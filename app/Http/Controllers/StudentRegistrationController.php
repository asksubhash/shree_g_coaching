<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\AcademicYear;
use App\Models\AdmissionSession;
use App\Models\Authentication;
use App\Models\ClassMaster;
use App\Models\GenCode;
use App\Models\Institute;
use App\Models\StateMaster;
use App\Models\Student;
use App\Models\StudentAcademicDetail;
use App\Models\StudentSubjectMapping;
use App\Models\UserDepartmentMapping;
use App\Traits\FileUploadTrait;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StudentRegistrationController extends Controller
{
    use FileUploadTrait;
    public function add(Request $request,  $page = null, $student_code = null)
    {


        $activeTab =  AppHelper::getStudentRegisterTabConstant($page ? $page : 'PERSONAL_DETAIL');
        $academic_query = AcademicYear::select('academic_year', 'id');
        $classes_query = ClassMaster::select('name', 'id')->where('record_status', 1);
        $admission_session_query = AdmissionSession::select('session_name', 'id')->where('record_status', 1);
        $instituteId = "";
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $academic_query->where('institute_id', $instituteId);
            $classes_query->where('institute_id', $instituteId);
            $admission_session_query->where('institute_id', $instituteId);
        }

        $data['institutes'] = [];
        // Check if the role code is in INS_DEO and IND_HEAD
        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the mapped institutes
            $data['institutes'] = UserDepartmentMapping::getUserMappedInstitutes(Auth::user()->user_id);
        }

        if ($student_code) {
            try {
                $data['user'] = Student::findOrFail(base64_decode($student_code));
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return redirect()->back()->with('error', 'Student not found!');
            }
        }
        $data['page_title'] = 'Register Student';
        $data['courses'] = [];
        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');
        $data['academic_years'] = $academic_query->where('record_status', 1)->get();
        $data['classes'] = $classes_query->get();
        $data['admission_sessions'] = $admission_session_query->get();
        $data['instituteId'] = $instituteId;
        $data['page'] = $page;
        $data['student_code'] = $student_code;
        $data['activeTab'] = $activeTab;
        return view('institute_head.student.add')->with($data);
    }


    public function store(Request $request)
    {
        $validationRules = [
            'medium_off_inst' => 'required|string',
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'class_id' => 'required|integer',
            'subjects.*' => 'required|string',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'religion' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'photo' => 'required|mimes:png,jpeg,jpg,webp',
            'aadhar' => 'required|mimes:png,jpg,jpeg,pdf,webp',
        ];



        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            DB::beginTransaction();

            $instituteId = "";
            if (Auth::user()->role?->role_code === "INS_HEAD") {
                $instituteId = AppHelper::getCurrentUserInstituteId();
            }


            // ===============================
            // Get the student max id
            $maxId = Student::max('id');
            $applicationNo = 1000 + $maxId + 1;

            $user = new Student();
            $user->institute_id = $instituteId;
            $user->register_through = 'INSTITUTE';
            $user->registered_by = Auth::user()->user_id;
            $user->application_no = date('Ymd') . $applicationNo;
            $user->academic_year = $request->academic_year;
            $user->adm_sesh = $request->admission_session;
            $user->class_id = $request->class_id;
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
            $user->is_approved = 0;

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

            if ($user->save()) {

                // ===============================================
                // Save student subjects
                $courseSubjects = $request->subjects;
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
                                'message' => 'Error while saving student subjects. Please try again.'
                            ]);
                        }
                    }
                }

                // ===============================================
                // create student credentials for login 
                // ===============================================

                // Generate the unique code =======
                $user_id = AppHelper::generateUniqueCode();
                $user = new User();
                $user->user_id = $user_id;
                $user->f_name = $request->name;
                $user->email_id = $request->email;
                $user->mobile_no = $request->contact;
                $user->is_verified = 0;
                $user->created_by = $user_id;
                $result = $user->save();

                if ($result) {
                    // Save the authentication ========================
                    $password = $request->dob . '' . substr($request->contact, -4);
                    $authStore = new Authentication();
                    $authStore->username = date('Ymd') . $applicationNo;
                    $authStore->user_id = $user_id;
                    $authStore->password = Hash::make($password);
                    $authStore->role_code = "STUDENT";
                    $authStore->created_by = $user_id;
                }

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Application registration processing started successfully. Proceed with the payment.',
                    'redirect_url' => url('student/lists')
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

    public function storePersonalDetail(Request $request)
    {
        $validationRules = [
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'religion' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'photo' => 'nullable|mimes:png,jpeg,jpg,webp',
            'aadhar' => 'nullable|mimes:png,jpg,jpeg,pdf,webp',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            DB::beginTransaction();

            $instituteId = "";
            if (Auth::user()->role?->role_code === "INS_HEAD") {
                $instituteId = AppHelper::getCurrentUserInstituteId();
            }


            // ===============================
            // Get the student max id
            $maxId = Student::max('id');
            $applicationNo = 1000 + $maxId + 1;
            $user = new Student();
            $user->institute_id = $instituteId;
            $user->register_through = 'INSTITUTE';
            $user->registered_by = Auth::user()->user_id;
            $user->application_no = date('Ymd') . $applicationNo;
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
            $user->created_by = Auth::user()->user_id;
            $user->created_at = now();
            $user->is_approved = 0;


            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('photo')) {
                $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }


            if ($request->file('aadhar')) {
                $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            if ($user->save()) {
                $student_id = $user->id;

                // ===============================================
                // create student credentials for login 
                // ===============================================

                // Generate the unique code =======
                $user_id = AppHelper::generateUniqueCode();
                $user = new User();
                $user->user_id = $user_id;
                $user->f_name = $request->name;
                $user->email_id = $request->email;
                $user->mobile_no = $request->contact;
                $user->is_verified = 0;
                $user->created_by = $user_id;
                $result = $user->save();

                if ($result) {
                    // Save the authentication ========================
                    $password = $request->dob . '' . substr($request->contact, -4);
                    $authStore = new Authentication();
                    $authStore->username = date('Ymd') . $applicationNo;
                    $authStore->user_id = $user_id;
                    $authStore->password = Hash::make($password);
                    $authStore->role_code = "STUDENT";
                    $authStore->created_by = $user_id;
                }

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student personal detail saved successfully',
                    'student_id' => base64_encode($student_id),
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

    public function updatePersonalDetail(Request $request)
    {
        $validationRules = [
            'hiddenId' => 'required|string',
            'name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required',
            'religion' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required|integer',
            'state' => 'required|integer',
           'email' => 'required|email|unique:students,email,' . base64_decode($request->hiddenId) . ',id',
            'contact' => 'required|integer',
            'category' => 'required|string',
            'aadhar_number' => 'required|string',
            'photo' => 'nullable|mimes:png,jpeg,jpg,webp',
            'aadhar' => 'nullable|mimes:png,jpg,jpeg,pdf,webp',
        ];

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
            $user = Student::find(base64_decode($request->hiddenId));
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
            $user->created_by = Auth::user()->user_id;
            $user->created_at = now();
            $user->is_approved = 0;


            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('photo')) {
                $filePath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_UPLOAD_PATH'];
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->photo =  $photo['filename'];
            }


            if ($request->file('aadhar')) {
                $filePath = Config::get('constants.files_storage_path')['STUDENT_AADHAAR_UPLOAD_PATH'];
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar =  $aadhar['filename'];
            }

            if ($user->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student personal detail updated successfully',
                    'student_id' => base64_encode($user->id),
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

    public function storeAcademicDetail(Request $request)
    {

        $validationRules = [
            'student_id' => 'required|string',
            'medium_off_inst' => 'required|string',
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'class_id' => 'required|string',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            DB::beginTransaction();

            // ===============================
            //Add Student Academic detail
            $user = new StudentAcademicDetail();
            $user->student_id = $request->student_id;
            $user->class_id = $request->class_id;
            $user->academic_year = $request->academic_year;
            $user->admission_session_id = $request->admission_session;
            $user->medium_off_inst = $request->medium_off_inst;
            $user->created_by = Auth::user()->user_id;
            $user->created_at = now();


            if ($user->save()) {
                $academic_id = $user->id;

                // ===============================================
                // Save student subjects
                $courseSubjects = $request->subjects;
                if (count($courseSubjects) > 0) {
                    $courseSubjectArray = [];
                    foreach ($courseSubjects as $key => $subject) {
                        array_push($courseSubjectArray, [
                            'academic_detail_id' =>    $academic_id,
                            'student_id' => $request->student_id,
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
                                'message' => 'Error while saving student subjects. Please try again.'
                            ]);
                        }
                    }
                }

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student academic detail saved successfully',
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
    public function updateAcademicDetail(Request $request)
    {
        $validationRules = [
            'hidden_code'=>"required:string",
            'student_id' => 'required|string',
            'medium_off_inst' => 'required|string',
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'class_id' => 'required|string',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            DB::beginTransaction();

            // ===============================
            //Update Student Academic detail
            $user = StudentAcademicDetail::find(base64_decode($request->hidden_code));
            $user->class_id = $request->class_id;
            $user->academic_year = $request->academic_year;
            $user->admission_session_id = $request->admission_session;
            $user->medium_off_inst = $request->medium_off_inst;
            $user->updated_by = Auth::user()->user_id;
            $user->updated_at = now();


            if ($user->save()) {
                $academic_id = $user->id;

                // ===============================================
                // Save student subjects
                $courseSubjects = $request->subjects;
                if (count($courseSubjects) > 0) {
                    $courseSubjectArray = [];
                    foreach ($courseSubjects as $key => $subject) {
                        array_push($courseSubjectArray, [
                            'academic_detail_id' =>    $academic_id,
                            'student_id' => $request->student_id,
                            'subject_id' => $subject,
                            'record_status' => 1,
                            'created_by' => Auth::user()->user_id,
                            'created_at' => now(),
                            'updated_by' => Auth::user()->user_id,
                            'updated_at' => now(),
                        ]);
                    }

                    if (count($courseSubjectArray) > 0) {
                        // delete old detail 
                        StudentSubjectMapping::where('academic_detail_id',$academic_id)->delete();
                        $ssmResult = StudentSubjectMapping::insert($courseSubjectArray);
                        if (!$ssmResult) {
                            DB::rollBack();
                            return Response::json([
                                'status' => false,
                                'message' => 'Error while saving student subjects. Please try again.'
                            ]);
                        }
                    }
                }

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student academic detail saved successfully',
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



    public function studentAcademicDataTableList(Request $request)
    {

        $query = StudentAcademicDetail::leftJoin('class_masters as a', 'student_academic_details.class_id', '=', 'a.id')
            ->leftJoin('academic_years as b', 'student_academic_details.academic_year', '=', 'b.id')
            ->leftJoin('admission_sessions as c', 'student_academic_details.admission_session_id', '=', 'c.id')
            ->leftJoin('student_subject_mappings as d', 'student_academic_details.id', '=', 'd.academic_detail_id')
            ->leftJoin('subjects as e', 'd.subject_id', '=', 'e.id')
            ->select(
                'student_academic_details.id',
                'student_academic_details.medium_off_inst',
                'a.name as class_name',
                'b.academic_year',
                'c.session_name',
                DB::raw('GROUP_CONCAT(e.name SEPARATOR ", ") as subjects')
            )
            ->groupBy(
                'student_academic_details.id',
                'student_academic_details.medium_off_inst',
                'a.name',
                'b.academic_year',
                'c.session_name'
            );
        $allUsers = $query->where('student_academic_details.student_id', $request->student_id);
        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) use ($request) {
                return "<button class='btn btn-warning btn-sm editAcademicDetailBtn' id='" . $allUsers->id . "' data-toggle='tooltip' data-placement='left' title='Edit Academic Detail'><i class='bx bx-edit'></i></button>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function studentAcademicDetail(Request $request)
    {

        try {
            $id=base64_decode($request->id);
            $record = StudentAcademicDetail::find($id);
            if (!$record) {
                return Response::json(['status' => false, 'message' => 'Record not found']);
            }
            $subjects = StudentSubjectMapping::where('academic_detail_id', $id)
                ->get(['subject_id']);
            return Response::json([
                'status' => true,
                'ac_detail' => $record,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
        
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function allStudentApplications()
    {
        $academic_years = AcademicYear::getAcademicYears();
        $institutes = Institute::getInstitutes();
        $admSessions = AdmissionSession::where('record_status', 1)->get();
        $page_title = 'Students Lists';
        return view('institute_head.student.student_lists', compact('page_title', 'academic_years', 'institutes', 'admSessions'));
    }


    public function studentDataTableList(Request $request)
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

                $button .= "<a href='" . route("student.view", base64_encode($allUsers->id)) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a> <a href='" . url('payment/student/fees/show?payment_id=' . base64_encode($allUsers->payment_id)) . "'class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-money'></i></a>";

                if ($allUsers->is_approved == 0 && Auth::user()->role_code == 'INS_HEAD') {
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


    public function  view($id)
    {
        if (!$id) {
            return redirect()->route('student/lists');
        }
        $id = base64_decode($id);

        $data['user'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('class_masters as cs', 'students.class_id', '=', 'cs.id')
            ->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id')
            ->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'ay.academic_year as st_academic_year',
                'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                'cs.name as course_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->findOrFail($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;

        $data['classSubjects'] = StudentSubjectMapping::getStudentSubjectsUsingStudentId($data['user']->id);

        return view('institute_head.student.view_application')->with($data);
    }
}
