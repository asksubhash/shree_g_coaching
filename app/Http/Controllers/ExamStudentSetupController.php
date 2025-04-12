<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamStudentsEnrollment;
use App\Models\Institute;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ExamStudentSetupController extends Controller
{
    public function examStudents(Request $request)
    {
        $id = $request->exam_id;
        $exam = Exam::find($id);

        if (!$exam) {
            return redirect()->back();
        }

        $institutes = Institute::getInstitutes();
        $academic_years = AcademicYear::getAcademicYears();

        return view('master_setup.exams_setup.exam_students_setup', [
            'page_title' => 'Exam Students Enrollment: ' . $exam->exam_name,
            'institutes' => $institutes,
            'academic_years' => $academic_years,
            'exam' => $exam
        ]);
    }

    public function fetchExamStudentsForEnrollment(Request $request)
    {
        $query = Student::select(
            'students.*',
            'sm.state_name',
            'ese.is_enrolled',
            'ese.admit_card',
        );
        $query->leftJoin('exam_students_enrollment as ese', function ($join) use ($request) {
            $join->on('ese.student_id', '=', 'students.id')
                ->where('ese.exam_id', '=', $request->exam_id);
        });
        $query->leftJoin('admission_sessions as as', 'students.adm_sesh', '=', 'as.id');
        $query->leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');

        $query->where('students.academic_year', $request->academic_year_id);
        $query->where('students.course', $request->course);
        $query->where('students.record_status', 1);
        $query->where('students.is_approved', 1);

        $query->where('as.session_name', $request->admission_session);

        $data = $query->orderBy('students.id', 'desc')->get();

        return DataTables::of($data)
            ->addColumn('checkbox', function ($data) {
                $checkBox = '';

                $checked = ($data->is_enrolled == 1 && $data->admit_card == 1) ? "checked" : "";
                $checkBox .= "<input type='checkbox' class='btn btn-info btn-sm student_enroll_checkbox' name='student_enroll_checkbox' value='" . $data->id . "' $checked />";

                return $checkBox;
            })
            ->addColumn('enrolled_status_desc', function ($data) {
                $status = ($data->is_enrolled == 1 && $data->admit_card == 1) ? '<span class="badge bg-success">Enrolled</span>' : '<span class="badge bg-danger">Not Enrolled</span>';
                return $status;
            })
            ->rawColumns(['checkbox', 'enrolled_status_desc'])
            ->make(true);
    }

    function storeExamStudentsForEnrollment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'enrolled_students' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $exam_id = $request->exam_id;
            $enrolledStudents = json_decode($request->enrolled_students);

            $studentIds = array_column($enrolledStudents, 'studentId');

            // Check and get the data of the student ids
            $examEnrolledStudents = ExamStudentsEnrollment::where([
                'exam_id' => $exam_id,
                'record_status' => 1
            ])->whereIn('student_id', $studentIds)->get();

            $examEnrolledStudentsIds = [];
            if ($examEnrolledStudents->count() > 0) {
                // Update those records
                $examEnrolledStudentsArray = $examEnrolledStudents->toArray();
                $examEnrolledStudentsIds = array_column($examEnrolledStudentsArray, 'student_id');
            }

            if (count($enrolledStudents) > 0) {
                $examsInsertData = [];
                foreach ($enrolledStudents as $key => $student) {
                    if (count($examEnrolledStudentsIds) > 0 && in_array($student->studentId, $examEnrolledStudentsIds)) {
                        // Update
                        ExamStudentsEnrollment::where([
                            'exam_id' => $exam_id,
                            'student_id' => $student->studentId
                        ])->update([
                            'is_enrolled' => $student->isChecked,
                            'admit_card' => $student->isChecked,
                            'record_status' => 1,
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Push into insert array
                        array_push($examsInsertData, [
                            'exam_id' => $exam_id,
                            'student_id' => $student->studentId,
                            'is_enrolled' => $student->isChecked,
                            'admit_card' => $student->isChecked,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);
                    }
                }

                if (count($examsInsertData) > 0) {
                    $result = ExamStudentsEnrollment::insert($examsInsertData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Students enrolled successfully.'
                        ]);
                    } else {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Server is not responding. Please try again.'
                        ]);
                    }
                } else {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Students enrolled successfully.'
                    ]);
                }
            } else {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Please select student for enrollment.'
                ]);
            }
        }
    }
}
