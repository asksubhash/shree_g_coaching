<?php

namespace App\Http\Controllers\master_setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Institute;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\StudentExamDetails;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExamAttendedController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->exam_id;
        $exam = Exam::find($id);

        if (!$exam) {
            return redirect()->back();
        }

        $institutes = Institute::getInstitutes();

        return view('master_setup.exam_attended.index', [
            'page_title' => 'Exam Attended: ' . $exam->exam_name,
            'institutes' => $institutes,
            'exam' => $exam
        ]);
    }

    function fetchForDatatable(Request $request)
    {

        $query = StudentExamDetails::select(
            'student_exams_details.*',
            'student.name',
            'student.roll_number',
            DB::raw("COUNT(student_exams_details.id) as exams_completed")
        );

        $query->leftJoin('students as student', 'student.id', '=', 'student_exams_details.student_id');
        $query->orderBy('student_exams_details.id', 'ASC');
        $query->groupBy(['student_id', 'exam_id']);

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "";

                $button .= "<a href='" . url('exams-setup/students/exams-attended/list?exam_id=' . $data->exam_id) . "' class='btn btn-custom btn-sm' id='" . $data->exam_id . "' data-toggle='tooltip' data-placement='left' title='Exam Attended by Students'><i class='bx bx-paper-plane'></i></a> ";

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function examAttendedList(Request $request)
    {
        $id = $request->exam_id;
        $exam = Exam::find($id);

        if (!$exam) {
            return redirect()->back();
        }

        $institutes = Institute::getInstitutes();

        return view('master_setup.exam_attended.exam_attended_list', [
            'page_title' => 'Exam Attended: ' . $exam->exam_name,
            'institutes' => $institutes,
            'exam' => $exam
        ]);
    }

    function fetchForExamAttendedListDatatable(Request $request)
    {

        $query = StudentExamDetails::select(
            'student_exams_details.*',
            'student.name',
            'student.roll_number',
            'subject.code as subject_code',
            'subject.name as subject_name',
            'nl_subject.code as nl_subject_code',
            'nl_subject.name as nl_subject_name',
        );

        $query->leftJoin('students as student', 'student.id', '=', 'student_exams_details.student_id');
        $query->leftJoin('subjects as subject', function ($join) {
            $join->on('subject.id', '=', 'student_exams_details.subject_id')
                ->where('student_exams_details.subject_type', '=', 'LANGUAGE'); // Example AND condition
        });
        $query->leftJoin('non_language_subjects as nl_subject', function ($join) {
            $join->on('nl_subject.id', '=', 'student_exams_details.subject_id')
                ->where('student_exams_details.subject_type', '=', 'NON_LANGUAGE'); // Example AND condition
        });

        $query->orderBy('student_exams_details.id', 'ASC');

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "";

                $button .= "<a href='" . url('exams-setup/students/exams-attended/view-student-result-details?id=' . $data->id) . "' class='btn btn-custom btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='View Student Result'><i class='bx bx-paper-plane'></i></a> ";

                return $button;
            })
            ->addColumn('exam_status_desc', function ($data) {
                $status = '<span class="badge bg-info">Pending</span>';
                if ($data->exam_status == 'SUBMITTED') {
                    $status = '<span class="badge bg-success">Submitted</span>';
                }
                return $status;
            })
            ->rawColumns(['exam_status_desc', 'action'])
            ->make(true);
    }

    public function viewStudentResultDetails(Request $request)
    {
        $id = $request->id;

        $studentExamDetails = StudentExamDetails::where([
            'id' => $id
        ])->first();

        $examId = $studentExamDetails->exam_id;
        $subjectId = $studentExamDetails->subject_id;
        $subjectType = $studentExamDetails->subject_type;

        if ($examId && $subjectId && $subjectType) {

            // Get the exam details
            $exam = Exam::where('id', $examId)->first();

            if (!$exam) {
                return redirect()->back();
            }

            // Get the student details
            $studentDetails = Student::getStudentAllDetailsUsingId($studentExamDetails->student_id);

            if (!$studentDetails) {
                return redirect()->back();
            }

            $examMcqQuestions = Question::getExamMcqQuestions($examId, $subjectId, $subjectType);
            $examTextQuestions = Question::getExamTextQuestions($examId, $subjectId, $subjectType);

            $questionsCount = 0;

            // Get student MCQ Answers
            $mcqAnswers = StudentAnswer::getStudentMCQAnswerUsingSEDID($studentExamDetails->id)->toArray();
            $questionsCount += count($mcqAnswers);

            // Get student TEXT Answers
            $textAnswers = StudentAnswer::getStudentTextAnswerUsingSEDID($studentExamDetails->id)->toArray();
            $questionsCount += count($textAnswers);

            $page_title = 'View Student Answers';

            return view('master_setup.exam_attended.student_answers', compact('examMcqQuestions', 'examTextQuestions', 'exam', 'page_title', 'studentExamDetails', 'mcqAnswers', 'textAnswers', 'questionsCount'));
        } else {
            return redirect()->back();
        }
    }
}
