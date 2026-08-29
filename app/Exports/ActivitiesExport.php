<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class ActivitiesExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;


    protected int $totalStudents;

    public function __construct(string $filename = '')
    {
        $this->totalStudents = Student::count();
        $this->writerType    = Excel::CSV;
        $this->headers       = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $this->fileName      = $filename ?: 'daftar_kegiatan_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }

    public function collection(): Collection
    {
        return Activity::with('attendances')->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Kegiatan',
            'Nama Kegiatan',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
            'Deskripsi',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpa / Belum Absen',
            'Total Mahasiswa',
        ];
    }

    public function map($activity): array
    {
        $attendances = $activity->attendances;
        $hadir = $attendances->where('status', 'hadir')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        $izin  = $attendances->where('status', 'izin')->count();
        $alpa  = $attendances->where('status', 'alpa')->count();

        // Mahasiswa yang belum tercatat absensinya dihitung sebagai alpa/belum absen
        $belumAbsen = $this->totalStudents - $attendances->count();
        $totalAlpa = $alpa + max(0, $belumAbsen);

        return [
            $activity->id,
            $activity->name,
            $activity->date ? $activity->date->format('d/m/Y') : '-',
            $activity->start_time ?? '-',
            $activity->end_time ?? '-',
            $activity->description ?? '-',
            $hadir,
            $sakit,
            $izin,
            $totalAlpa,
            $this->totalStudents,
        ];
    }
}
