<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamStudentsEnrollment;
use App\Models\NonLanguageSubject;
use Auth;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmitCardController extends Controller
{
    /**
     * Function for student assignments list
     */
    public function studentAdmitCard(Request $request)
    {
        $pageTitle = 'Admit Card';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        // $assignments = Assignment::getStudentAssignments($user->course);

        // Get the student admit card
        $studentEnrollment = ExamStudentsEnrollment::getStudentExamEnrollmentUsingStudentId($data['studentDetails']['id']);

        // Get the exam details
        $exam = Exam::getExamUsingExamId($studentEnrollment->exam_id);

        if (!$exam) {
            $errorMessage = 'No Exams are not available';
            return view('errors.custom.common_error', compact('errorMessage'));
        }

        $subjects = $data['courseSubjects']->toArray();
        $subjectIds = array_column($subjects, 'subject_id');
        $studentSubjects = Subject::getStudentSubjectWithExamTimings($subjectIds, $studentEnrollment->exam_id);

        $nlSubjects = $data['nonLanguageSubjects']->toArray();
        $nlSubjectIds = array_column($nlSubjects, 'subject_id');
        $nlStudentSubjects = NonLanguageSubject::getNLStudentSubjectWithExamTimings($nlSubjectIds, $studentEnrollment->exam_id);


        if ($data) {
            return view('student.admit_card', [
                'page_title' => $pageTitle,
                'user' => $data['studentDetails'],
                'studentEnrollment' => $studentEnrollment,
                'exam' => $exam,
                'studentSubjects' => $studentSubjects,
                'nlStudentSubjects' => $nlStudentSubjects,
                // 'assignments' => $assignments
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function downloadstudentAdmitCard(Request $request)
    {
        $pageTitle = 'Admit Card';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        // $assignments = Assignment::getStudentAssignments($user->course);

        // Get the student admit card
        $studentEnrollment = ExamStudentsEnrollment::getStudentExamEnrollmentUsingStudentId($data['studentDetails']['id']);

        // Get the exam details
        $exam = Exam::getExamUsingExamId($studentEnrollment->exam_id);

        if (!$exam) {
            $errorMessage = 'No Exams are not available';
            return view('errors.custom.common_error', compact('errorMessage'));
        }

        $subjects = $data['courseSubjects']->toArray();
        $subjectIds = array_column($subjects, 'subject_id');
        $studentSubjects = Subject::getStudentSubjectWithExamTimings($subjectIds, $studentEnrollment->exam_id);

        $nlSubjects = $data['nonLanguageSubjects']->toArray();
        $nlSubjectIds = array_column($nlSubjects, 'subject_id');
        $nlStudentSubjects = NonLanguageSubject::getNLStudentSubjectWithExamTimings($nlSubjectIds, $studentEnrollment->exam_id);

        // <img src="{{ asset('website_assets/images/site-logo-white.png') }}" alt="Image" class="admit-card-header-img" />
        // <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="w-100" />

        $pdf = Pdf::loadView('pdf.download_admit_card', [
            'page_title' => $pageTitle,
            'user' => $data['studentDetails'],
            'studentEnrollment' => $studentEnrollment,
            'exam' => $exam,
            'studentSubjects' => $studentSubjects,
            'nlStudentSubjects' => $nlStudentSubjects,
            // 'assignments' => $assignments
        ]);
        return $pdf->stream();
    }
}
