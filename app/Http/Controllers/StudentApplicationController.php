<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\User;
use App\Models\GenCode;
use App\Models\Student;
use App\Helpers\AppHelper;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use App\Models\Authentication;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDepartmentMapping;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class StudentApplicationController extends Controller
{
    use FileUploadTrait;

    public function studentApplication()
    {
        $data['states'] = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $data['gender'] = GenCode::getGenCodeUsingGroup('GENDER');
        $data['religion'] = GenCode::getGenCodeUsingGroup('RELIGION');
        $data['category'] = GenCode::getGenCodeUsingGroup('CATEGORY');

        return view('website.student.student_application')->with($data);
    }

    public function storeStudentApplication(Request $request)
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
            'category' => 'required|string',
            'contact' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'aadhar_number' => 'required|string',
            'photo' => 'required|mimes:png,jpeg,jpg',
            'aadhar' => 'required|mimes:jpg,jpeg,pdf',
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
            $maxId = Student::max('id');
            $applicationNo = 1000 + $maxId + 1;
            $applicationNo = date('Ymd') . $applicationNo;

            $user = new Student();
            $user->register_through = 'ONLINE';
            $user->application_no = $applicationNo;
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
            $user->created_by = $applicationNo;
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

            $user->is_approved = 0;
            $user->belongs_to_center = 1;
            $user->institute_id = env('CENTER_INSTITUTE_ID');

            if ($user->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Application submitted successfully. Study center will revert you as soon as possible.',
                    'redirect_url' => ''
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

    public function studyCenterStudentNewApplications()
    {
        $page_title = 'New Applications';
        return view('student_new_applications.new_applications', compact('page_title'));
    }
    public function fetchStudyCenterStudentNewApplications(Request $request)
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


        $query->where('students.is_approved', 0);
        $query->where('students.record_status', 1);
        $query->where('students.edu_type', NULL);
        $query->where('students.register_through', "ONLINE");

        $allUsers = $query->orderBy('students.id', 'desc')->get();

        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) {
                // <a href='" . url('high-school/edit/' . base64_encode($allUsers->id)) . "' class='btn btn-warning btn-sm editUserBtn' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></a> 

                $button = '';

                $button .= "<a href='" . url('study-center/student/new-applications/show/' . base64_encode($allUsers->id)) . "'class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a> ";

                $button .= "<div class='dropdown d-inline-block'>
                    <button class='btn btn-primary btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>Action</button>
                    <ul class='dropdown-menu' style=''>
                        <li>
                            <a href='" . url('high-school/edit/' . base64_encode($allUsers->id)) . "'class='dropdown-item'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-send'></i> 10th</a>
                        </li>
                        <li>
                            <a href='" . url('inter/edit/' . base64_encode($allUsers->id)) . "'class='dropdown-item'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-send'></i> 12th</a>
                        </li>
                        <li>
                            <a href='" . url('graduation/edit/' . base64_encode($allUsers->id)) . "'class='dropdown-item'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-send'></i> Graduation</a>
                        </li>
                    </ul>
                </div>";

                return $button;
            })
            ->editColumn('status_desc', function ($allUsers) {
                $status = ($allUsers->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    public function showStudyCenterStudentNewApplications(Request $request)
    {
        $id = base64_decode($request->id);

        $data['user'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->findOrFail($id);

        $data['page_title'] = "Student Detail: " . $data['user']->name;

        return view('student_new_applications.view')->with($data);
    }

    /**
     * Function to change the approval status and set the roll number
     */

    public function studentApplicationApproval(Request $request)
    {
        $validationRules = [
            'id' => 'required',
            'roll_number' => 'required|string',
            'status' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        }

        $id = base64_decode($request->id);
        $studentData = Student::find($id);

        if ($studentData->count() > 0) {

            // Create the student credentials
            DB::beginTransaction();

            // Check if the roll number is duplicate
            $checkRollNumber = Student::where([
                'roll_number' => $request->roll_number
            ])->count();

            if ($checkRollNumber > 0) {
                return Response::json([
                    'status' => false,
                    'message' => 'Duplicate roll number entry. Please enter unique roll number.'
                ]);
            }

            // =======================================
            // Update student
            $studentUpdateResult = Student::where([
                'id' => $id,
            ])->update([
                'roll_number' => $request->roll_number,
                'is_approved' => $request->status,
                'approved_on' => now(),
                'approved_by' => Auth::user()->user_id,
                'updated_at' => now(),
                'updated_by' => Auth::user()->user_id,
            ]);


            if ($studentUpdateResult) {

                // Generate the unique code =======
                $user_id = AppHelper::generateUniqueCode();

                $user = new User();
                $user->user_id = $user_id;
                $user->edu_type = $studentData->course;
                $user->f_name = $studentData->name;
                $user->l_name = '';
                $user->email_id = $studentData->email;
                $user->mobile_no = $studentData->contact_number;
                $user->is_verified = 1;
                $user->created_by = $user_id;
                $result = $user->save();

                if ($result) {

                    // $defaultPassword = 'Password@123';
                    $defaultPassword = $studentData->contact_number;

                    // Save the authentication ========================
                    $authStore = new Authentication();
                    $authStore->user_id = $user_id;
                    $authStore->username = $request->roll_number;
                    $authStore->password = Hash::make($defaultPassword);
                    $authStore->role_code = "STUDENT";
                    $authStore->created_by = $user_id;

                    if ($authStore->save()) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Student approved and roll number assigned successfully. Credentials of student created successfully.'
                        ]);
                    } else {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Error while generating credentials. Please try again.'
                        ]);
                    }
                } else {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Error while creating user for student. Please try again.'
                    ]);
                }
            } else {
                DB::rollBack();
                $output = [
                    'status' => false,
                    'message' => 'Error while updating roll number. Please try again.'
                ];
                return Response::json($output);
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'Data not found. Please try again or contact support.'
            ];
            return Response::json($output);
        }
    }
}
