<?php

namespace App\Exports;

use App\Models\Permit;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class PermitsExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    protected Request $request;

    public function __construct(Request $request, string $filename = '')
    {
        $this->request  = $request;
        $this->writerType = Excel::CSV;
        $this->headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $this->fileName = $filename ?: 'riwayat_izin_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }

    public function query(): Builder
    {
        $query = Permit::with(['student.user', 'actionBy'])
            ->whereIn('status', ['rejected', 'returned_on_time', 'returned_late']);

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->request->filled('date')) {
            $query->whereDate('start_time', $this->request->date);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->orderBy('updated_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID Izin',
            'NIM',
            'Nama Mahasiswa',
            'Kamar Asrama',
            'Jenis Izin',
            'Tujuan',
            'Waktu Keluar',
            'Batas Kembali',
            'Waktu Lapor Kembali',
            'Keterlambatan (Menit)',
            'Status',
            'Catatan Admin',
            'Tanggal Pengajuan',
        ];
    }

    public function map($permit): array
    {
        $statusLabels = [
            'pending'          => 'Menunggu ACC',
            'approved'         => 'Disetujui / Aktif',
            'rejected'         => 'Ditolak',
            'returned_on_time' => 'Kembali Tepat Waktu',
            'returned_late'    => 'Kembali Terlambat',
        ];

        return [
            $permit->id,
            $permit->student->nim ?? '-',
            $permit->student->user->name ?? '-',
            $permit->student->dorm_room ?? '-',
            ucwords(str_replace('_', ' ', $permit->type)),
            $permit->destination ?? '-',
            $permit->start_time ? $permit->start_time->format('d/m/Y H:i') : '-',
            $permit->end_time ? $permit->end_time->format('d/m/Y H:i') : '-',
            $permit->actual_return_time ? $permit->actual_return_time->format('d/m/Y H:i') : '-',
            $permit->lateness_duration ?? 0,
            $statusLabels[$permit->status] ?? $permit->status,
            $permit->admin_note ?? '-',
            $permit->created_at ? $permit->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
