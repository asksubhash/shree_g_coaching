<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AcademicYear;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicYearSetupController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        return view('master_setup.academic_year_setup', [
            'page_title' => 'AcademicYears Setup'
        ]);
    }


    function fetchForDatatable(Request $request)
    {
        $query = AcademicYear::select(
            'academic_years.*'
        );
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $query->where('institute_id', $instituteId);
        }
        if ($request->institute) {
            $query->where('institute_id', $request->institute);
        }
        $query->orderBy('academic_years.academic_year', 'DESC');
        $allData = $query->get();
        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<button class='btn btn-warning btn-sm editAcademicYearsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteAcademicYearsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('active_status_desc', function ($data) {
                $status = ($data->active_status == 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                return $status;
            })
            ->addColumn('status_desc', function ($data) {
                $status = ($data->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'active_status_desc'])
            ->make(true);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required',
            'active_status' => 'required',
            'status' => 'required',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $ayData = [
                'institute_id' => $instituteId,
                'academic_year' => $request->academic_year,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active_status' => $request->active_status,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $result = AcademicYear::create($ayData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Academic Year added successfully.'
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
        $details = AcademicYear::where(['id' => $id])->select('*')->first();
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
        $id = base64_decode($request->academic_years_id);
        $admSession = AcademicYear::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'AcademicYear data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'academic_year' => 'required',
                'active_status' => 'required',
                'start_date'=>'nullable|date',
                'end_date'=>'nullable|date',
                'status' => 'required',
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

                    $ayData = [
                        'academic_year' => $request->academic_year,
                        'active_status' => $request->active_status,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ];

                    $result = AcademicYear::where('id', $id)->update($ayData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Academic Year updated successfully.'
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
        $data = AcademicYear::where(['id' => $id])->first();
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
