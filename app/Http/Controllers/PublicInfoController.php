<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Permit;
use Illuminate\Http\Request;

class PublicInfoController extends Controller
{
    public function index(Request $request)
    {
        $student = null;
        $historyPermits = null;
        $maskedName = null;

        if ($request->filled('nim')) {
            $student = Student::with('user')->where('nim', $request->nim)->first();

            if ($student) {
                $maskedName = $this->maskName($student->user->name);

                $historyPermits = Permit::where('student_id', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
                    ->withQueryString();
            }
        }

        return view('public.student-info', compact('student', 'historyPermits', 'maskedName'));
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
