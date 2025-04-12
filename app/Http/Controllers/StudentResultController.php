<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    public function studentResult()
    {
        $page_title = 'Student Result';
        $rollNumber = auth()->user()->username;
        return view('student.student_result', compact('page_title', 'rollNumber'));
    }
}
