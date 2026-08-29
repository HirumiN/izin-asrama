<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\PrayerAttendance;
use App\Exports\PrayerAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PrayerMonitoringController extends Controller
{
    public function exportCsv(Request $request)
    {
        $selectedDate = $request->input('date', today()->format('Y-m-d'));
        $filename = 'absen_shalat_' . $selectedDate . '_' . now()->format('H-i-s') . '.csv';
        $csvData = Excel::raw(new PrayerAttendanceExport($request), \Maatwebsite\Excel\Excel::CSV);

        return response($csvData, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
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

        $totalStudents = Student::count();

        // FIX N+1: Satu query GROUP BY untuk semua statistik
        // Sebelumnya: 4 COUNT query × 5 waktu shalat = 20 query terpisah
        // Sekarang: 1 query aggregate GROUP BY prayer_time + status
        $statsRaw = PrayerAttendance::query()->whereDate('date', $selectedDate)
            ->selectRaw('prayer_time, status, COUNT(*) as total')
            ->groupBy('prayer_time', 'status')
            ->get()
            ->groupBy('prayer_time');

        $stats = [];
        foreach ($prayers as $prayer) {
            $prayerStats = $statsRaw->get($prayer, collect())->keyBy('status');
            $berjamaah = (int) ($prayerStats->get('berjamaah')?->total ?? 0);
            $munfarid  = (int) ($prayerStats->get('munfarid')?->total ?? 0);
            $sakit     = (int) ($prayerStats->get('sakit')?->total ?? 0);
            $izin      = (int) ($prayerStats->get('izin')?->total ?? 0);

            $stats[$prayer] = [
                'berjamaah' => $berjamaah,
                'munfarid'  => $munfarid,
                'sakit'     => $sakit,
                'izin'      => $izin,
                'alpa'      => max(0, $totalStudents - ($berjamaah + $munfarid + $sakit + $izin)),
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
