<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Models\Institute;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\AdmissionSession;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AdmissionSessionController extends Controller
{
    public function index()
    {
        $academic_query = AcademicYear::select('academic_year', 'id');
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $academic_query->where('institute_id', $instituteId);
        }
        $academic_years = $academic_query->where('record_status', 1)->get();
        $institutes = Institute::getInstitutes();

        return view('master_setup.admission_session_setup', [
            'page_title' => 'Admission Session Setup',
            'institutes' => $institutes,
            'academic_years' => $academic_years
        ]);
    }


    function fetchForDatatable(Request $request)
    {

        $query = AdmissionSession::select('admission_sessions.*', 'ins.name as institute_name');
        $query->leftJoin('institutes as ins', 'ins.id', '=', 'admission_sessions.institute_id');
        if ($request->filter_institute) {
            $query->where('admission_sessions.institute_id', $request->filter_institute);
        }

        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $query->where('admission_sessions.institute_id', $instituteId);
        }

        $query->orderBy('admission_sessions.id', 'DESC');
        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($course) {
                $button = "<button class='btn btn-warning btn-sm editCourseBtn' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteCourseBtn' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($course) {
                $status = ($course->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required',
            'session_name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required'
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

            $result = AdmissionSession::create([
                'academic_year_id' => $request->academic_year_id,
                'institute_id' => $instituteId,
                'session_name' => $request->session_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ]);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Admission Session added successfully.'
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
        $details = AdmissionSession::where(['id' => $id])->select('*')->first();
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
        $id = base64_decode($request->admission_session_id);
        $admSession = AdmissionSession::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'Admission session data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'academic_year_id' => 'required',
                'session_name' => 'required',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'status' => 'required'
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
                    $result = AdmissionSession::where('id', $id)->update([
                        'academic_year_id' => $request->academic_year_id,
                        'institute_id' => $instituteId,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'session_name' => $request->session_name,
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ]);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Admission Session updated successfully.'
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
        $data = AdmissionSession::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = 0;
            $data->updated_by = Auth::user()->user_id;
            $data->updated_on = now();

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

    // no need 
    function getUsingCourseinsituteAcademicYear(Request $request)
    {

        $query = AdmissionSession::select('admission_sessions.*', 'ins.name as institute_name', 'c.course_name', 'c.course_code');
        $query->leftJoin('institutes as ins', 'ins.id', '=', 'admission_sessions.institute_id');
        $query->leftJoin('courses as c', 'c.id', '=', 'admission_sessions.course_id');

        $query->where('admission_sessions.academic_year_id', $request->academic_year_id);
        $query->where('admission_sessions.institute_id', $request->institute_id);
        $query->where('admission_sessions.course_id', $request->course_id);

        $query->orderBy('admission_sessions.id', 'DESC');

        $allData = $query->get();

        return Response::json([
            'status' => true,
            'message' => '',
            'data' => $allData
        ]);
    }
}
