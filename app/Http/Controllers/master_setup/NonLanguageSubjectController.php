<?php

namespace App\Http\Controllers\master_setup;

use Auth;
use Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\NonLanguageSubject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NonLanguageSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master_setup.subject_setup.non_language_subjects', ['page_title' => 'Non Language Subject Setup']);
    }

    function getSubjectList()
    {

        $subject = NonLanguageSubject::orderBy('id', 'DESC')->get();
        return DataTables::of($subject)
            ->addColumn('action', function ($subject) {
                $button = "<button class='btn btn-warning btn-sm editSubjectBtn' id='" . $subject->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteSubjectBtn' id='" . $subject->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($subject) {
                $status = ($subject->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
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
            'subject_code' => 'required|string',
            'subject_name' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $subject = new NonLanguageSubject();
            $subject->code = $request->subject_code;
            $subject->name = $request->subject_name;
            $subject->record_status = $request->status;
            $subject->created_by = Auth::user()->user_id;
            $subject->created_at = now();

            if ($subject->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Subject added successfully.'
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
        $details = NonLanguageSubject::where(['id' => $id])->select('id', 'name', 'record_status')->first();
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
        $id = base64_decode($request->subject_id);
        $subject = NonLanguageSubject::find($id);
        if (!$subject) {
            return Response::json([
                'status' => false,
                'message' => 'Subject not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'subject_code' => 'required|string',
                'subject_name' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $subject->code = $request->subject_code;
                $subject->name = $request->subject_name;
                $subject->record_status = $request->status;
                $subject->updated_by = Auth::user()->user_id;
                $subject->updated_at = now();
                if ($subject->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Subject Updated successfully.'
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
        $data = NonLanguageSubject::where(['id' => $id])->first();
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
