<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\PrayerAttendance;
use Illuminate\Http\Request;

class PrayerMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', today()->format('Y-m-d'));
        $search = $request->input('search');

        // Query mahasiswa beserta data absen shalatnya pada tanggal yang dipilih
        $studentsQuery = Student::with(['user', 'prayerAttendances' => function ($q) use ($selectedDate) {
            $q->whereDate('date', $selectedDate);
        }]);

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $students = $studentsQuery->paginate(10)->withQueryString();

        // Daftar waktu shalat
        $prayers = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        // Hitung statistik absensi shalat harian pada tanggal terpilih
        $stats = [];
        $totalStudents = Student::count();

        foreach ($prayers as $prayer) {
            $berjamaah = PrayerAttendance::whereDate('date', $selectedDate)->where('prayer_time', $prayer)->where('status', 'berjamaah')->count();
            $munfarid = PrayerAttendance::whereDate('date', $selectedDate)->where('prayer_time', $prayer)->where('status', 'munfarid')->count();
            $sakit = PrayerAttendance::whereDate('date', $selectedDate)->where('prayer_time', $prayer)->where('status', 'sakit')->count();
            $izin = PrayerAttendance::whereDate('date', $selectedDate)->where('prayer_time', $prayer)->where('status', 'izin')->count();
            $alpa = $totalStudents - ($berjamaah + $munfarid + $sakit + $izin);

            $stats[$prayer] = [
                'berjamaah' => $berjamaah,
                'munfarid' => $munfarid,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => max(0, $alpa),
            ];
        }

        return view('admin.sholat.index', compact(
            'students',
            'prayers',
            'selectedDate',
            'stats',
            'totalStudents',
            'search'
        ));
    }
}
