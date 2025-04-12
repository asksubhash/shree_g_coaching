<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Models\Institute;
use Illuminate\Http\Request;
use App\Models\StudyMaterial;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ClassMaster;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudyMaterialSetupController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $institutes = Institute::where('record_status', 1)->get();
        $classes_query = ClassMaster::select('name', 'id')->where('record_status', 1);
        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $classes_query->where('institute_id', $instituteId);
        }

        $classes = $classes_query->get();
        return view('master_setup.study_material_setup', [
            'page_title' => 'Study Material Setup',
            'institutes' => $institutes,
            'classes' => $classes,
        ]);
    }


    function fetchForDatatable(Request $request)
    {

        $query = StudyMaterial::select('study_materials.*', 'ins.name as institute_name', 'cls.name as class_name', 's.name as subject_name');
        $query->leftJoin('institutes as ins', 'ins.id', '=', 'study_materials.institute_id');
        $query->leftJoin('class_masters as cls', 'cls.id', '=', 'study_materials.class_id');
        $query->leftJoin('subjects as s', function ($join) {
            $join->on('s.id', '=', 'study_materials.subject_id');
        });

        if (Auth::user()->role?->role_code === "INS_HEAD") {
            $instituteId = AppHelper::getCurrentUserInstituteId();
            $query->where('study_materials.institute_id', $instituteId);
        }

        if ($request->filter_institute) {
            $query->where('study_materials.institute_id', $request->filter_institute);
        }
        if ($request->filter_class) {
            $query->where('study_materials.class_id', $request->filter_class);
        }
        $query->orderBy('study_materials.id', 'DESC');
        $allData = $query->get();
        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<button class='btn btn-warning btn-sm editStudyMaterialBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteStudyMaterialBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('document_button', function ($data) {
                $status = ($data->document) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['STUDY_MATERIAL_VIEW_PATH'] . '/' . $data->document) . "' class='btn btn-success btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'><i class='bx bx-file'></i></a>" : '<span class="badge bg-danger">N/A</span>';
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
            'class_id' => 'required',
            'subject_id' => 'required',
            'status' => 'required',
            'document' => 'required|mimes:pdf|max:307200',
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

            $studyMaterialData = [
                'institute_id' => $instituteId,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['STUDY_MATERIAL_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('document')) {
                $document = $this->uploadSingleFile($request->document, $filePath, true);
                $studyMaterialData['document'] =  $document['filename'];
            }

            $result = StudyMaterial::create($studyMaterialData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Study Material added successfully.'
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
        $details = StudyMaterial::where(['id' => $id])->select('*')->first();
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
        $id = base64_decode($request->study_material_id);
        $admSession = StudyMaterial::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'Study Material data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required',
                'subject_id' => 'required',
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
                    $instituteId = $request->institute_id;
                    if (Auth::user()->role?->role_code === "INS_HEAD") {
                        $instituteId = AppHelper::getCurrentUserInstituteId();
                    }
                    $studyMaterialData = [
                        'institute_id' =>   $instituteId,
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'title' => $request->title,
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ];

                    $filePath = Config::get('constants.files_storage_path')['STUDY_MATERIAL_UPLOAD_PATH'];
                    // CUSTOM TRAIT: Using the trait function to upload the file
                    if ($request->file('document')) {
                        $document = $this->uploadSingleFile($request->document, $filePath, true);
                        $studyMaterialData['document'] =  $document['filename'];
                    }

                    $result = StudyMaterial::where('id', $id)->update($studyMaterialData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Study Material updated successfully.'
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
        $data = StudyMaterial::where(['id' => $id])->first();
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
