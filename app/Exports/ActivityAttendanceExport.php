<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class ActivityAttendanceExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;


    protected Activity $activity;

    public function __construct(Activity $activity, string $filename = '')
    {
        $this->activity = $activity;
        $this->writerType = Excel::CSV;
        $this->headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $this->fileName = $filename ?: 'absensi_' . Str::slug($activity->name) . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }

    public function collection(): Collection
    {
        return Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('students.*')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kegiatan',
            'Tanggal Kegiatan',
            'NIM',
            'Nama Mahasiswa',
            'Kamar Asrama',
            'Status Kehadiran',
            'Waktu Absen',
            'Catatan / Alasan',
        ];
    }

    public function map($student): array
    {
        $attendance = $this->activity->attendances->where('student_id', $student->id)->first();

        $statusLabel = 'Belum Absen / Alpa';
        $waktuAbsen  = '-';
        $notes       = '-';

        if ($attendance) {
            $statusLabel = ucfirst($attendance->status);
            $waktuAbsen  = $attendance->created_at ? $attendance->created_at->format('d/m/Y H:i') : '-';
            $notes       = $attendance->notes ?? '-';
        }

        return [
            $this->activity->name,
            $this->activity->date ? $this->activity->date->format('d/m/Y') : '-',
            $student->nim ?? '-',
            $student->user->name ?? '-',
            $student->dorm_room ?? '-',
            $statusLabel,
            $waktuAbsen,
            $notes,
        ];
    }
}
