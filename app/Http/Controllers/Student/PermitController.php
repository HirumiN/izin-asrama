<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Carbon\Carbon;
use Carbon\Constants\UnitValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
{
    const PRODITA_LAT = -8.0967762;
    const PRODITA_LNG = 112.1791154;
    const ALLOWED_RADIUS_METERS = 200;

    /**
     * Menghitung jarak antara dua koordinat menggunakan rumus Haversine (dalam meter).
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Jari-jari bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

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
            ->paginate(10)
            ->withQueryString();

        // Mengambil riwayat absensi kegiatan kustom mahasiswa
        $activityAttendances = $student->activityAttendances()
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengambil kegiatan hari ini
        $todayActivities = \App\Models\Activity::whereDate('date', today())->get();
        $todayAttendances = $student->activityAttendances()
            ->whereIn('activity_id', $todayActivities->pluck('id'))
            ->get()
            ->keyBy('activity_id');

        return view('student.dashboard', compact(
            'student', 
            'activePermit', 
            'pendingPermit', 
            'historyPermits', 
            'activityAttendances',
            'todayActivities',
            'todayAttendances'
        ));
    }

    /**
     * Halaman daftar kegiatan kustom mahasiswa:
     * menampilkan kegiatan hari ini & riwayat absensi.
     */
    public function activityIndex()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->withErrors(['email' => 'Profil mahasiswa tidak ditemukan.']);
        }

        // Kegiatan hari ini beserta status kehadiran mahasiswa
        $todayActivities = \App\Models\Activity::whereDate('date', today())->get();
        $todayAttendances = $student->activityAttendances()
            ->whereIn('activity_id', $todayActivities->pluck('id'))
            ->get()
            ->keyBy('activity_id');

        // Riwayat absensi kegiatan (sebelum hari ini)
        $historyAttendances = $student->activityAttendances()
            ->with('activity')
            ->whereHas('activity', fn($q) => $q->whereDate('date', '<', today()))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('student.activities.index', compact(
            'student',
            'todayActivities',
            'todayAttendances',
            'historyAttendances'
        ));
    }

    /**
     * Catat absensi mandiri mahasiswa untuk kegiatan kustom tertentu.
     */
    public function storeActivityAttendance(Request $request, \App\Models\Activity $activity)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // Validasi tanggal kegiatan harus hari ini
        if (!$activity->date->isToday()) {
            return back()->with('error', 'Waktu absensi untuk kegiatan ini telah berakhir atau belum dimulai.');
        }

        // Validasi waktu saat ini berada di antara start_time dan end_time
        $now = Carbon::now();
        $startTime = Carbon::parse($activity->start_time);
        $endTime = Carbon::parse($activity->end_time);

        if ($now->lessThan($startTime) || $now->greaterThan($endTime)) {
            return back()->with('error', 'Waktu absensi untuk kegiatan ini telah berakhir atau belum dimulai.');
        }

        // Buat atau update absensi sebagai hadir
        $student->activityAttendances()->updateOrCreate(
            ['activity_id' => $activity->id],
            [
                'status' => 'hadir',
                'notes' => 'Absen Mandiri',
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Absensi kegiatan berhasil direkam.');
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
            'type' => 'required|in:pesiar,bermalam_biasa,bermalam_urgensi',
            'destination' => 'required|string|max:255',
            'reason' => 'required_unless:type,pesiar|nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required_if:type,bermalam_urgensi|nullable|date|after:start_time',
        ], [
            'reason.required_unless' => 'Alasan wajib diisi untuk izin bermalam.',
            'end_time.required_if' => 'Tanggal kembali wajib diisi untuk izin bermalam urgensi.',
            'end_time.after' => 'Tanggal kembali harus setelah tanggal keluar.',
        ]);

        $permit = new Permit();
        $permit->student_id = $student->id;
        $permit->type = $request->type;
        $permit->destination = $request->destination;
        $permit->reason = $request->reason;
        
        // Parsing input waktu ke format Carbon datetime
        $permit->start_time = Carbon::parse($request->start_time);
        
        if ($request->type === 'bermalam_urgensi') {
            $permit->end_time = Carbon::parse($request->end_time)->setTime(6, 30, 0);
        } elseif ($request->type === 'bermalam_biasa') {
            // Otomatis menetapkan Senin terdekat pukul 06:30 WIB
            $permit->end_time = Carbon::parse($request->start_time)->next(UnitValue::MONDAY)->setTime(6, 30, 0);
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

        // Validasi Geofencing
        $location = $request->input('return_location');
        if (preg_match('/Lat:\s*([-\d.]+),\s*Lng:\s*([-\d.]+)/i', $location, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];

            $distance = $this->calculateDistance(self::PRODITA_LAT, self::PRODITA_LNG, $lat, $lng);
            if ($distance > self::ALLOWED_RADIUS_METERS) {
                return back()->with('error', 'Gagal melaporkan kepulangan: Lokasi Anda terdeteksi di luar area asrama PRODITA (jarak: ' . round($distance) . ' meter, maksimum: ' . self::ALLOWED_RADIUS_METERS . ' meter).');
            }
        } else {
            return back()->with('error', 'Gagal melaporkan kepulangan: Koordinat GPS tidak valid atau harus diizinkan.');
        }

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
