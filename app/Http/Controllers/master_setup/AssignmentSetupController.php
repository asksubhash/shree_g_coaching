<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Models\Institute;
use App\Models\Assignment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AdmissionSession;
use App\Models\ClassMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AssignmentSetupController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $academic_query = AcademicYear::select('academic_year', 'id');
        $classes_query = ClassMaster::select('name', 'id')->where('record_status', 1);
        $admission_session_query = AdmissionSession::select('session_name', 'id')->where('record_status', 1);
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $academic_query->where('institute_id', $instituteId);
            $classes_query->where('institute_id', $instituteId);
            $admission_session_query->where('institute_id', $instituteId);
        }
        $classes = $classes_query->get();
        $academic_years = $academic_query->where('record_status', 1)->get();
        $institutes = Institute::getInstitutes();
        $admission_session = $admission_session_query->get();
        return view('master_setup.assignments_setup', [
            'page_title' => 'Assignments Setup',
            'institutes' => $institutes,
            'academic_years' => $academic_years,
            'classes' => $classes,
            'admission_sessions' => $admission_session
        ]);
    }


    function fetchForDatatable(Request $request)
    {

        $query = Assignment::select(
            'assignments.*',
            'ins.name as institute_name',
            'cls.name as class_name',
            'ay.academic_year as academic_year',
            'ads.session_name as admission_session',
            's.name as subject_name',
        );

        $query->leftJoin('institutes as ins', 'ins.id', '=', 'assignments.institute_id');
        $query->leftJoin('class_masters as cls', 'cls.id', '=', 'assignments.class_id');
        $query->leftJoin('academic_years as ay', 'assignments.academic_year_id', '=', 'ay.id');
        $query->leftJoin('admission_sessions as ads', 'assignments.admission_session_id', '=', 'ads.id');
        $query->leftJoin('subjects as s', function ($join) {
            $join->on('s.id', '=', 'assignments.subject_id');
        });

        if ($request->filter_institute) {
            $query->where('assignments.institute_id', $request->filter_institute);
        }
        // if (Auth::user()->role?->role_code === "INS_HEAD") {
        //     $instituteId = AppHelper::getCurrentUserInstituteId();
        //     $query->where('assignments.institute_id', $instituteId);
        // }

        $query->orderBy('assignments.id', 'DESC');

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<button class='btn btn-warning btn-sm editAssignmentsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteAssignmentsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('document_button', function ($data) {
                $status = ($data->document) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['ASSIGNMENTS_VIEW_PATH'] . '/' . $data->document) . "' class='btn btn-success btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'><i class='bx bx-file'></i></a>" : '<span class="badge bg-danger">N/A</span>';
                return $status;
            })
            ->addColumn('status_desc', function ($data) {
                $status = ($data->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'document_button'])
            ->make(true);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required',
            'institute_id' => 'required',
            'class_id' => 'required',
            'admission_session_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required',
            'document' => 'required|mimes:pdf|max:10240',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $instituteId = $request->institute_id;
            if (Auth::user()->role?->role_code === "INS_HEAD") {
                $instituteId = AppHelper::getCurrentUserInstituteId();
            }
            $assignmentsData = [
                'institute_id' => $instituteId,
                'academic_year_id' => $request->academic_year_id,
                'class_id' => $request->class_id,
                'admission_session_id' => $request->admission_session_id,
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['ASSIGNMENTS_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('document')) {
                $document = $this->uploadSingleFile($request->document, $filePath, true);
                $assignmentsData['document'] =  $document['filename'];
            }

            $result = Assignment::create($assignmentsData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Assignment added successfully.'
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
     * Function to fetch the single details
     */
    function fetchSingleDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Assignment::where(['id' => $id])->select('*')->first();
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
     * Function to update the details
     */
    function update(Request $request)
    {
        $id = base64_decode($request->assignments_id);
        $admSession = Assignment::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'Assignment data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'academic_year_id' => 'required',
                'class_id' => 'required',
                'admission_session_id' => 'required',
                'subject_id' => 'required',
                'title' => 'required',
                'status' => 'required',
                'document' => 'mimes:pdf|max:10240',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                try {
                    // Update course details
                    $instituteId = $request->institute_id;
                    if (Auth::user()->role?->role_code === "INS_HEAD") {
                        $instituteId = AppHelper::getCurrentUserInstituteId();
                    }
                    $assignmentsData = [
                        'institute_id' => $instituteId,
                        'academic_year_id' => $request->academic_year_id,
                        'class_id' => $request->class_id,
                        'admission_session_id' => $request->admission_session_id,
                        'subject_id' => $request->subject_id,
                        'title' => $request->title,
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ];

                    $filePath = Config::get('constants.files_storage_path')['ASSIGNMENTS_UPLOAD_PATH'];
                    // CUSTOM TRAIT: Using the trait function to upload the file
                    if ($request->file('document')) {
                        $document = $this->uploadSingleFile($request->document, $filePath, true);
                        $assignmentsData['document'] =  $document['filename'];
                    }

                    $result = Assignment::where('id', $id)->update($assignmentsData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Assignment updated successfully.'
                        ]);
                    } else {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Server is not responding. Please try again.'
                        ]);
                    }
                } catch (\Exception $e) {
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
     * Function to delete the details
     */
    function delete(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Assignment::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = 0;
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
