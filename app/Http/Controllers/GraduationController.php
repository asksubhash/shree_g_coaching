<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\User;
use App\Models\Course;
use App\Models\GenCode;
use App\Models\Student;
use App\Models\Graduation;
use App\Models\StateMaster;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\StudentSubjectMapping;
use App\Models\UserDepartmentMapping;
use Illuminate\Support\Facades\Config;
use App\Models\StudentNLSubjectMapping;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class GraduationController extends Controller
{
    use FileUploadTrait;
    public function index()
    {
        $page_title = 'Graduation Students';
        return view('graduation.index', compact('page_title'));
    }
    public function fetchAll(Request $request)
    {
        $query = Student::leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');
        $query->select(
            'students.*',
            'sm.state_name',
        );

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', FacadesAuth::user()->user_id)->get()->toArray();
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
        $query->where('students.edu_type', "GRADUATION");

        $allUsers = $query->orderBy('students.id', 'desc')->get();

        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) {
                $button = '';

                $button .= "<a href='" . url('graduation/show/' . base64_encode($allUsers->id)) . "'class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a>";

                if ($allUsers->payment_received == 0) {
                    $button .= " <a href='" . url('graduation/edit/' . base64_encode($allUsers->id)) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></a>";

                    $button .= " <button class='btn btn-success btn-sm btnAddPayment' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Add Payment'><i class='bx bx-money'></i></button>";

                    $button .= " <button class='btn btn-danger btn-sm deleteUserBtn' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                }

                $button .= " <a href='" . url('/pdf/graduation/show/' . $allUsers->id) . "' class='btn btn-success btn-sm' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='pdf' target='_BLANK'><i class='bx bx-file'></i></a>";

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

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the mapped institutes
            $data['institutes'] = UserDepartmentMapping::getUserMappedInstitutes(Auth::user()->user_id);
        }

        $data['page_title'] = 'Register Graduation Student';

        if (Auth::user()->role_code == 'STUDENT') {
            $data['courses'] = Course::getDefaultInstCourses('GRADUATION');
        } else if (in_array(Auth::user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            $instituteIdsArray = array_column($data['institutes']->toArray(), 'institute_id');
            $data['courses'] = Course::getInstitutesCoursesUsingInsIds($instituteIdsArray, 'GRADUATION');
        } else {
            $data['courses'] = Course::getAllCourses('GRADUATION');
        }

        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);

        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');
        $data['year'] = GenCode::getGenCodeUsingGroup('TEST');
        $data['board'] = GenCode::getGenCodeUsingGroup('BOARD_UNIVERSITY');
        $data['subject'] = GenCode::getGenCodeUsingGroup('SUBJECT');

        $data['academic_years'] = AcademicYear::where([
            'record_status' => 1
        ])->get();

        return view('graduation.add')->with($data);
    }
    public function store(Request $request)
    {
        $validationRules = [
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'course' => 'required|integer',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'ten_year' => 'required|string',
            'ten_subj' => 'required|string',
            'ten_board_uni' => 'required|string',
            'ten_board_name' => 'required|string',
            'twelve_year' => 'required|string',
            'twelve_subj' => 'required|string',
            'twelve_board_uni' => 'required|string',
            'twelve_board_name' => 'required|string',

            'other_year' => 'nullable|string',
            'other_subj' => 'nullable|string',
            'other_board_uni' => 'nullable|string',
            'other_board_name' => 'nullable|string',

            'email' => 'required|email',
            'photo' => 'required|mimes:png,jpeg,jpg',
            'aadhar' => 'mimes:jpg,jpeg,pdf',
            'ten_marksheet' => 'required|mimes:jpg,jpeg,pdf',
            'twelve_marksheet' => 'required|mimes:jpg,jpeg,pdf',
        ];

        if ($request->other_year != '') {
            $validationRules['other_marksheet'] = 'required|mimes:jpg,jpeg,pdf';
        }

        if (auth()->user()->role_code != 'STUDENT') {
            $validationRules['institute_id'] = 'required';
        }

        $validator = FacadesValidator::make($request->all(), $validationRules);

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
            $user->registered_by = Auth::user()->user_id;
            $user->application_no = date('Ymd') . $applicationNo;
            $user->edu_type = 'GRADUATION';
            $user->adm_sesh = $request->admission_session;
            $user->academic_year = $request->academic_year;
            $user->course = $request->course;
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->mother_name = $request->mother_name;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state = $request->state;
            $user->email = $request->email;
            $user->contact_number = $request->contact;
            $user->category = $request->category;
            $user->aadhar_number = $request->aadhar_number;
            $user->ac_ten_year = $request->ten_year;
            $user->ac_ten_subj = $request->ten_subj;
            $user->ac_ten_board = $request->ten_board_uni;
            $user->ac_ten_board_name = $request->ten_board_name;
            $user->ac_twelve_year = $request->twelve_year;
            $user->ac_twelve_subj = $request->twelve_subj;
            $user->ac_twelve_board = $request->twelve_board_uni;
            $user->ac_twelve_board_name = $request->twelve_board_name;
            $user->ac_other_year = $request->other_year;
            $user->ac_other_subj = $request->other_subj;
            $user->ac_other_board = $request->other_board_uni;
            $user->ac_other_board_name = $request->other_board_name;
            $user->created_by = FacadesAuth::user()->user_id;
            $user->created_at = now();

            // CUSTOM TRAIT: Using the trait function to upload the file
            $filePath = Config::get('constants.files_storage_path')['STUDENT_METRIC_MARKSHEET_UPLOAD_PATH'];

            if ($request->file('ten_marksheet')) {
                $ten_marksheet = $this->uploadSingleFile($request->ten_marksheet, $filePath, true);
                $user->ac_ten_sheet =  $ten_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_TWELVE_MARKSHEET_UPLOAD_PATH'];
            if ($request->file('twelve_marksheet')) {
                $twelve_marksheet = $this->uploadSingleFile($request->twelve_marksheet, $filePath, true);
                $user->ac_twelve_sheet =  $twelve_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_OTHER_MARKSHEET_UPLOAD_PATH'];
            if ($request->file('other_marksheet')) {
                $other_marksheet = $this->uploadSingleFile($request->other_marksheet, $filePath, true);
                $user->ac_other_sheet =  $other_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
            if ($request->file('photo')) {
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
            if ($request->file('aadhar')) {
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            // ====================================
            if (auth()->user()->role_code == 'STUDENT') {
                $user->is_approved = 0;
                $user->belongs_to_center = 1;
                $user->institute_id = env('CENTER_INSTITUTE_ID');
            } else {
                $user->institute_id = $request->institute_id;
                $user->is_approved = 0;
                $user->belongs_to_center = 0;
            }

            // ====================================
            if ($user->save()) {
                // check if student self register 
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
                        'redirect_url' => url('graduation')
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

        if (!$data['user']) {
            return redirect()->back();
        }

        $data['institutes'] = [];

        // Check if the role code is in INS_DEO and IND_HEAD
        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the mapped institutes
            $data['institutes'] = UserDepartmentMapping::getUserMappedInstitutes(Auth::user()->user_id);
        }

        $data['page_title'] = 'Register Graduation Student';

        if (Auth::user()->role_code == 'STUDENT') {
            $data['courses'] = Course::getDefaultInstCourses('GRADUATION');
        } else if (in_array(Auth::user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            $instituteIdsArray = array_column($data['institutes']->toArray(), 'institute_id');
            $data['courses'] = Course::getInstitutesCoursesUsingInsIds($instituteIdsArray, 'GRADUATION');
        } else {
            $data['courses'] = Course::getAllCourses('GRADUATION');
        }

        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');
        $data['board'] = GenCode::getGenCodeUsingGroup('BOARD_UNIVERSITY');
        $data['academic_years'] = AcademicYear::getActiveAcademicYear();
        $data['subject'] = GenCode::getGenCodeUsingGroup('SUBJECT');

        return view('graduation.add')->with($data);

        // $id = base64_decode($request->id);
        // $data['user'] = Student::where('edu_type', "GRADUATION")->findOrFail($id);
        // $data['courses'] = Course::where('record_status', 1)->get(['id', 'course_code', 'course_name']);
        // $data['states'] = StateMaster::where('record_status', 1)->get(['id', 'state_name']);
        // $data['admission_session'] = GenCode::getGenCodeUsingGroup('ADMISSION_SESSION');
        // $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        // $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        // $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');
        // $data['year'] = GenCode::getGenCodeUsingGroup('TEST');
        // $data['board'] = GenCode::getGenCodeUsingGroup('BOARD_UNIVERSITY');
        // $data['subject'] = GenCode::getGenCodeUsingGroup('SUBJECT');
        // $data['page_title'] = 'Edit Graduation Student Detail';
        // return view('graduation.add')->with($data);
    }
    public function update(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'course' => 'required|integer',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'ten_year' => 'required|string',
            'ten_subj' => 'required|string',
            'ten_board_uni' => 'required|string',
            'ten_board_name' => 'required|string',
            'twelve_year' => 'required|string',
            'twelve_subj' => 'required|string',
            'twelve_board_uni' => 'required|string',
            'twelve_board_name' => 'required|string',

            'other_year' => 'nullable|string',
            'other_subj' => 'nullable|string',
            'other_board_uni' => 'nullable|string',
            'other_board_name' => 'nullable|string',

            'email' => 'required|email',
            'photo' => 'mimes:png,jpeg,jpg',
            'aadhar' => 'mimes:jpg,jpeg,pdf',
            'ten_marksheet' => 'mimes:jpg,jpeg,pdf',
            'twelve_marksheet' => 'mimes:jpg,jpeg,pdf',
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $user = Student::findOrFail(base64_decode($request->hiddenId));
            $user->registered_by = Auth::user()->user_id;
            $user->edu_type = 'GRADUATION';
            $user->adm_sesh = $request->admission_session;
            $user->academic_year = $request->academic_year;
            $user->course = $request->course;
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->mother_name = $request->mother_name;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state = $request->state;
            $user->email = $request->email;
            $user->contact_number = $request->contact;
            $user->category = $request->category;
            $user->aadhar_number = $request->aadhar_number;
            $user->ac_ten_year = $request->ten_year;
            $user->ac_ten_subj = $request->ten_subj;
            $user->ac_ten_board = $request->ten_board_uni;
            $user->ac_ten_board_name = $request->ten_board_name;
            $user->ac_twelve_year = $request->twelve_year;
            $user->ac_twelve_subj = $request->twelve_subj;
            $user->ac_twelve_board = $request->twelve_board_uni;
            $user->ac_twelve_board_name = $request->twelve_board_name;
            $user->ac_other_year = $request->other_year;
            $user->ac_other_subj = $request->other_subj;
            $user->ac_other_board = $request->other_board_uni;
            $user->ac_other_board_name = $request->other_board_name;
            $user->updated_by = FacadesAuth::user()->user_id;
            $user->updated_at = now();

            // CUSTOM TRAIT: Using the trait function to upload the file
            $filePath = Config::get('constants.files_storage_path')['STUDENT_METRIC_MARKSHEET_UPLOAD_PATH'];

            if ($request->file('ten_marksheet')) {
                $ten_marksheet = $this->uploadSingleFile($request->ten_marksheet, $filePath, true);
                $user->ac_ten_sheet =  $ten_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_TWELVE_MARKSHEET_UPLOAD_PATH'];
            if ($request->file('twelve_marksheet')) {
                $twelve_marksheet = $this->uploadSingleFile($request->twelve_marksheet, $filePath, true);
                $user->ac_twelve_sheet =  $twelve_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_OTHER_MARKSHEET_UPLOAD_PATH'];
            if ($request->file('other_marksheet')) {
                $other_marksheet = $this->uploadSingleFile($request->other_marksheet, $filePath, true);
                $user->ac_other_sheet =  $other_marksheet['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
            if ($request->file('photo')) {
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
            if ($request->file('aadhar')) {
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            // ====================================
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
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student graduation form updated successfully.'
                ]);
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
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->leftJoin('gen_codes as gd5', 'students.ac_ten_year', '=', 'gd5.gen_code')
            ->leftJoin('gen_codes as gd6', 'students.ac_ten_subj', '=', 'gd6.gen_code')
            ->leftJoin('gen_codes as gd7', 'students.ac_ten_board_name', '=', 'gd7.gen_code')
            ->leftJoin('gen_codes as gd8', 'students.ac_twelve_year', '=', 'gd8.gen_code')
            ->leftJoin('gen_codes as gd9', 'students.ac_twelve_subj', '=', 'gd9.gen_code')
            ->leftJoin('gen_codes as gd10', 'students.ac_twelve_board_name', '=', 'gd10.gen_code')
            ->leftJoin('gen_codes as gd11', 'students.ac_other_year', '=', 'gd11.gen_code')
            ->leftJoin('gen_codes as gd12', 'students.ac_other_subj', '=', 'gd12.gen_code')
            ->leftJoin('gen_codes as gd13', 'students.ac_other_board_name', '=', 'gd13.gen_code')
            ->select(
                'students.*',
                'ay.academic_year as st_academic_year',
                'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                'cs.course_name',
                'gd2.description as gender',
                'st.state_name',
                'gd4.description as category',
                'gd6.description as ac_ten_subj',

                'gd9.description as ac_twelve_subj',

                'gd12.description as ac_other_subj',
            )->where('students.edu_type', "GRADUATION")->findOrFail($id);
        $data['page_title'] = "Student Detail: " . $data['user']->name;
        return view('graduation.view')->with($data);
    }
    public function delete(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Student::where('edu_type', "GRADUATION")->find($id);
        if ($data->count() > 0) {
            $data->record_status = ($data->record_status == 1) ? 0 : 1;
            $data->updated_at = now();
            $data->updated_by = FacadesAuth::user()->user_id;
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
        $page_title = 'Graduation Class Students';
        return view('graduation.all_students_applications', compact('page_title'));
    }

    public function fetchAllAdminStudentApplications(Request $request)
    {
        $query = Student::leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');
        $query->select(
            'students.*',
            'sm.state_name',
        );

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', FacadesAuth::user()->user_id)->get()->toArray();
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
        $query->where('students.edu_type', "GRADUATION");

        $allUsers = $query->orderBy('students.id', 'desc')->get();

        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) use ($request) {

                $button = '';

                $button .= "<a href='" . url('graduation/show/' . base64_encode($allUsers->id)) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a>";

                if ($request->type == 'NEW' && Auth::user()->role_code == 'ADMIN') {
                    $button .= " <button class='btn btn-success btn-sm approveStudent' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Approve Student'><i class='bx bx-check'></i></button> ";
                }

                $button .= " <a href='" . url('/pdf/graduation/show/' . $allUsers->id) . "' class='btn btn-success btn-sm' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='pdf' target='_BLANK'><i class='bx bx-file'></i></a>";

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
            $data->updated_by = FacadesAuth::user()->user_id;
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
