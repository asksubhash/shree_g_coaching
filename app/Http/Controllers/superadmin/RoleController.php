<?php

namespace App\Http\Controllers\superadmin;

use Response;

use App\Models\Role;
use App\Models\Resource;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function allRoles()
    {
        $page_title = 'All Roles';
        $resources = Resource::where('record_status', 1)->get(['id', 'resource_name']);
        return view('superadmin.all-roles', compact('page_title', 'resources'));
    }

    // Function to get all assign-projects in for data table;
    public function getAllRolesList()
    {
        $query = Role::query();
        $allRoles = $query->orderBy('status', 'desc')->orderBy('id', 'desc');
        return DataTables::of($allRoles)
            ->addColumn('action', function ($allRoles) {
                $button = "<button class='btn btn-warning btn-sm editRoleBtn' id='" . base64_encode($allRoles->id) . "' data-toggle='tooltip' data-placement='left' title='Edit User'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteUserBtn' id='" . base64_encode($allRoles->id) . "' data-toggle='tooltip' data-placement='left' title='Delete User'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('resource_name', function ($allRoles) {
                return (isset($allRoles->resource->resource_name)) ? $allRoles->resource->resource_name : '';
            })
            ->editColumn('status_desc', function ($allRoles) {
                $status = ($allRoles->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    // Get single user full details
    public function roleDetails(Request $request)
    {
        $role = Role::select('id', 'role_name', 'role_code', 'status', 'resource_id')->find(base64_decode($request->id));

        if ($role) {
            $output = [
                'status' => true,
                'data' => $role
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return Response::json($output);
    }

    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_code' => 'required|unique:roles,role_code',
            'role_name' => 'required|unique:roles,role_name',
            'status' => 'required',
            'resource_code' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = Role::create([
                'role_code' => $request->role_code,
                'role_name' => $request->role_name,
                'status' => $request->status,
                'resource_id' => $request->resource_code,
            ]);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Role created successfully.'
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


    public function updateRole(Request $request, Role $role)
    {
        $role = Role::find(base64_decode($request->role_id));
        if (!$role) {
            return Response::json([
                'status' => false,
                'message' => 'Role not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,role_name,' . $role->id,
            'status' => 'required',
            'operation_type' => 'required',
            'resource_code' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            $role->role_name = $request->role_name;
            $role->status = $request->status;
            $role->resource_id = $request->resource_code;

            if ($role->save()) {
                return Response::json([
                    'status' => true,
                    'message' => 'Role updated successfully.'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }

    // Function to delete
    public function deleteRole(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Role::find($id);
        if ($data->count() > 0) {
            $result = Role::where(['id' => $id])->update([
                'status' => 0
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
                'message' => 'User not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }
}
