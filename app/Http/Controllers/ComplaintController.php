<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Carbon\Carbon;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('complaints.index', ['page_title' => 'Complaints/Queries']);
    }

    function fetchForStudentDatatable()
    {

        $complaint = Complaint::where('user_id', Auth::user()->user_id)->orderBy('id', 'DESC')->get();
        return DataTables::of($complaint)
            ->addColumn('action', function ($complaint) {
                $button = "<button class='btn btn-danger btn-sm deleteComplaintBtn' id='" . $complaint->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($complaint) {
                $status = ($complaint->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->addColumn('complaint_status_desc', function ($complaint) {
                $status = '';
                if ($complaint->complaint_status == 0) {
                    $status = '<span class="badge bg-primary">Pending</span>';
                }
                if ($complaint->complaint_status == 1) {
                    $status = '<span class="badge bg-success">Resolved</span>';
                }
                if ($complaint->complaint_status == 2) {
                    $status = '<span class="badge bg-danger">Rejected</span>';
                }
                return $status;
            })
            ->addColumn('complaint_document_button', function ($data) {
                $status = ($data->complaint_document) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['COMPLAINT_DOCUMENT_VIEW_PATH'] . '/' . $data->complaint_document) . "' class='btn btn-success btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'><i class='bx bx-file'></i></a>" : '<span class="badge bg-danger">N/A</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'complaint_document_button', 'complaint_status_desc'])
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     */
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_title' => 'required|string',
            'complaint_description' => 'required|string',
            'complaint_document' => 'mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $complaintData = [
                'user_id' => Auth::user()->user_id,
                'complaint_title' => $request->complaint_title,
                'complaint_description' => $request->complaint_description,
                'complaint_date' => Carbon::now()->toDateString(),
                'complaint_status' => 0,
                'record_status' => 1,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['COMPLAINT_DOCUMENT_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('complaint_document')) {
                $complaint_document = $this->uploadSingleFile($request->complaint_document, $filePath, true);
                $complaintData['complaint_document'] =  $complaint_document['filename'];
            }

            $course = Complaint::create($complaintData);

            if ($course->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Complaint submitted successfully. We will reach you as soon as possible'
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Complaint::where(['id' => $id])->first();
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

    /**
     * Display a listing of the admin student complaints.
     */
    public function adminAllStudentComplaints()
    {
        return view('complaints.admin_student_complaints', ['page_title' => 'Students Complaints/Queries']);
    }

    function fetchForAdminAllStudentCompDatatable()
    {

        $complaint = Complaint::select('complaints.*', 'auth.username', 'st.roll_number', 'st.name as student_name')
            ->leftJoin('authentications as auth', 'auth.user_id', '=', 'complaints.user_id')
            ->leftJoin('students as st', 'st.roll_number', '=', 'auth.username')
            ->orderBy('id', 'DESC');

        return DataTables::of($complaint)
            ->addColumn('action', function ($complaint) {
                $button = "";
                // $button .= "<button class='btn btn-danger btn-sm deleteComplaintBtn' id='" . $complaint->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($complaint) {
                $status = ($complaint->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->addColumn('complaint_status_desc', function ($complaint) {
                $status = '';
                if ($complaint->complaint_status == 0) {
                    $status = '<span class="badge bg-primary">Pending</span>';
                }
                if ($complaint->complaint_status == 1) {
                    $status = '<span class="badge bg-success">Resolved</span>';
                }
                if ($complaint->complaint_status == 2) {
                    $status = '<span class="badge bg-danger">Rejected</span>';
                }
                return $status;
            })
            ->addColumn('complaint_document_button', function ($data) {
                $status = ($data->complaint_document) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['COMPLAINT_DOCUMENT_VIEW_PATH'] . '/' . $data->complaint_document) . "' class='btn btn-success btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'><i class='bx bx-file'></i></a>" : '<span class="badge bg-danger">N/A</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'complaint_document_button', 'complaint_status_desc'])
            ->make(true);
    }
}
