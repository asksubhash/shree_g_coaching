<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GenCode;
use App\Models\GenCodeGroup;
use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GenCodeController extends Controller
{
    function index()
    {
        $gen_code_group = GenCodeGroup::all()->where('status', 1);
        $page_title = 'Gen Code Setup';
        return view('master_setup.gen_code_setup', compact('page_title', 'gen_code_group'));
    }

    function GetGencodeList()
    {
        // $genCode = GenCode::orderBy('id', 'desc')->where('status',1)->get();
        $genCode = DB::table('gen_codes')
            ->select('gen_codes.id', 'gen_codes.gen_code', 'gen_codes.description', 'gen_codes.serial_no', 'gen_codes.status', 'gen_code_groups.group_name')
            ->leftJoin('gen_code_groups', 'gen_codes.gen_code_group_id', '=', 'gen_code_groups.id')
            ->where('gen_codes.status', 1)
            ->get();
        return DataTables::of($genCode)
            ->addColumn('action', function ($genCode) {
                $button = "<button class='btn btn-warning btn-sm editGencodeBtn' id='" . $genCode->id . "' data-toggle='tooltip' data-placement='left' title='Edit Gen code'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteGencodeBtn' id='" . $genCode->id . "' data-toggle='tooltip' data-placement='left' title='Delete Gen code'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($genCode) {
                $status = ($genCode->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function addGenCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gen_code_group' => 'required',
            'gen_code' => 'required',
            'description' => 'required',
            'serial_no' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = GenCode::create([
                'gen_code_group_id' => $request->gen_code_group,
                'gen_code' => $request->gen_code,
                'description' => $request->description,
                'serial_no' => $request->serial_no,
                'status' => $request->status,
                'created_by' => Auth::user()->user_id
            ]);
            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Gen code add successfully.'
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

    function deleteGenCode(Request $request)
    {

        $id = $request->id;
        $data = GenCode::where(['id' => $id])->first();

        if ($data) {
            $result = GenCode::where(['id' => $id])->update([
                'status' => 0,
                'updated_by' => Auth::user()->user_id
            ]);

            if ($result) {
                $output = [
                    'status' => true,
                    'message' => 'Gen Code deleted successfully.'
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
                'message' => 'Gen Code not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }

    // Function for get details of gencode for update
    function editGenCode(Request $request)
    {
        $id = $request->id;
        $details = GenCode::where(['id' => $id])->first();
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
    function updateGenCode(Request $request)
    {
        $id = $request->hidden_id;
        $gen_code = GenCode::where(['id' => $id])->first();

        if (!$gen_code) {
            return Response::json([
                'status' => false,
                'message' => 'Gen Code not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'gen_code_group' => 'required',
                'gen_code' => 'required',
                'description' => 'required',
                'serial_no' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $gen_code->gen_code_group_id = $request->gen_code_group;
                $gen_code->gen_code = $request->gen_code;
                $gen_code->description = $request->description;
                $gen_code->serial_no = $request->serial_no;
                $gen_code->status = $request->status;
                $gen_code->updated_by = Auth::user()->user_id;
                if ($gen_code->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Gen Code update successfully.'
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
