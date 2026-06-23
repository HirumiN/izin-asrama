<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    public function index(Request $request)
    {
        // Pengajuan Masuk (Pending) - dipisah per jenis izin
        $pendingPesiar = Permit::with('student.user')
            ->where('status', 'pending')
            ->where('type', 'pesiar')
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'pending_pesiar_page')
            ->withQueryString();

        $pendingBermalam = Permit::with('student.user')
            ->where('status', 'pending')
            ->where('type', 'bermalam')
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'pending_bermalam_page')
            ->withQueryString();

        $pendingPermitsCount = Permit::where('status', 'pending')->count();

        // Mahasiswa Sedang Keluar (Approved / Aktif) - dipisah per jenis izin
        $activePesiar = Permit::with('student.user')
            ->where('status', 'approved')
            ->where('type', 'pesiar')
            ->orderBy('end_time', 'asc')
            ->paginate(10, ['*'], 'active_pesiar_page')
            ->withQueryString();

        $activeBermalam = Permit::with('student.user')
            ->where('status', 'approved')
            ->where('type', 'bermalam')
            ->orderBy('end_time', 'asc')
            ->paginate(10, ['*'], 'active_bermalam_page')
            ->withQueryString();

        $activePermitsCount = Permit::where('status', 'approved')->count();

        // Riwayat Semua Izin dengan Filter Pencarian
        $query = Permit::with(['student.user', 'actionBy'])
            ->whereIn('status', ['rejected', 'returned_on_time', 'returned_late']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%")
            );
        }

        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $historyPermits = $query
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'history_page')
            ->withQueryString();

        return view('admin.dashboard', compact(
            'pendingPesiar',
            'pendingBermalam',
            'pendingPermitsCount',
            'activePesiar',
            'activeBermalam',
            'activePermitsCount',
            'historyPermits'
        ));
    }

    public function approve(Request $request, Permit $permit)
    {
        if ($permit->status !== 'pending') {
            return back()->with('error', 'Status pengajuan tidak valid untuk disetujui.');
        }

        $this->applyPermitDecision($permit, 'approved', $request->input('admin_note'));

        return redirect()->route('admin.dashboard')->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function reject(Request $request, Permit $permit)
    {
        if ($permit->status !== 'pending') {
            return back()->with('error', 'Status pengajuan tidak valid untuk ditolak.');
        }

        $this->applyPermitDecision($permit, 'rejected', $request->input('admin_note'));

        return redirect()->route('admin.dashboard')->with('success', 'Pengajuan izin telah ditolak.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'permit_ids'   => 'required|array',
            'permit_ids.*' => 'exists:permits,id',
            'action'       => 'required|in:approve,reject',
        ]);

        $action = $request->action;
        $newStatus = $action === 'approve' ? 'approved' : 'rejected';
        $count = 0;

        foreach ($request->permit_ids as $id) {
            $permit = Permit::find($id);
            if ($permit && $permit->status === 'pending') {
                $this->applyPermitDecision($permit, $newStatus);
                $count++;
            }
        }

        $message = $action === 'approve'
            ? "Berhasil menyetujui {$count} pengajuan izin."
            : "Berhasil menolak {$count} pengajuan izin.";

        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    public function markReturned(Permit $permit)
    {
        if ($permit->status !== 'approved') {
            return back()->with('error', 'Mahasiswa tidak sedang dalam status izin keluar.');
        }

        $now = Carbon::now();
        $endTime = Carbon::parse($permit->end_time);
        $isLate = $now->greaterThan($endTime);

        $permit->actual_return_time = $now;
        $permit->status = $isLate ? 'returned_late' : 'returned_on_time';
        $permit->lateness_duration = $isLate ? (int) $endTime->diffInMinutes($now) : 0;
        $permit->save();

        if ($isLate) {
            $student = $permit->student;
            $student->is_suspended = true;
            $student->suspended_at = $now;
            $student->save();
        }

        $message = $isLate
            ? "Lapor kembali berhasil. Mahasiswa terlambat selama {$permit->lateness_duration} menit dan telah ditangguhkan."
            : "Lapor kembali berhasil. Mahasiswa kembali tepat waktu.";

        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Terapkan keputusan (setuju/tolak) pada permit dan simpan ke database.
     * Untuk status 'approved', batas waktu (end_time) dihitung otomatis.
     */
    private function applyPermitDecision(Permit $permit, string $status, ?string $adminNote = null): void
    {
        $permit->status     = $status;
        $permit->action_by  = Auth::id();
        $permit->action_at  = Carbon::now();
        $permit->admin_note = $adminNote;

        if ($status === 'approved') {
            // Pesiar: kembali hari yang sama jam 21:00
            // Bermalam: kembali sesuai tanggal yang diajukan mahasiswa jam 17:00
            $permit->end_time = $permit->type === 'pesiar'
                ? Carbon::parse($permit->start_time)->setTime(21, 0, 0)
                : Carbon::parse($permit->end_time)->setTime(17, 0, 0);
        }

        $permit->save();
    }
}
