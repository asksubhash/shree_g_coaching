<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Category;
use Auth;
use Response;
use App\Models\FileUpload;
use App\Models\UserDepartmentMapping;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $departments = UserDepartmentMapping::getUserMappedDepartmentCodes();
        $categories = Category::getDepartmentBasedCategories($departments);

        return view('file_uploads.all-file-uploads')->with([
            'page_title' => 'File Uploads',
            'categories' => $categories
        ]);
    }

    public function getAllForDatatable(Request $request)
    {
        $query = FileUpload::query();
        $query->select('*');
        $query->with('category');
        $query->where('record_status', 1);
        $allData = $query->orderBy('id', 'DESC');

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<button class='btn btn-warning btn-sm editFileUploadBtn' id='" . base64_encode($data->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Resource'><i class=' bx bx-edit'></i></button>";
                return $button;
            })
            ->addColumn('file_button', function ($data) {
                $imagePath = '';
                if ($data['file_type'] == 'IMAGE') {
                    $imagePath = Config::get('constants.files_storage_path')['IMAGE_VIEW_PATH'];
                }

                if ($data['file_type'] == 'PDF') {
                    $imagePath = Config::get('constants.files_storage_path')['PDF_VIEW_PATH'];
                }

                if ($data['file_type'] == 'WORD') {
                    $imagePath = Config::get('constants.files_storage_path')['WORD_VIEW_PATH'];
                }

                if ($data['file_type'] == 'EXCEL') {
                    $imagePath = Config::get('constants.files_storage_path')['EXCEL_VIEW_PATH'];
                }

                if ($data['file_type'] == 'PPT') {
                    $imagePath = Config::get('constants.files_storage_path')['PPT_VIEW_PATH'];
                }

                if ($imagePath) {
                    $button = "<a href='" . asset($imagePath . $data['file_name']) . "' class='btn btn-success btn-sm' data-toggle='tooltip' data-placement='left' title='View File'><i class=' bx bx-file'></i> View File</a>";
                    return $button;
                }

                return '';
            })
            ->addColumn('file_with_path', function ($data) {
                if ($data['file_type'] == 'IMAGE') {
                    $imagePath = Config::get('constants.files_storage_path')['IMAGE_VIEW_PATH'];
                    return asset($imagePath . $data['file_name']);
                }
                return '';
            })
            ->rawColumns(['record_status', 'action', 'file_button'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $rules = [
            'category' => 'required',
            'file_type' => 'required',
            'operation_type' => 'required'
        ];

        if ($request->file_type) {
            if ($request->file_type == 'IMAGE') {
                $rules['file'] = 'required|image';
            }
            if ($request->file_type == 'PDF') {
                $rules['file'] = 'required|mimes:pdf';
            }
            if ($request->file_type == 'WORD') {
                $rules['file'] = 'required|mimes:doc,docx';
            }
            if ($request->file_type == 'EXCEL') {
                $rules['file'] = 'required|mimes:xls,xlsx';
            }
            if ($request->file_type == 'PPT') {
                $rules['file'] = 'required|mimes:ppt,pptx';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $data = [
                'category_id' => $request->category,
                'file_type' => $request->file_type,
                'created_by' => Auth::user()->user_id,
                'updated_by' => Auth::user()->user_id
            ];

            if ($request->hasFile('file')) {

                // Check for the file type
                if ($request->file_type == 'IMAGE') {
                    // Check for the image type
                    $filePath = Config::get('constants.files_storage_path')['IMAGE_STORAGE_PATH'];
                }
                if ($request->file_type == 'PDF') {
                    // Check for the image type
                    $filePath = Config::get('constants.files_storage_path')['PDF_STORAGE_PATH'];
                }
                if ($request->file_type == 'WORD') {
                    // Check for the image type
                    $filePath = Config::get('constants.files_storage_path')['WORD_STORAGE_PATH'];
                }
                if ($request->file_type == 'EXCEL') {
                    // Check for the image type
                    $filePath = Config::get('constants.files_storage_path')['EXCEL_STORAGE_PATH'];
                }
                if ($request->file_type == 'PPT') {
                    // Check for the image type
                    $filePath = Config::get('constants.files_storage_path')['PPT_STORAGE_PATH'];
                }

                // UPLOAD FILE =============================

                // CUSTOM TRAIT: Using the trait function to upload the file
                $uploadResult  = $this->uploadSingleFile($request->file, $filePath, true);

                // Check if there is any error
                if ($uploadResult['status'] == false) {
                    return Response::json($uploadResult);
                }

                // Set the file name into the insert data array
                $data['file_name'] = $uploadResult['filename'];
            } else {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'File is required.'
                ]);
            }

            $result = FileUpload::create($data);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'File data saved successfully.'
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'file_type' => 'required',
            'operation_type' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {

            DB::beginTransaction();

            // Check if the record is available
            $fileUpload = FileUpload::where('code', $request->file_type_code)->first();

            if (!$fileUpload) {
                return Response::json([
                    'status' => false,
                    'message' => 'Data not found, please check your data and try again.'
                ]);
            }

            $result = FileUpload::where('code', $request->file_type_code)->update([
                'category' => $request->category,
                'file_type' => $request->file_type,
            ]);



            if ($result) {

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'File data updated successfully.'
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

    // Function to delete
    public function deleteUser(Request $request)
    {
        $id = base64_decode($request->id);
        $data = User::find($id);
        if ($data->count() > 0) {
            $result = User::where(['id' => $id])->update([
                'status' => 0,
                'last_updated_by' => Auth::user()->email
            ]);

            if ($result) {
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
                'message' => 'User not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }
}
