<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class PublicInfoController extends Controller
{
    public function index(Request $request)
    {
        $student = null;
        $historyPermits = null;
        $maskedName = null;
        $activePermit = null;
        $activityAttendances = null;

        if ($request->filled('nim')) {
            // Validasi: NIM hanya boleh alfanumerik, maks 50 karakter
            $request->validate([
                'nim' => 'required|string|alpha_num|max:50',
            ], [
                'nim.alpha_num' => 'NIM hanya boleh mengandung huruf dan angka.',
                'nim.max'       => 'NIM tidak boleh lebih dari 50 karakter.',
            ]);

            $student = Student::with('user')->where('nim', $request->nim)->first();

            if ($student) {
                $maskedName = $this->maskName($student->user->name);

                $activePermit = $student->permits()->where('status', 'approved')->first();

                $historyPermits = $student->permits()
                    ->orderBy('created_at', 'desc')
                    ->paginate(10, ['*'], 'permit_page')
                    ->withQueryString();

                $activityAttendances = $student->activityAttendances()
                    ->with('activity')
                    ->orderByDesc('created_at')
                    ->paginate(10, ['*'], 'activity_page')
                    ->withQueryString();
            }
        }

        return view('public.student-info', compact('student', 'historyPermits', 'maskedName', 'activePermit', 'activityAttendances'));
    }

    private function maskName($name)
    {
        $parts = explode(' ', trim($name));
        if (count($parts) === 1) {
            return substr($parts[0], 0, 3) . str_repeat('*', max(1, strlen($parts[0]) - 3));
        }

        $masked = $parts[0] . ' ' . substr($parts[1], 0, 1) . str_repeat('*', max(1, strlen($parts[1]) - 1));
        for ($i = 2; $i < count($parts); $i++) {
            $masked .= ' ' . str_repeat('*', strlen($parts[$i]));
        }
        
        return $masked;
    }
}
