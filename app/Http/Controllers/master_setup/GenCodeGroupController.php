<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GenCodeGroup;
use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GenCodeGroupController extends Controller
{
    function  index()
    {
        $page_title = 'Gen Code Group Setup';
        return view('master_setup.gen_code_group_setup', compact('page_title'));
    }

    function getGroupList()
    {
        $genCodeGroup = GenCodeGroup::orderBy('id', 'desc')->where('status', 1)->get();

        return DataTables::of($genCodeGroup)
            ->addColumn('action', function ($genCodeGroup) {
                $button = "<button class='btn btn-warning btn-sm editDepartmentBtn' id='" . base64_encode($genCodeGroup->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Department'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteDepartmentBtn' id='" . base64_encode($genCodeGroup->id) . "' data-toggle='tooltip' data-placement='left' title='Delete Department'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($genCodeGroup) {
                $status = ($genCodeGroup->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function addGenCodeGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = GenCodeGroup::create([
                'group_name' => $request->group_name,
                'status' => $request->status,
                'created_by' => Auth::user()->user_id
            ]);
            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Add Gen Code Group successfully.'
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
