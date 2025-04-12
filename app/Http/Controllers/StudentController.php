<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Helpers\AppHelper;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Models\Authentication;
use App\Models\CourseNLSubjectMapping;
use App\Models\CourseSubjectMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Models\StudentSubjectMapping;
use App\Models\StudentNLSubjectMapping;
use App\Models\StudyMaterial;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'course' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users,email_id',
            'phone_number' => 'required|unique:users,mobile_no',
            'password' => 'min:6|required_with:confirmPassword|same:confirmPassword',
            'confirmPassword' => 'min:6',
            'captcha' => 'required|captcha',
        ], [
            'captcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // Generate the unique code =======
            $user_id = AppHelper::generateUniqueCode();

            $user = new User();
            $user->user_id = $user_id;
            $user->edu_type = $request->course;
            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->email_id = $request->email;
            $user->mobile_no = $request->phone_number;
            $user->is_verified = 1; //0;
            $user->created_by = $user_id;
            $result = $user->save();

            if ($result) {

                // Generate a student reference no. for username

                // Save the authentication ========================
                $authStore = new Authentication();
                $authStore->username = $request->email;
                $authStore->user_id = $user_id;
                $authStore->password = Hash::make($request->password);
                $authStore->role_code = "STUDENT";
                $authStore->created_by = $user_id;

                if ($authStore->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Registration done successfully.'
                    ]);
                } else {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
                    ]);
                }
            } else {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }

    public function checkStudentResult(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'roll_number' => 'required',
            'captcha' => 'required|captcha',
        ], [
            'captcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            $studentResult = DB::table('old_marks_entry as ome')
                ->select('ome.*', 'osd.exam_center', 'osd.exam_dist', 'osd.exam_name', 'osd.exam_date', 'osd.exam_division')
                ->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no')
                ->where([
                    'student_roll_no' => $request->roll_number,
                    // 'exam_type' => $request->exam_name
                ])
                ->get();


            if (count($studentResult) > 0) {
                return Response::json([
                    'status' => true,
                    'redirect_to' => URL::to("/student-result/show?roll_number=" . urlencode(base64_encode($request->roll_number)))
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Roll number not found or result is not declared yet. Please contact institute for more information'
                ]);
            }
        }
    }

    public function showStudentResult(Request $request)
    {
        $rollNumber = $request->roll_number;
        // $examType = $request->input('type');

        $rollNumber = base64_decode(urldecode($rollNumber));
        // $examType = urldecode($examType);

        // dump($rollNumber);
        // dd($examType);

        $studentResult = DB::table('old_marks_entry as ome')
            ->select('ome.*', 'osd.exam_center', 'osd.exam_dist', 'osd.exam_name', 'osd.exam_date', 'osd.exam_division', 'osd.publication_date', 'osd.exam_controller')
            ->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no')
            ->where([
                'ome.student_roll_no' => $rollNumber,
                // 'ome.exam_type' => $examType
            ])
            ->get();

        // dd($studentResult);


        if (count($studentResult) < 1) {
            session()->flash('error_message', 'No result found, please try after sometime.');
            return back();
        }

        return view('website.student.student_result', [
            'page_title' => 'Student Result',
            'studentResult' => $studentResult
        ]);
    }

    /**
     * Function for student personal details form
     */
    public function studentMyProfile(Request $request)
    {
        $pageTitle = 'My Profile';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);
        $data['page_title'] = $pageTitle;

        if ($data['studentDetails']) {
            return view('student.my_profile')->with($data);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Function for student personal details form
     */
    public function studentPersonalDetails(Request $request)
    {
        $pageTitle = 'Personal Details';

        $email = auth()->user()->userDetail->email_id;
        $studentRegistered = User::leftJoin('students as sd', 'users.email_id', '=', 'sd.email')
            ->where(function ($query) use ($email) {
                $query->where('sd.email', $email);
            })
            ->count();

        $user = Student::where([
            'record_status' => 1,
            'created_by' => Auth::user()->user_id
        ])->first();

        if ($user) {
            return view('student.personal_details', [
                'page_title' => $pageTitle,
                'user' => $user
            ]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Function for student course details
     */
    public function studentCourseDetails(Request $request)
    {
        $pageTitle = 'Course Details';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        $courseDetails = Course::getStudentCourseDetailsUsingId($data['user']->course);
        $courseSubjects = StudentSubjectMapping::getStudentSubjectsUsingStudentId($data['user']->id);
        $nonLanguageSubjects = StudentNLSubjectMapping::getStudentNLSubjectsUsingStudentId($data['user']->id);

        if ($data['user']) {
            return view('student.course_details', [
                'page_title' => $pageTitle,
                'user' => $data['user'],
                'courseDetails' => $courseDetails,
                'courseSubjects' => $courseSubjects,
                'nonLanguageSubjects' => $nonLanguageSubjects
            ]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Function for student study material list
     */
    public function studentStudyMaterial(Request $request)
    {
        $pageTitle = 'Study Material';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        // $studyMaterials = StudyMaterial::getStudentCourseStudyMaterials($data['studentDetails']->course);

        // Get language subjects of courses
        $subjects = StudentSubjectMapping::where('student_id', $data['studentDetails']->id)->get();

        // Get language subjects of courses
        $nlSubjects = StudentNLSubjectMapping::where('student_id', $data['studentDetails']->id)->get();

        // Get language subjects study material
        $studyMaterialsLanSubjects = [];
        if ($subjects->count() > 0) {
            $subjectsIdsArray = array_column($subjects->toArray(), 'subject_id');
            $studyMaterialsLanSubjects = StudyMaterial::getStudentSubjectStudyMaterials($subjectsIdsArray);
        }

        // Get non language subjects study material
        $studyMaterialsNonLanSubjects = [];
        if ($nlSubjects->count() > 0) {
            $nlSubjectsIdsArray = array_column($nlSubjects->toArray(), 'subject_id');
            $studyMaterialsNonLanSubjects = StudyMaterial::getStudentNLSubjectStudyMaterials($nlSubjectsIdsArray);
        }

        if ($data['studentDetails']) {
            return view('student.study_material', [
                'page_title' => $pageTitle,
                'user' => $data['studentDetails'],
                // 'studyMaterials' => $studyMaterials,
                'studyMaterialsLanSubjects' => $studyMaterialsLanSubjects,
                'studyMaterialsNonLanSubjects' => $studyMaterialsNonLanSubjects
            ]);
        } else {
            return redirect()->back();
        }
    }
}
