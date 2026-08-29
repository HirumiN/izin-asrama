<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Student;
use App\Exports\ActivitiesExport;
use App\Exports\ActivityAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function exportCsv()
    {
        $filename = 'daftar_kegiatan_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $csvData = Excel::raw(new ActivitiesExport(), \Maatwebsite\Excel\Excel::CSV);

        return response($csvData, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }

    public function exportAttendanceCsv(Activity $activity)
    {
        $filename = 'absensi_' . Str::slug($activity->name) . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $csvData = Excel::raw(new ActivityAttendanceExport($activity), \Maatwebsite\Excel\Excel::CSV);

        return response($csvData, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
    public function index()
    {
        $activities = Activity::orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalStudents = Student::count();

        return view('admin.activities.index', compact('activities', 'totalStudents'));
    }

    /**
     * Tampilkan form pembuatan kegiatan baru.
     */
    public function create()
    {
        return view('admin.activities.create');
    }

    /**
     * Simpan kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kegiatan wajib diisi.',
            'date.required' => 'Tanggal kegiatan wajib diisi.',
            'start_time.required' => 'Jam mulai absensi wajib diisi.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'end_time.required' => 'Jam selesai absensi wajib diisi.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
            'end_time.after' => 'Jam selesai absensi harus setelah jam mulai.',
        ]);

        Activity::create([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan baru berhasil dibuat.');
    }

    /**
     * Tampilkan halaman monitoring absensi kehadiran kegiatan (read-only).
     * Mahasiswa melakukan absen mandiri dari akun masing-masing.
     */
    public function showAttendance(Activity $activity)
    {
        // Ambil semua mahasiswa beserta akun user-nya
        $students = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('students.*')
            ->get();

        // Ambil absensi yang sudah tercatat, di-index per student_id
        $attendances = $activity->attendances->keyBy('student_id');

        // Hitung statistik ringkasan
        $stats = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'izin'  => $attendances->where('status', 'izin')->count(),
            'alpa'  => $attendances->where('status', 'alpa')->count(),
        ];
        // Mahasiswa yang belum absen sama sekali dihitung sebagai alpa
        $stats['belum_absen'] = $students->count() - $attendances->count();
        $stats['total'] = $students->count();

        return view('admin.activities.attendance', compact('activity', 'students', 'attendances', 'stats'));
    }

    /**
     * Menghapus beberapa kegiatan beserta seluruh riwayat absensinya secara masal.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'activity_ids'   => 'required|array',
            'activity_ids.*' => 'exists:activities,id',
        ], [
            'activity_ids.required' => 'Pilihlah minimal satu kegiatan untuk dihapus.',
        ]);

        $count = Activity::whereIn('id', $request->activity_ids)->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', "Berhasil menghapus {$count} kegiatan asrama beserta seluruh riwayat absensinya.");
    }
}
