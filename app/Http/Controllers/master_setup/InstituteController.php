<?php

namespace App\Http\Controllers\master_setup;

use App\Http\Controllers\Controller;
use App\Models\DistrictMaster;
use App\Models\Institute;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InstituteController extends Controller
{
    public function index()
    {
        $states = StateMaster::where('record_status', 1)->get(['state_name', 'state_code']);
        return view('master_setup.institute_setup', ['page_title' => 'Institute Setup', 'states' => $states]);
    }

    /**
     * Function to get institutes list for datatable
     */
    function getInstituteList(Request $request)
    {
        $query = Institute::leftJoin('district_master as dm', 'institutes.district', '=', 'dm.district_code');
        $query->leftJoin('state_master as sm', 'institutes.state', '=', 'sm.state_code');
        $query->leftJoin('study_centers as sc', 'institutes.study_center_id', '=', 'sc.id');
        $query->select(
            'institutes.institute_code',
            'institutes.name',
            'institutes.address1',
            'institutes.address2',
            'institutes.pin_code',
            'institutes.record_status',
            'institutes.id',
            'institutes.registered_by',
            'dm.district_name',
            'sm.state_name',
            'institutes.state',
            'sc.name as person_name'
        );
        if ($request->filter_state) {
            $query->where('institutes.state', $request->filter_state);
        }
        $allData = $query->orderBy('institutes.id', 'DESC')->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($Institute) {
                $button = "<button class='btn btn-warning btn-sm editInstituteBtn' id='" . $Institute->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteInstituteBtn' id='" . $Institute->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($Institute) {
                $status = ($Institute->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function storeInstitute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'institute_code' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'state' => 'required',
            'district' => 'required',
            'pin_code' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

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

            $Institute = new Institute();
            $Institute->name = $request->name;
            $Institute->institute_code = $request->institute_code;
            $Institute->address1 = $request->address1;
            $Institute->address2 = $request->address2;
            $Institute->state = $request->state;
            $Institute->district = $request->district;
            $Institute->pin_code = $request->pin_code;
            $Institute->record_status = $request->status;
            $Institute->created_by = Auth::user()->user_id;
            $Institute->created_on = now();

            if ($Institute->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Institute added successfully.'
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

    // Delete Institute Function 
    function deleteInstitute(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Institute::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = 0;
            $data->updated_by = Auth::user()->user_id;
            $data->updated_on = now();

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
                'message' => 'State not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }


    // Function for get details of Institute for update
    function instituteDetail(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Institute::where(['id' => $id])->select(
            'institute_code',
            'name',
            'address1',
            'address2',
            'pin_code',
            'record_status',
            'state',
            'id',
            'district',
        )->first();
        if ($details) {
            $output = [
                'status' => true,
                'data' => $details
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return Response::json($output);
    }

    // Function for update Institute
    function updateInstitute(Request $request)
    {
        $id = base64_decode($request->inst_id);
        $Institute = Institute::find($id);
        if (!$Institute) {
            return Response::json([
                'status' => false,
                'message' => 'Institute not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'institute_code' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'state' => 'required',
                'district' => 'required',
                'pin_code' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $Institute->name = $request->name;
                $Institute->institute_code = $request->institute_code;
                $Institute->address1 = $request->address1;
                $Institute->address2 = $request->address2;
                $Institute->state = $request->state;
                $Institute->district = $request->district;
                $Institute->pin_code = $request->pin_code;
                $Institute->record_status = $request->status;
                $Institute->updated_by = Auth::user()->user_id;
                $Institute->updated_on = now();
                if ($Institute->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Institute Updated successfully.'
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
    }
}
