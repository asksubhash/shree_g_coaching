<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    function index()
    {
        return view('master_setup.department_setup', ['page_title' => 'Department Setup']);
    }

    function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = Department::create([
                'department_name' => $request->department_name,
                'created_by' => Auth::user()->user_id
            ]);
            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Add Department successfully.'
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

    function allDepartmentList()
    {
        $allDepartment = Department::orderBy('id', 'desc')->where('record_status', 1)->get();

        return DataTables::of($allDepartment)
            ->addColumn('action', function ($allDepartment) {
                $button = "<button class='btn btn-warning btn-sm editDepartmentBtn' id='" . base64_encode($allDepartment->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Department'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteDepartmentBtn' id='" . base64_encode($allDepartment->id) . "' data-toggle='tooltip' data-placement='left' title='Delete Department'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($allDepartment) {
                $status = ($allDepartment->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    // Delete Department Function 
    function delete(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Department::find($id);

        if ($data->count() > 0) {
            $result = Department::where(['id' => $id])->update([
                'record_status' => 0,
                'updated_by' => Auth::user()->user_id
            ]);

            if ($result) {
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
                'message' => 'Department not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }

    function departmentDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Department::find($id);
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

    function update(Request $request)
    {
        $id = base64_decode($request->hidden_id);
        $department = Department::find($id);
        if (!$department) {
            return Response::json([
                'status' => false,
                'message' => 'Department not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'department_name' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();

                $department->department_name = $request->department_name;
                $department->updated_by = Auth::user()->user_id;

                if ($department->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Update Department successfully.'
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
