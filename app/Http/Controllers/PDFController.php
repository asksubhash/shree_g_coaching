<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Traits\PdfGeneratorTrait;
use App\Models\StudentSubjectMapping;
use App\Models\StudentNLSubjectMapping;
use App\Models\StudyCenter;

class PDFController extends Controller
{
    use PdfGeneratorTrait;

    public function showHighSchoolApplication($id)
    {
        $data = Student::getHighSchoolStudentDetailsUsingId($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;
        $data['logo'] = asset('website_assets/images/pdf-heading.jpg');

        // @if(Storage::exists('public/'.$photoPath))
        // <img src="{{ asset('storage/'.$photoPath) }}" alt="Image" class="stu-profile-img" />
        // @endif

        $view = view('pdf.high_school_student_application', [
            'user' => $data['user'],
            'courseSubjects' => $data['courseSubjects'],
            'nonLanguageSubjects' => $data['nonLanguageSubjects'],
            'logo' => $data['logo']
        ])->render();

        // dd($view);

        $this->generateAndShowPdf($view);
    }

    public function showInterApplication($id)
    {
        $data = Student::getHighSchoolStudentDetailsUsingId($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;

        $view = view('pdf.inter_school_student_application', [
            'user' => $data['user'],
            'courseSubjects' => $data['courseSubjects'],
            'nonLanguageSubjects' => $data['nonLanguageSubjects'],
        ])->render();

        // dd($view);

        $this->generateAndShowPdf($view);
    }

    public function showGraduationApplication($id)
    {
        $data = Student::getGraduationSchoolStudentDetailsUsingId($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;

        $view = view('pdf.graduation_student_application', [
            'user' => $data['user'],
            'courseSubjects' => $data['courseSubjects'],
            'nonLanguageSubjects' => $data['nonLanguageSubjects'],
        ])->render();

        // dd($view);

        $this->generateAndShowPdf($view);
    }

    public function showStudyCenterApplication($id)
    {
        $scData = StudyCenter::getStudyCenterDetailsUsingId($id);

        $data['page_title'] = "Study Center: " . $scData->institute_name;

        $view = view('pdf.study_center_application', [
            'scData' => $scData
        ])->render();

        // dd($view);

        $this->generateAndShowPdf($view);
    }
}
