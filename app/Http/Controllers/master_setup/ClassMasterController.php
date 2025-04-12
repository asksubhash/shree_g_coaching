<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\ClassMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClassMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master_setup.class_setup', ['page_title' => 'Class Setup']);
    }

    function getSubjectList(Request $request)
    {

        $query = ClassMaster::query();
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $query->where('institute_id', $instituteId);
        }
        if ($request->institute) {
            $query->where('institute_id', $request->institute);
        }
        $lists = $query->orderBy('id', 'DESC');
        return DataTables::of($lists)
            ->addColumn('action', function ($list) {
                $button = "<button class='btn btn-warning btn-sm editClassBtn' id='" . $list->id . "' data-toggle='tooltip' data-placement='left' title='Edit Class'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteClassBtn' id='" . $list->id . "' data-toggle='tooltip' data-placement='left' title='Delete Class'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($list) {
                $status = ($list->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            DB::beginTransaction();
            $class = new ClassMaster();
            $class->institute_id = $instituteId;
            $class->name = $request->class_name;
            $class->description = $request->description;
            $class->difficulty_level = $request->difficulty_level;
            $class->record_status = $request->status;
            $class->created_by = Auth::user()->user_id;
            $class->created_at = now();

            if ($class->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Class added successfully.'
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
    public function edit(Request $request)
    {
        $id = base64_decode($request->id);
        $details = ClassMaster::where(['id' => $id])->select('institute_id', 'id', 'name', 'description', 'difficulty_level', 'record_status')->first();
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = base64_decode($request->class_id);
        $class = ClassMaster::find($id);
        if (!$class) {
            return Response::json([
                'status' => false,
                'message' => 'Class not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string',
                'class_name' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $class->name = $request->class_name;
                $class->description = $request->description;
                $class->difficulty_level = $request->difficulty_level;
                $class->record_status = $request->status;
                $class->updated_by = Auth::user()->user_id;
                $class->updated_at = now();
                if ($class->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Class Updated successfully.'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = base64_decode($request->id);
        $data = ClassMaster::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = $data->record_status == 1 ? 0 : 1;
            $data->updated_by = Auth::user()->user_id;
            $data->updated_at = now();

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
}
