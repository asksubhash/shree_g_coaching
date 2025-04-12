<?php

namespace App\Http\Controllers\master_setup;

use App\Http\Controllers\Controller;
use App\Models\DistrictMaster;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DistrictMasterController extends Controller
{
    public function index()
    {
        $states = StateMaster::where('record_status', 1)->select('id', 'state_name')->get();
        return view('master_setup.district_setup', ['page_title' => 'District Setup', 'states' => $states]);
    }


    function getDistrictList()
    {

        $district = DistrictMaster::join('state_master as stm', 'district_master.fk_state_code', '=', 'stm.state_code')
            ->select('stm.state_name', 'district_master.district_name', 'district_master.id', 'district_master.record_status')
            ->orderBy('district_master.id', 'DESC')->get();
        return DataTables::of($district)
            ->addColumn('action', function ($district) {
                $button = "<button class='btn btn-warning btn-sm editDistrictBtn' id='" . $district->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteDistrictBtn' id='" . $district->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($district) {
                $status = ($district->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function storeDistrict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district_name' => 'required',
            'status' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $district = new DistrictMaster();
            $district->fk_state_code = $request->state;
            $district->district_name = $request->district_name;
            $district->record_status = $request->status;
            $district->created_by = Auth::user()->user_id;
            $district->created_on = now();

            if ($district->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Add State successfully.'
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

    // Delete district Function 
    function deleteDistrict(Request $request)
    {
        $id = base64_decode($request->id);
        $data = DistrictMaster::where(['id' => $id])->first();
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


    // Function for get details of district for update
    function districtDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = DistrictMaster::where(['id' => $id])->select('state', 'district_name', 'record_status', 'id')->first();

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

    // Function for update district
    function updateDistrict(Request $request)
    {
        $id = base64_decode($request->district_id);
        $district = DistrictMaster::find($id);
        if (!$district) {
            return Response::json([
                'status' => false,
                'message' => 'District not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'district_name' => 'required',
                'status' => 'required',
                'state' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $district->fk_state_code = $request->state;
                $district->district_name = $request->district_name;
                $district->record_status = $request->status;
                $district->updated_by = Auth::user()->user_id;
                $district->updated_on = now();
                if ($district->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'District Updated successfully.'
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
