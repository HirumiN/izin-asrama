<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PrayerAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        
        // Ambil absen hari ini
        $todayAttendances = $student->prayerAttendances()
            ->whereDate('date', today())
            ->get()
            ->keyBy('prayer_time');

        // Daftar waktu shalat
        $prayers = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        // Ambil riwayat tanggal-tanggal sebelumnya (paginated)
        $historyDates = $student->prayerAttendances()
            ->whereDate('date', '<', today())
            ->select('date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(7);

        // Ambil data absen berdasarkan tanggal-tanggal tersebut
        $historyAttendances = $student->prayerAttendances()
            ->whereIn('date', $historyDates->pluck('date'))
            ->get()
            ->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        return view('student.sholat.index', compact(
            'todayAttendances',
            'prayers',
            'historyDates',
            'historyAttendances'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prayer_time' => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
            'status' => 'required|in:berjamaah,munfarid,sakit,izin',
        ]);

        $student = Auth::user()->student;

        // Simpan atau update absen shalat hari ini
        $student->prayerAttendances()->updateOrCreate(
            [
                'date' => today()->format('Y-m-d'),
                'prayer_time' => $request->prayer_time,
            ],
            [
                'status' => $request->status,
            ]
        );

        return redirect()->back()->with('success', 'Absen shalat ' . ucfirst($request->prayer_time) . ' berhasil disimpan!');
    }
}
