<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->withErrors(['email' => 'Profil mahasiswa tidak ditemukan.']);
        }

        // Mengambil pengajuan aktif (yang sudah di-ACC tapi belum kembali)
        $activePermit = $student->permits()
            ->where(['status' => 'approved'])
            ->first();

        // Mengambil pengajuan yang masih menunggu persetujuan
        $pendingPermit = $student->permits()
            ->where(['status' => 'pending'])
            ->first();

        // Mengambil riwayat izin terdahulu (ditolak, kembali tepat waktu, kembali telat)
        $historyPermits = $student->permits()
            ->whereIn('status', ['rejected', 'returned_on_time', 'returned_late'])
            ->latest()
            ->get();

        return view('student.dashboard', compact('student', 'activePermit', 'pendingPermit', 'historyPermits'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', 'Profil mahasiswa tidak valid.');
        }

        // Cek apakah mahasiswa ditangguhkan
        if ($student->isSuspended()) {
            return back()->with('error', 'Hak izin Anda sedang ditangguhkan karena riwayat keterlambatan. Silakan hubungi pengelola asrama.');
        }

        // Cek apakah ada izin aktif atau pending
        $hasPendingOrActive = $student->permits()
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

    public function reportReturn(Request $request, Permit $permit)
    {
        $student = Auth::user()->student;

        if (!$student || $permit->student_id !== $student->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($permit->status !== 'approved') {
            return back()->with('error', 'Izin ini tidak sedang aktif.');
        }

        $request->validate([
            'return_photo' => 'required|string',
            'return_location' => 'required|string',
        ], [
            'return_photo.required' => 'Foto bukti pulang wajib diambil.',
            'return_location.required' => 'Lokasi GPS wajib diizinkan.',
        ]);

        try {
            $photoData = $request->input('return_photo');
            // Bersihkan prefix base64
            $photoData = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);
            $photoData = str_replace(' ', '+', $photoData);
            $imageBinary = base64_decode($photoData);

            if ($imageBinary === false) {
                return back()->with('error', 'Format foto tidak valid.');
            }

            // Simpan gambar
            $fileName = 'return_photos/' . $permit->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($fileName, $imageBinary);

            $now = Carbon::now();
            $isOverdue = $now->greaterThan($permit->end_time);
            $latenessDuration = 0;
            if ($isOverdue) {
                $latenessDuration = (int) abs($now->diffInMinutes($permit->end_time));
            }

            $permit->actual_return_time = $now;
            $permit->lateness_duration = $isOverdue ? $latenessDuration : 0;
            $permit->status = $isOverdue ? 'returned_late' : 'returned_on_time';
            $permit->return_photo = $fileName;
            $permit->return_location = $request->input('return_location');
            $permit->save();

            // Auto-suspend mahasiswa jika terlambat
            if ($isOverdue) {
                $student->is_suspended = true;
                $student->suspended_at = $now;
                $student->save();
            }

            return redirect()->route('student.dashboard')->with('success', 'Laporan kepulangan berhasil dikirim. Anda telah kembali ke asrama.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function latestStatus()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return response()->json(['error' => 'Profil mahasiswa tidak ditemukan.'], 404);
        }

        $latestPermit = $student->permits()->latest()->first();

        return response()->json([
            'latest' => $latestPermit ? [
                'id' => $latestPermit->id,
                'status' => $latestPermit->status,
                'destination' => $latestPermit->destination,
                'admin_note' => $latestPermit->admin_note,
            ] : null
        ]);
    }
}
