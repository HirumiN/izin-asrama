<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->withErrors(['email' => 'Profil mahasiswa tidak ditemukan.']);
        }

        // Mengambil pengajuan aktif (yang sudah di-ACC tapi belum kembali)
        $activePermit = Permit::where('student_id', $student->id)
            ->where('status', 'approved')
            ->first();

        // Mengambil pengajuan yang masih menunggu persetujuan
        $pendingPermit = Permit::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        // Mengambil riwayat izin terdahulu (ditolak, kembali tepat waktu, kembali telat)
        $historyPermits = Permit::where('student_id', $student->id)
            ->whereIn('status', ['rejected', 'returned_on_time', 'returned_late'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dashboard', compact('student', 'activePermit', 'pendingPermit', 'historyPermits'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', 'Profil mahasiswa tidak valid.');
        }

        // Cek apakah ada izin aktif atau pending
        $hasPendingOrActive = Permit::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasPendingOrActive) {
            return back()->with('error', 'Anda masih memiliki izin yang aktif atau sedang menunggu persetujuan.');
        }

        $request->validate([
            'type' => 'required|in:pesiar,bermalam',
            'destination' => 'required|string|max:255',
            'reason' => 'required_if:type,bermalam|nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required_if:type,bermalam|nullable|date|after:start_time',
        ], [
            'reason.required_if' => 'Alasan wajib diisi untuk izin bermalam.',
            'end_time.required_if' => 'Tanggal kembali wajib diisi untuk izin bermalam.',
            'end_time.after' => 'Tanggal kembali harus setelah tanggal keluar.',
        ]);

        $permit = new Permit();
        $permit->student_id = $student->id;
        $permit->type = $request->type;
        $permit->destination = $request->destination;
        $permit->reason = $request->reason;
        
        // Parsing input waktu ke format Carbon datetime
        $permit->start_time = Carbon::parse($request->start_time);
        
        if ($request->type === 'bermalam') {
            $permit->end_time = Carbon::parse($request->end_time);
        }

        $permit->status = 'pending';
        $permit->save();

        return redirect()->route('student.dashboard')->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}
