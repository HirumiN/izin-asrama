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
        // 1. Pengajuan Masuk (Pending) - Paginated
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

        // Total Pending Count
        $pendingPermitsCount = Permit::where('status', 'pending')->count();

        // 2. Mahasiswa Sedang Keluar (Approved / Active) - Paginated
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

        // Total Active/Approved Count
        $activePermitsCount = Permit::where('status', 'approved')->count();

        // 3. Tabel Riwayat Semua Izin (Ditolak, Tepat Waktu, Telat) dengan Filter
        $query = Permit::with(['student.user', 'actionBy'])
            ->whereIn('status', ['rejected', 'returned_on_time', 'returned_late']);

        // Filter Pencarian Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $historyPermits = $query->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'history_page')->withQueryString();

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

    public function approve(Permit $permit)
    {
        if ($permit->status !== 'pending') {
            return back()->with('error', 'Status pengajuan tidak valid untuk disetujui.');
        }

        $permit->status = 'approved';
        $permit->action_by = Auth::id();
        $permit->action_at = Carbon::now();

        // Mengatur batas waktu (end_time) berdasarkan jenis izin
        if ($permit->type === 'pesiar') {
            // Untuk Pesiar: Batas waktu kembali adalah jam malam hari yang sama (misalnya pukul 21:00)
            $permit->end_time = Carbon::parse($permit->start_time)->setTime(21, 0, 0);
        } else {
            // Untuk Bermalam: Mengikuti tanggal kembali yang telah diisi mahasiswa, batas waktu jam 17:00 sore
            $permit->end_time = Carbon::parse($permit->end_time)->setTime(17, 0, 0);
        }

        $permit->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function reject(Permit $permit)
    {
        if ($permit->status !== 'pending') {
            return back()->with('error', 'Status pengajuan tidak valid untuk ditolak.');
        }

        $permit->status = 'rejected';
        $permit->action_by = Auth::id();
        $permit->action_at = Carbon::now();
        $permit->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pengajuan izin telah ditolak.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'permit_ids' => 'required|array',
            'permit_ids.*' => 'exists:permits,id',
            'action' => 'required|in:approve,reject',
        ]);

        $permitIds = $request->permit_ids;
        $action = $request->action;
        $count = 0;

        foreach ($permitIds as $id) {
            $permit = Permit::find($id);
            if ($permit && $permit->status === 'pending') {
                if ($action === 'approve') {
                    $permit->status = 'approved';
                    $permit->action_by = Auth::id();
                    $permit->action_at = Carbon::now();

                    if ($permit->type === 'pesiar') {
                        $permit->end_time = Carbon::parse($permit->start_time)->setTime(21, 0, 0);
                    } else {
                        $permit->end_time = Carbon::parse($permit->end_time)->setTime(17, 0, 0);
                    }
                    $permit->save();
                    $count++;
                } elseif ($action === 'reject') {
                    $permit->status = 'rejected';
                    $permit->action_by = Auth::id();
                    $permit->action_at = Carbon::now();
                    $permit->save();
                    $count++;
                }
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

        $permit->actual_return_time = $now;

        if ($now->greaterThan($endTime)) {
            // Terlambat
            $permit->status = 'returned_late';
            // Hitung durasi keterlambatan dalam menit
            $permit->lateness_duration = $endTime->diffInMinutes($now);
        } else {
            // Tepat waktu
            $permit->status = 'returned_on_time';
            $permit->lateness_duration = 0;
        }

        $permit->save();

        // Auto-suspend mahasiswa jika terlambat
        if ($permit->status === 'returned_late') {
            $student = $permit->student;
            $student->is_suspended = true;
            $student->suspended_at = Carbon::now();
            $student->save();
        }

        $message = $permit->status === 'returned_late' 
            ? "Lapor kembali berhasil. Mahasiswa terlambat selama {$permit->lateness_duration} menit dan telah ditangguhkan."
            : "Lapor kembali berhasil. Mahasiswa kembali tepat waktu.";

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}
