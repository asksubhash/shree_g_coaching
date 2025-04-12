<?php

namespace App\Http\Controllers;

use App\Models\MarksEntry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadResultController extends Controller
{
    public function downloadSingleStudentResult($examId, $studentRollNo, Request $request)
    {
        $studentResult = MarksEntry::where([
            'exam_id' => $examId,
            'student_roll_no' => $studentRollNo
        ])->with('subject')->with('course')->with('institute')->orderBy('id', 'DESC')->get();


        if (count($studentResult->toArray()) < 1) {
            return back();
        }

        $pdf = Pdf::loadView('export.download_result.download_result', [
            'page_title' => 'Student Result',
            'studentResult' => $studentResult
        ]);

        return $pdf->stream();
        // return view('export.download_result.download_result', [
        //     'page_title' => 'Student Result',
        //     'studentResult' => $studentResult
        // ]);
    }
}
