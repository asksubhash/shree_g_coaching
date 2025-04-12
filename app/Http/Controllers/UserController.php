<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Authentication;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Institute;
use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDepartmentMapping;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function superadminAllUsers()
    {
        $page_title = 'All Users';
        $roles = Role::getRolesForUserCreation();
        $designations = Designation::where('record_status', 1)->orderBy('name', 'ASC')->get();
        $departments = Institute::where('record_status', 1)->orderBy('name', 'ASC')->get(['id', 'name']);
        return view('superadmin.all-users', compact('roles', 'page_title', 'designations', 'departments'));
    }

    public function adminUserSetup()
    {
        $page_title = 'All Users';
        $roles = Role::getRolesForUserCreation();
        $designations = Designation::where('record_status', 1)->orderBy('name', 'ASC')->get();
        $departments = Institute::where('record_status', 1)->orderBy('name', 'ASC')->get(['id', 'institute_code', 'name']);
        return view('admin.all-users', compact('roles', 'page_title', 'designations', 'departments'));
    }

    public function getAllUsersList(Request $request)
    {
        $query = User::query();
        $query->select('users.*', 'authentications.username', 'authentications.role_code', 'roles.role_name as role', 'institutes.name as department_name', 'udm.department_id');
        $query->leftJoin('authentications', 'users.user_id', '=', 'authentications.user_id');
        $query->leftJoin('roles', 'roles.role_code', '=', 'authentications.role_code');

        $query->leftJoin("user_department_mappings as udm", function ($join) {
            $join->on('users.user_id', '=', 'udm.user_id');
            $join->on('udm.record_status', '=', DB::raw(1));
        });
        $query->leftJoin('institutes', 'udm.department_id', '=', 'institutes.id');

        if (Auth::user()->role_code != 'SUPERADMIN') {
            $query->where('authentications.role_code', '!=', 'SUPERADMIN');
        }

        if ($request->filter_role) {
            $query->where('authentications.role_code', $request->filter_role);
        }

        $allUsers = $query->orderBy('id', 'desc')->get();
        return DataTables::of($allUsers)
            ->addColumn('action', function ($allUsers) {
                $button = "<button class='btn btn-warning btn-sm editUserBtn' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Edit User'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteUserBtn' id='" . base64_encode($allUsers->id) . "' data-toggle='tooltip' data-placement='left' title='Delete User'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->editColumn('designation_desc', function ($allUsers) {
                $designation = Designation::where('id', $allUsers->designation)->first();
                return (isset($designation->name)) ? $designation->name : '';
            })
            ->editColumn('status_desc', function ($allUsers) {
                $status = ($allUsers->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->addColumn('full_name', function ($allUsers) {
                return $allUsers->f_name . ' ' . $allUsers->l_name;
            })
            ->rawColumns(['status_desc', 'full_name', 'action', 'role'])
            ->make(true);
    }

    // Get single user full details
    public function userDetails(Request $request)
    {
        $user = User::select(
            'users.id',
            'users.f_name',
            'users.l_name',
            'users.email_id',
            'users.mobile_no',
            'users.status',
            'users.designation',
            'auth.role_code',
            'udm.department_id',
        )
            ->leftJoin('authentications as auth', 'users.user_id', '=', 'auth.user_id')
            ->leftJoin('roles as role', 'auth.role_code', '=', 'role.role_code')
            ->leftJoin("user_department_mappings as udm", function ($join) {
                $join->on('users.user_id', '=', 'udm.user_id');
                $join->on('udm.record_status', '=', DB::raw(1));
            })
            ->where('users.id', base64_decode($request->id))
            ->first();


        if ($user) {
            $output = [
                'status' => true,
                'data' => $user
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return FacadesResponse::json($output);
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email_id' => 'required|email|unique:users,email_id',
            'phone_number' => 'required|unique:users,mobile_no',
            'role' => 'required',
            'designation' => 'required',
            'department' => 'required',
            'status' => 'required',
            'operation_type' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // Default password ===============
            $defaultPassword = $request->phone_number;

            // Generate the unique code =======
            $user_id = AppHelper::generateUniqueCode();

            $result = User::create([
                'user_id' => $user_id,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email_id' => $request->email_id,
                'mobile_no' => $request->phone_number,
                'designation' => $request->designation,
                'status' => $request->status,
                'is_verified' => 1,
                'created_by' => Auth::user()->user_id
            ]);

            if ($result) {
                // Insert the department mapping ================
                $userMappingDepartmentResult = UserDepartmentMapping::create([
                    'department_id' => $request->department,
                    'user_id' => $user_id,
                    'created_by' => Auth::user()->user_id,
                    'updated_by' => Auth::user()->user_id
                ]);

                if (!$userMappingDepartmentResult) {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Error while saving user mapping department. Please try again.'
                    ]);
                }

                // Save the authentication ========================
                $authStore = new Authentication();
                $authStore->username = $request->email_id;
                $authStore->user_id = $user_id;
                $authStore->password = Hash::make($defaultPassword);
                $authStore->role_code = $request->role;
                $authStore->record_status = $request->status;
                $authStore->created_by = Auth::user()->user_id;

                if ($authStore->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'User created successfully.'
                    ]);
                } else {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
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


    public function updateUser(Request $request, User $user)
    {
        $user = User::where('id', base64_decode($request->user_id))->first();

        if (!$user) {
            return Response::json([
                'status' => false,
                'message' => 'User not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone_number' => 'required|unique:users,mobile_no,' . $user->id,
            'role' => 'required',
            'designation' => 'required',
            'department' => 'required',
            'status' => 'required',
            'operation_type' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->mobile_no = $request->phone_number;
            $user->designation = $request->designation;
            $user->status = $request->status;

            if ($user->save()) {

                // // First remove all the previous mappings
                $userRemoveMappingDepartmentResult = UserDepartmentMapping::where('user_id', $user->user_id)->update([
                    'record_status' => 0,
                ]);

                // Insert the department mapping ================
                $userMappingDepartmentResult = UserDepartmentMapping::create([
                    'department_id' => $request->department,
                    'user_id' => $user->user_id,
                    'created_by' => Auth::user()->user_id,
                    'updated_by' => Auth::user()->user_id
                ]);

                if (!$userMappingDepartmentResult) {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Error while saving user mapping department. Please try again.'
                    ]);
                }

                // change auth role 
                Authentication::where('user_id', $user->user_id)->update(['role_code' => $request->role, 'updated_by' => Auth::user()->user_id]);

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'User updated successfully.'
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

    // Function to delete
    public function deleteUser(Request $request)
    {
        $id = base64_decode($request->id);
        $data = User::find($id);
        if ($data->count() > 0) {
            $result = User::where(['id' => $id])->update([
                'status' => 0,
                'last_updated_by' => Auth::user()->email
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
