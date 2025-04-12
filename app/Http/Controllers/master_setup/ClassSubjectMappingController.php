<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\ClassMaster;
use App\Models\ClassSubjectMapping;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\select;

class ClassSubjectMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instituteId = AppHelper::getCurrentUserInstituteId();
        $classes = ClassMaster::where('record_status', 1)->where('institute_id', $instituteId)->get(['id', 'name']);
        $subjects = Subject::where('record_status', 1)->where('institute_id', $instituteId)->get(['id', 'name']);
        $page_title = "Class Subject Mapping";
        return view('master_setup.class_subject_mapping', compact('classes', 'subjects', 'page_title'));
    }

    function getDataTableList(Request $request)
    {

        $query = ClassSubjectMapping::leftJoin('class_masters as a', 'class_subject_mappings.class_id', '=', 'a.id')->leftJoin('subjects as b', 'class_subject_mappings.subject_id', '=', 'b.id')->select('a.name as class_name', 'b.name as subject_name', 'class_subject_mappings.id', 'class_subject_mappings.record_status');
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $query->where('class_subject_mappings.institute_id', $instituteId);
        }
        if ($request->institute) {
            $query->where('class_subject_mappings.institute_id', $request->institute);
        }
        $lists = $query->orderBy('class_subject_mappings.id', 'DESC');
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
            'class' => 'required|string',
            'subject' => 'required|string',
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
            $class = new ClassSubjectMapping();
            $class->institute_id = $instituteId;
            $class->class_id = $request->class;
            $class->subject_id = $request->subject;
            $class->record_status = $request->status;
            $class->created_by = Auth::user()->user_id;
            $class->created_at = now();

            if ($class->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Mapping added successfully.'
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
        $details = ClassSubjectMapping::where(['id' => $id])->select('id', 'institute_id', 'class_id', 'subject_id', 'record_status')->first();
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
        $id = base64_decode($request->mapping_id);
        $class = ClassSubjectMapping::find($id);
        if (!$class) {
            return Response::json([
                'status' => false,
                'message' => 'Mapping not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'mapping_id' => 'required|string',
                'class' => 'required',
                'subject' => "required",
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $class->class_id = $request->class;
                $class->subject_id = $request->subject;
                $class->record_status = $request->status;
                $class->updated_by = Auth::user()->user_id;
                $class->updated_at = now();
                if ($class->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Mapping Updated successfully.'
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
        $data = ClassSubjectMapping::where(['id' => $id])->first();
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



    public function fetchSubject(Request $request)
    {
        $id = base64_decode($request->class_id);
        $query = ClassSubjectMapping::leftJoin('class_masters as a', 'class_subject_mappings.class_id', '=', 'a.id')
            ->leftJoin('subjects as b', 'class_subject_mappings.subject_id', '=', 'b.id')
            ->select('b.name as subject_name', 'b.id as subject_id')
            ->where('a.id', $id);
        $details = $query->get();
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
}
