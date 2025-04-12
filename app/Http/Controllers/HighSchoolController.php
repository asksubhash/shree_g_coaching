<?php

namespace App\Http\Controllers;

use Svg\Tag\Rect;
use App\Models\User;
use App\Models\Course;
use App\Models\GenCode;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Institute;
use App\Models\StateMaster;
use App\Models\AcademicYear;
use App\Models\AdmissionSession;
use Illuminate\Http\Request;
use App\Models\StudentDetail;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CourseSubjectMapping;
use App\Models\StudentSubjectMapping;
use App\Models\UserDepartmentMapping;
use App\Models\CourseNLSubjectMapping;
use Illuminate\Support\Facades\Config;
use App\Models\StudentNLSubjectMapping;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HighSchoolController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $page_title = '10th Class Students';
        return view('high_school.index', compact('page_title'));
    }
    public function fetchAll(Request $request)
    {
        $query = Student::leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');

        $query->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id');
        $query->leftJoin('courses as cs', 'students.course', '=', 'cs.id');
        $query->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id');
        $query->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id');

        $query->select(
            'students.*',
            'sm.state_name',
            'ay.academic_year as st_academic_year',
            'ads.session_name as st_admission_session',
            'ins.name as institute_name',
            'cs.course_name',
        );

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', Auth::user()->user_id)->get()->toArray();
            $instituteIdsMapped = array_column($udmData, 'department_id');
            $query->whereIn('students.institute_id', $instituteIdsMapped);
        }

        if ($request->type == 'PAYMENT_DONE') {
            $query->where('students.payment_received', 1);
            $query->where('students.is_approved', 0);
        }

        if ($request->type == 'TOTAL_ENROLLED') {
            $query->where('students.payment_received', 1);
            $query->where('students.is_approved', 1);
        }

        $query->where('students.record_status', 1);

        $allUsers = $query->orderBy('students.id', 'desc')->get();

        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) {
                // <a href='" . url('high-school/edit/' . base64_encode($allUsers->id)) . "' class='btn btn-warning btn-sm editUserBtn' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></a> 

                $button = '';

                $button .= "<a href='" . url('high-school/show/' . base64_encode($allUsers->id)) . "'class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a>";

                if ($allUsers->payment_received == 0) {
                    $button .= " <a href='" . url('high-school/edit/' . base64_encode($allUsers->id)) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></a>";

                    $button .= " <button class='btn btn-success btn-sm btnAddPayment' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Add Payment'><i class='bx bx-money'></i></button>";

                    $button .= " <button class='btn btn-danger btn-sm deleteUserBtn' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                }

                $button .= " <a href='" . url('/pdf/high-school/show/' . $allUsers->id) . "' class='btn btn-success btn-sm' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='pdf' target='_BLANK'><i class='bx bx-file'></i></a>";

                return $button;
            })
            ->editColumn('status_desc', function ($allUsers) {
                $status = ($allUsers->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    public function add(Request $request)
    {
        $data['institutes'] = [];

        // Check if the role code is in INS_DEO and IND_HEAD
        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the mapped institutes
            $data['institutes'] = UserDepartmentMapping::getUserMappedInstitutes(Auth::user()->user_id);
        }

        $data['page_title'] = 'Register 10th Student';

        if (Auth::user()->role_code == 'STUDENT') {
            $data['courses'] = Course::getDefaultInstCourses('TEN');
        } else if (in_array(Auth::user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            $instituteIdsArray = array_column($data['institutes']->toArray(), 'institute_id');
            $data['courses'] = Course::getInstitutesCoursesUsingInsIds($instituteIdsArray, 'TEN');
        } else {
            $data['courses'] = Course::getAllCourses('TEN');
        }

        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');

        $data['academic_years'] = AcademicYear::getActiveAcademicYear();

        return view('high_school.add')->with($data);
    }
    public function store(Request $request)
    {
        $validationRules = [
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'course' => 'required|integer',
            'language_subject.*' => 'required|string',
            'non_language_subject.*' => 'string',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'religion' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'medium_off_inst' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'photo' => 'required|mimes:png,jpeg,jpg',
            'aadhar' => 'required|mimes:jpg,jpeg,pdf',
        ];

        if (auth()->user()->role_code != 'STUDENT') {
            $validationRules['institute_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            // ===============================
            // Get the student max id
            $maxId = Student::max('id');
            $applicationNo = 1000 + $maxId + 1;

            $user = new Student();
            $user->register_through = 'CENTRE';
            $user->registered_by = Auth::user()->user_id;
            $user->application_no = date('Ymd') . $applicationNo;
            $user->edu_type = 'TEN';
            $user->academic_year = $request->academic_year;
            $user->adm_sesh = $request->admission_session;
            $user->course = $request->course;
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->mother_name = $request->mother_name;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->religion = $request->religion;
            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state = $request->state;
            $user->email = $request->email;
            $user->contact_number = $request->contact;
            $user->category = $request->category;
            $user->aadhar_number = $request->aadhar_number;
            $user->medium_off_inst = trim($request->medium_off_inst);
            $user->created_by = Auth::user()->user_id;
            $user->created_at = now();

            $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('photo')) {
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
            if ($request->file('aadhar')) {
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            if (auth()->user()->role_code == 'STUDENT') {
                $user->is_approved = 0;
                $user->belongs_to_center = 1;
                $user->institute_id = env('CENTER_INSTITUTE_ID');
            } else {
                $user->institute_id = $request->institute_id;
                $user->is_approved = 0;
                $user->belongs_to_center = 0;
            }


            if ($user->save()) {

                // ===============================================
                // Save student language subjects
                $courseSubjects = $request->language_subject;
                if (count($courseSubjects) > 0) {
                    $courseSubjectArray = [];
                    foreach ($courseSubjects as $key => $subject) {
                        array_push($courseSubjectArray, [
                            'student_id' => $user->id,
                            'subject_id' => $subject,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);
                    }

                    if (count($courseSubjectArray) > 0) {
                        $ssmResult = StudentSubjectMapping::insert($courseSubjectArray);

                        if (!$ssmResult) {
                            DB::rollBack();
                            return Response::json([
                                'status' => false,
                                'message' => 'Error while saving student course subjects. Please try again.'
                            ]);
                        }
                    }
                }

                // ===============================================
                // Save student non language subjects
                $courseNLSubjects = $request->non_language_subject;
                if (count($courseNLSubjects) > 0) {
                    $courseNLSubjectArray = [];
                    foreach ($courseNLSubjects as $key => $subject) {
                        array_push($courseNLSubjectArray, [
                            'student_id' => $user->id,
                            'subject_id' => $subject,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);
                    }

                    if (count($courseNLSubjectArray) > 0) {
                        $ssmResult = StudentNLSubjectMapping::insert($courseNLSubjectArray);

                        if (!$ssmResult) {
                            DB::rollBack();
                            return Response::json([
                                'status' => false,
                                'message' => 'Error while saving student non language subjects. Please try again.'
                            ]);
                        }
                    }
                }

                // ===============================================
                // Check if student self register 
                // ===============================================
                if (auth()->user()->role_code == 'STUDENT') {
                    $userMaster = User::where('user_id', auth()->user()->user_id)->first();
                    $userMaster->is_applied_for_adm = 1;
                    $userMaster->updated_at = now();
                    $userMaster->save();
                }

                DB::commit();

                if (Auth::user()->role_code == 'STUDENT') {
                    return Response::json([
                        'status' => true,
                        'message' => 'Application submitted successfully. Please wait for approval.',
                        'redirect_url' => url('student/dashboard')
                    ]);
                } else {
                    return Response::json([
                        'status' => true,
                        'message' => 'Application registration processing started successfully. Proceed with the payment.',
                        'redirect_url' => url('high-school')
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
    public function edit(Request $request)
    {
        $id = base64_decode($request->id);

        // Get student details
        $data['user'] = Student::getStudentDetailsUsingId($id);
        // dd($data['user']);

        if (!$data['user']) {
            return redirect()->back();
        }

        $data['institutes'] = [];

        // Check if the role code is in INS_DEO and IND_HEAD
        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the mapped institutes
            $data['institutes'] = UserDepartmentMapping::getUserMappedInstitutes(Auth::user()->user_id);
        }

        $data['page_title'] = 'Register 10th Student';

        if (Auth::user()->role_code == 'STUDENT') {
            $data['courses'] = Course::getDefaultInstCourses('TEN');
        } else if (in_array(Auth::user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            $instituteIdsArray = array_column($data['institutes']->toArray(), 'institute_id');
            $data['courses'] = Course::getInstitutesCoursesUsingInsIds($instituteIdsArray, 'TEN');
        } else {
            $data['courses'] = Course::getAllCourses('TEN');
        }

        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');

        $data['academic_years'] = AcademicYear::getActiveAcademicYear();

        $data['courseAllSubjects'] = CourseSubjectMapping::leftJoin('subjects as sub', 'sub.id', '=', 'course_subject_mappings.subject_id')->where('course_subject_mappings.course_id', $data['user']->course)->get();
        // dd($data['courseAllSubjects']);

        $data['courseAllNLSubjects'] = CourseNLSubjectMapping::leftJoin('non_language_subjects as sub', 'sub.id', '=', 'course_nl_subject_mappings.subject_id')->where('course_nl_subject_mappings.course_id', $data['user']->course)->get();

        // Get Subjects and courses
        $data['courseSubjects'] = StudentSubjectMapping::getStudentSubjectsUsingStudentId($data['user']->id);

        $data['nonLanguageSubjects'] = StudentNLSubjectMapping::getStudentNLSubjectsUsingStudentId($data['user']->id);


        return view('high_school.add')->with($data);
    }
    public function update(Request $request)
    {
        $validationRules = [
            'hiddenId' => 'required',
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'course' => 'required|integer',
            'language_subject.*' => 'required|string',
            'non_language_subject.*' => 'string',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'religion' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'medium_off_inst' => 'required|string',
            'email' => 'required|email',
            'photo' => 'mimes:png,jpeg,jpg',
            'aadhar' => 'mimes:jpg,jpeg,pdf',
        ];

        if (auth()->user()->role_code != 'STUDENT') {
            $validationRules['institute_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // ===============================
            $user = Student::findOrFail(base64_decode($request->hiddenId));
            $user->registered_by = Auth::user()->user_id;
            $user->edu_type = 'TEN';
            $user->academic_year = $request->academic_year;
            $user->adm_sesh = $request->admission_session;
            $user->course = $request->course;
            // $user->lang_subj = $request->language_subject;
            // $user->non_lang_subj = $request->non_language_subject;
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->mother_name = $request->mother_name;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->religion = $request->religion;
            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state = $request->state;
            $user->email = $request->email;
            $user->contact_number = $request->contact;
            $user->category = $request->category;
            $user->aadhar_number = $request->aadhar_number;
            $user->medium_off_inst = trim($request->medium_off_inst);
            $user->updated_by = Auth::user()->user_id;
            $user->updated_at = now();

            $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('photo')) {
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
            if ($request->file('aadhar')) {
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            if (auth()->user()->role_code == 'STUDENT') {
                $user->is_approved = 0;
                $user->belongs_to_center = 1;
                $user->institute_id = env('CENTER_INSTITUTE_ID');
            } else {
                $user->institute_id = $request->institute_id;
                $user->is_approved = 0;
                $user->belongs_to_center = 0;
            }


            if ($user->save()) {

                // ===============================================
                // Save student language subjects
                $courseSubjects = $request->language_subject;
                if (count($courseSubjects) > 0) {

                    $courseSubjectArray = [];
                    foreach ($courseSubjects as $key => $subject) {

                        array_push($courseSubjectArray, [
                            'student_id' => $user->id,
                            'subject_id' => $subject,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);

                        // Delete the previous entries
                        $delResult = StudentSubjectMapping::where([
                            'student_id' => $user->id,
                            'subject_id' => $subject
                        ])->update([
                            'record_status' => 0
                        ]);

                        // if (!$delResult) {
                        //     DB::rollBack();
                        //     return Response::json([
                        //         'status' => false,
                        //         'message' => 'Error while removing previous student course subjects. Please try again.'
                        //     ]);
                        // }
                    }

                    if (count($courseSubjectArray) > 0) {
                        $ssmResult = StudentSubjectMapping::insert($courseSubjectArray);

                        if (!$ssmResult) {
                            DB::rollBack();
                            return Response::json([
                                'status' => false,
                                'message' => 'Error while saving student course subjects. Please try again.'
                            ]);
                        }
                    }
                }

                // ===============================================
                // Save student non language subjects
                $courseNLSubjects = $request->non_language_subject;
                if (count($courseNLSubjects) > 0) {
                    $courseNLSubjectArray = [];
                    foreach ($courseNLSubjects as $key => $subject) {
                        array_push($courseNLSubjectArray, [
                            'student_id' => $user->id,
                            'subject_id' => $subject,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);

                        // Delete the previous entries
                        $delResult = StudentNLSubjectMapping::where([
                            'student_id' => $user->id,
                            'subject_id' => $subject
                        ])->update([
                            'record_status' => 0
                        ]);

                        // if (!$delResult) {
                        //     DB::rollBack();
                        //     return Response::json([
                        //         'status' => false,
                        //         'message' => 'Error while removing previous student non language subjects. Please try again.'
                        //     ]);
                        // }
                    }

                    if (count($courseNLSubjectArray) > 0) {
                        $ssmResult = StudentNLSubjectMapping::insert($courseNLSubjectArray);

                        if (!$ssmResult) {
                            DB::rollBack();
                            return Response::json([
                                'status' => false,
                                'message' => 'Error while saving student non language subjects. Please try again.'
                            ]);
                        }
                    }
                }

                // ===============================================
                // Check if student self register 
                // ===============================================
                // if (auth()->user()->role_code == 'STUDENT') {
                //     $userMaster = User::where('user_id', auth()->user()->user_id)->first();
                //     $userMaster->is_applied_for_adm = 1;
                //     $userMaster->updated_at = now();
                //     $userMaster->save();
                // }

                DB::commit();
                if (Auth::user()->role_code == 'STUDENT') {
                    return Response::json([
                        'status' => true,
                        'message' => 'Application submitted successfully. Please wait for approval.',
                        'redirect_url' => url('student/dashboard')
                    ]);
                } else {
                    return Response::json([
                        'status' => true,
                        'message' => 'Application registration processing started successfully. Proceed with the payment.',
                        'redirect_url' => url('high-school')
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
    public function show(Request $request)
    {
        $id = base64_decode($request->id);

        $data['user'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('courses as cs', 'students.course', '=', 'cs.id')
            ->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id')
            ->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id')
            // ->leftJoin('gen_codes as gd1', 'students.adm_sesh', '=', 'gd1.gen_code')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'ay.academic_year as st_academic_year',
                'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                'cs.course_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->findOrFail($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;

        $data['courseSubjects'] = StudentSubjectMapping::getStudentSubjectsUsingStudentId($data['user']->id);

        $data['nonLanguageSubjects'] = StudentNLSubjectMapping::getStudentNLSubjectsUsingStudentId($data['user']->id);

        // dd($nonLanguageSubjects);
        // ->leftJoin('student_subject_mappings as ssm', 'students.id', '=', 'ssm.gen_code')
        //     ->leftJoin('student_nl_subject_mappings as snsm', 'students.id', '=', 'gd4.gen_code')

        //     ->leftJoin('subjects as sub', 'sub.id', '=', 'students.gen_code')
        //     ->leftJoin('non_language_subjects as nl_sub', 'nl_sub.id', '=', 'students.gen_code')

        return view('high_school.view')->with($data);
    }
    public function delete(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Student::find($id);
        if ($data->count() > 0) {
            $data->record_status = ($data->record_status == 1) ? 0 : 1;
            $data->updated_at = now();
            $data->updated_by = Auth::user()->user_id;
            if ($data->save()) {
                $output = [
                    'status' => true,
                    'message' => 'Record deleted successfully.'
                ];
            } else {
                $output = [
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ];
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'User not found. Please try again or contact support.'
            ];
        }
        return Response::json($output);
    }

    /**
     * Function to show data in admin for high school
     */

    public function adminAllStudentApplications()
    {
        $academic_years = AcademicYear::getAcademicYears();
        $institutes = Institute::getInstitutes();
        $admSessions = AdmissionSession::where('record_status', 1)->get();

        $page_title = '10th Class Students';
        return view('high_school.all_students_applications', compact('page_title', 'academic_years', 'institutes', 'admSessions'));
    }
    public function fetchAllAdminStudentApplications(Request $request)
    {
        $query = Student::leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');
        $query->leftJoin('payments as pay', "pay.student_application_no", '=', 'students.application_no');
        $query->select(
            'students.*',
            'sm.state_name',
            'pay.id as payment_id'
        );
        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', Auth::user()->user_id)->get()->toArray();
            $instituteIdsMapped = array_column($udmData, 'department_id');
            $query->whereIn('students.institute_id', $instituteIdsMapped);
        }

        if ($request->type == 'NEW') {
            $query->where('students.payment_received', 1);
            $query->where('students.is_approved', 0);
        }

        if ($request->type == 'PAYMENT_DONE') {
            $query->where('students.payment_received', 1);
            $query->where('students.is_approved', 0);
        }

        if ($request->type == 'TOTAL_ENROLLED') {
            $query->where('students.payment_received', 1);
            $query->where('students.is_approved', 1);
        }

        $query->where('students.record_status', 1);

        $allUsers = $query->orderBy('students.id', 'desc')->get();


        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) use ($request) {

                $button = '';

                $button .= "<a href='" . url('high-school/show/' . base64_encode($allUsers->id)) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a> <a href='" . url('payment/student/fees/show?payment_id=' . base64_encode($allUsers->payment_id)) . "'class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-money'></i></a>";

                if ($request->type == 'NEW' && Auth::user()->role_code == 'ADMIN') {
                    $button .= " <button class='btn btn-success btn-sm approveStudent' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Approve Student'><i class='bx bx-check'></i></button> ";
                }

                if ($allUsers->payment_received == 1 && $allUsers->is_approved == 1) {
                    $button .= " <a href='" . url('/pdf/high-school/show/' . $allUsers->id) . "' class='btn btn-success btn-sm' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='pdf' target='_BLANK'><i class='bx bx-file'></i></a>";
                }

                return $button;
            })
            ->editColumn('status_desc', function ($allUsers) {
                $status = ($allUsers->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    /**
     * Function to approved student
     */
    public function approveStudent(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Student::find($id);
        if ($data->count() > 0) {
            $data->is_approved = ($data->is_approved == 1) ? 0 : 1;
            $data->updated_at = now();
            $data->updated_by = Auth::user()->user_id;
            if ($data->save()) {
                $output = [
                    'status' => true,
                    'message' => 'Student approved successfully.'
                ];
            } else {
                $output = [
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ];
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'User not found. Please try again or contact support.'
            ];
        }
        return Response::json($output);
    }
}
