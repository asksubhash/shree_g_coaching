<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Institute;
use App\Models\StateMaster;
use App\Models\StudyCenter;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PDO;
use Yajra\DataTables\DataTables;

class StudyCenterController extends Controller
{
    use FileUploadTrait;

    public function login()
    {
        return view('website.study_center.login');
    }

    public function register()
    {
        $states = StateMaster::where('record_status', 1)->get(['state_code', 'state_name']);
        $institute = Institute::where('record_status', 1)->get(['id', 'name']);
        return view('website.study_center.register', compact('states', 'institute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:study_centers,email_id',
            'contact' => 'required|integer',
            'address1' => 'required|string',
            'address2' => 'string',
            'education_qualification' => 'required|string',
            'occupation' => 'required|string',
            'nature_or_work' => 'required|string',
            'state' => 'required|integer',
            'district' => 'required|integer',
            'pin_code' => 'required|integer',
            'city' => 'required|string',
            'institute_name' => 'required|string',
            'property' => 'required|string',
            'photo' => 'required|mimes:png,jpeg,jpg|max:1024', // 2MB in kilobytes
            'aadhar' => 'required|mimes:jpg,jpeg,pdf|max:2048', // 2MB in kilobytes
            'document' => 'required|mimes:jpg,jpeg,pdf|max:2048', // 2MB in kilobytes
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $user = new StudyCenter();
            $user->name = $request->name;
            $user->email_id = $request->email;
            $user->contact_no = $request->contact;
            $user->address1 = $request->address1;
            $user->address2 = $request->address2;
            $user->education_qualification = $request->education_qualification;
            $user->occupation = $request->occupation;
            $user->nature_of_work = $request->nature_or_work;
            $user->state = $request->state;
            $user->district = $request->district;
            $user->pin_code = $request->pin_code;
            $user->city_name = $request->city;
            $user->institute_name = $request->institute_name;
            $user->property = $request->property;
            $user->created_at = now();

            $filePath = Config::get('constants.files_storage_path')['STUDY_CENTER_PHOTO_STORAGE_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('photo')) {
                $photo = $this->uploadSingleFile($request->photo, $filePath, true);
                $user->passport_photo =  $photo['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDY_CENTER_AADHAAR_STORAGE_PATH'];
            if ($request->file('aadhar')) {
                $aadhar = $this->uploadSingleFile($request->aadhar, $filePath, true);
                $user->aadhar_card =  $aadhar['filename'];
            }

            $filePath = Config::get('constants.files_storage_path')['STUDY_CENTER_EDU_DOC_STORAGE_PATH'];
            if ($request->file('document')) {
                $document = $this->uploadSingleFile($request->document, $filePath, true);
                $user->education_document =  $document['filename'];
            }

            if ($user->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Application Submitted successfully. Please wait for approval, we will revert you as soon as possible.'
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

    /**
     * Display a listing of the resource.
     */
    public function newApplications()
    {
        $page_title = 'New Study Center List';
        return view('study_center.new_application_list', compact('page_title'));
    }

    public function fetchAllNewRegistered(Request $request)
    {
        $query = StudyCenter::leftJoin('state_master as sm', 'study_centers.state', '=', 'sm.state_code')
            ->leftJoin('district_master as dm', 'study_centers.district', '=', 'dm.district_code');
        $query->select(
            'study_centers.id',
            'study_centers.name',
            'study_centers.email_id',
            'study_centers.contact_no',
            'study_centers.address1',
            'study_centers.city_name',
            'study_centers.pin_code',
            'study_centers.status',
            'study_centers.is_verified',
            'study_centers.institute_name',
            'sm.state_name',
            'dm.district_name'
        );

        $query->where('study_centers.status', '=', 1);
        $query->where('study_centers.is_verified', '=', 0);
        $allUsers = $query->orderBy('study_centers.id', 'desc')->get();

        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) {
                $button = "<a href='" . url('study-centers/new-applications/show/' . $allUsers->id) . "'class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='left' title='View'><i class='bx bx-show'></i></a>";

                $button .= " <a href='" . url('/pdf/study-centers/show/' . $allUsers->id) . "' class='btn btn-success btn-sm' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='pdf' target='_BLANK'><i class='bx bx-file'></i></a>";

                return $button;
            })
            ->editColumn('status_desc', function ($allUsers) {
                $status = ($allUsers->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })

            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showApplicationsDetails($id)
    {
        $query = StudyCenter::leftJoin('state_master as sm', 'study_centers.state', '=', 'sm.state_code');
        $query->leftJoin('district_master as dm', 'study_centers.district', '=', 'dm.id');
        $query->select(
            'study_centers.*',
            'sm.state_name',
            'dm.district_name'
        );

        $query->where('study_centers.id', $id);
        $scData = $query->first();

        $page_title = 'Study Center Details';

        return view('study_center.view_application_details', compact('scData', 'page_title'));
    }

    /**
     * Function for approval/rejection of new application
     */
    public function approvalOrRejectionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_center_id' => 'required',
            'status' => 'required|string',
            'remarks' => 'required_if:status,1',
            'institute_code' => 'required_if:status,1',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $result = StudyCenter::where('id', $request->study_center_id)->update([
                'is_verified' => $request->status,
                'remarks' => $request->remarks,
                'status_changed_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ]);

            if ($result) {

                // Check if the institute code is already registered or not
                $checkCount = Institute::where([
                    'institute_code' => $request->institute_code,
                    'record_status' => 1
                ])->count();

                if ($checkCount > 0) {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Institute Code is already registered, please use unique institute code.'
                    ]);
                }

                // Get the data from study center table
                $scData = StudyCenter::where('id', $request->study_center_id)->first();

                // Insert the data into the main institute setup
                $insResult = Institute::create([
                    'institute_code' => $request->institute_code,
                    'name' => $scData->institute_name,
                    'address1' => $scData->city_name,
                    'address2' => '',
                    'state' => $scData->state,
                    'district' => $scData->district,
                    'pin_code' => $scData->pin_code,
                    'registered_by' => 'SELF',
                    'study_center_id' => $request->study_center_id,
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'created_on' => now(),
                ]);

                if (!$insResult) {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Error while saving data into masters setup. Please try again.'
                    ]);
                }

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => ($request->status == 1) ? 'Application approved successfully.' : 'Application rejected successfully'
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyCenter $studyCenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyCenter $studyCenter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyCenter $studyCenter)
    {
        //
    }
}
