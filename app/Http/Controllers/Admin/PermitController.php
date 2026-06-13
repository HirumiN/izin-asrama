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
        // 1. Pengajuan Masuk (Pending)
        $pendingPermits = Permit::with('student.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Mahasiswa Sedang Keluar (Approved / Active)
        $activePermits = Permit::with('student.user')
            ->where('status', 'approved')
            ->orderBy('end_time', 'asc')
            ->get();

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

        $historyPermits = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('pendingPermits', 'activePermits', 'historyPermits'));
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

        $message = $permit->status === 'returned_late' 
            ? "Lapor kembali berhasil. Mahasiswa terlambat selama {$permit->lateness_duration} menit."
            : "Lapor kembali berhasil. Mahasiswa kembali tepat waktu.";

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}
