<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CityCorporationNotice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CityNoticesController extends Controller
{
    public function index()
    {
        
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        // School Information
        $school = $student->school;
        
        $schoolId = $student->school_id;
        $cityNotices = CityCorporationNotice::active()
            // ->forSchool($schoolId)
            ->forRoles(['student'])
            ->latest()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('student.city-notices.index', compact('cityNotices'));
    }

    public function show($id)
    {
        $notice = CityCorporationNotice::where('id', $id)
            ->firstOrFail();

        return view('student.city-notices.show', compact('notice'));
    }
}