<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class PrayerAttendanceExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;


    protected Request $request;
    protected string $selectedDate;

    public function __construct(Request $request, string $filename = '')
    {
        $this->request      = $request;
        $this->selectedDate = $request->input('date', today()->format('Y-m-d'));
        $this->writerType   = Excel::CSV;
        $this->headers      = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $this->fileName     = $filename ?: 'absen_shalat_' . $this->selectedDate . '_' . now()->format('H-i-s') . '.csv';
    }

    public function collection(): Collection
    {
        $selectedDate = $this->selectedDate;
        $search = $this->request->input('search');

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

        return $studentsQuery->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'NIM',
            'Nama Mahasiswa',
            'Kamar Asrama',
            'Subuh',
            'Dzuhur',
            'Ashar',
            'Maghrib',
            'Isya',
        ];
    }

    public function map($student): array
    {
        $prayers = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $attendances = $student->prayerAttendances->keyBy('prayer_time');

        $row = [
            $this->selectedDate,
            $student->nim ?? '-',
            $student->user->name ?? '-',
            $student->dorm_room ?? '-',
        ];

        foreach ($prayers as $prayer) {
            $attendance = $attendances->get($prayer);
            if ($attendance) {
                $row[] = ucfirst($attendance->status);
            } else {
                $row[] = 'Alpa';
            }
        }

        return $row;
    }
}
