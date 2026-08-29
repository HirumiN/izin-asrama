<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;


    protected Request $request;

    public function __construct(Request $request, string $filename = '')
    {
        $this->request  = $request;
        $this->writerType = Excel::CSV;
        $this->headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $this->fileName = $filename ?: 'data_mahasiswa_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }

    public function query(): Builder
    {
        $query = Student::with('user');

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->request->filled('status')) {
            $query->where('is_suspended', $this->request->status === 'ditangguhkan');
        }

        if ($this->request->filled('registered_since')) {
            $query->whereDate('created_at', '>=', $this->request->registered_since);
        }

        $sort = $this->request->input('sort', 'terbaru');
        $sortMap = [
            'az'      => ['join' => true, 'column' => 'name', 'dir' => 'asc'],
            'za'      => ['join' => true, 'column' => 'name', 'dir' => 'desc'],
            'terlama' => ['join' => false, 'column' => 'created_at', 'dir' => 'asc'],
            'terbaru' => ['join' => false, 'column' => 'created_at', 'dir' => 'desc'],
        ];

        $sortConfig = $sortMap[$sort] ?? $sortMap['terbaru'];

        if ($sortConfig['join']) {
            $query->join('users', 'students.user_id', '=', 'users.id')
                  ->orderByRaw("users.{$sortConfig['column']} {$sortConfig['dir']}")
                  ->select('students.*');
        } else {
            $query->orderByRaw("students.{$sortConfig['column']} {$sortConfig['dir']}");
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID Mahasiswa',
            'NIM',
            'Nama Mahasiswa',
            'Email',
            'Kamar Asrama',
            'No. Telepon',
            'Status Akun',
            'Waktu Ditangguhkan',
            'Tanggal Terdaftar',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->nim ?? '-',
            $student->user->name ?? '-',
            $student->user->email ?? '-',
            $student->dorm_room ?? '-',
            $student->phone ?? '-',
            $student->is_suspended ? 'Ditangguhkan' : 'Aktif',
            $student->suspended_at ? $student->suspended_at->format('d/m/Y H:i') : '-',
            $student->created_at ? $student->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
