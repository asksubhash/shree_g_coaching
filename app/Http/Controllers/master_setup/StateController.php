<?php

namespace App\Http\Controllers\master_setup;

use App\Http\Controllers\Controller;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StateController extends Controller
{
    public function index()
    {
        return view('master_setup.state_setup', ['page_title' => 'State Setup']);
    }


    function getStateList()
    {

        $stateList = StateMaster::orderBy('id', 'DESC')->get();
        return DataTables::of($stateList)
            ->addColumn('action', function ($stateList) {
                $button = "<button class='btn btn-warning btn-sm editStateBtn' id='" . $stateList->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteStateBtn' id='" . $stateList->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($stateList) {
                $status = ($stateList->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function storeState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name' => 'required',
            'country_code' => 'string',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $state = new StateMaster();
            $state->state_name = $request->state_name;
            $state->country_code = ($request->country_code) ? $request->country_code : 'IND';
            $state->record_status = $request->status;
            $state->created_by = Auth::user()->user_id;
            $state->created_on = now();

            if ($state->save()) {
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

    // Delete Category Function 
    function deleteState(Request $request)
    {
        $id = base64_decode($request->id);
        $data = StateMaster::where(['id' => $id])->first();
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


    // Function for get details of category for update
    function stateDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = StateMaster::where(['id' => $id])->select('state_name', 'record_status', 'id', 'country_code')->first();
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

    // Function for update category
    function updateState(Request $request)
    {
        $id = base64_decode($request->state_id);
        $state = StateMaster::find($id);
        if (!$state) {
            return Response::json([
                'status' => false,
                'message' => 'State not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'state_name' => 'required',
                'country_code' => 'string',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $state->state_name = $request->state_name;
                $state->country_code = ($request->country_code) ? $request->country_code : 'IND';
                $state->record_status = $request->status;
                $state->updated_by = Auth::user()->user_id;
                $state->updated_on = now();
                if ($state->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'State Updated successfully.'
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
