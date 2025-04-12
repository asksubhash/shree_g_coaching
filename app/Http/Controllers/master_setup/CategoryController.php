<?php

namespace App\Http\Controllers\master_setup;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use App\Models\Department;
use Auth;
use Response;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    function index()
    {
        $department = Department::all()->where('record_status', 1);
        $page_title = 'Category Setup';
        return view('master_setup.category_setup', compact('page_title', 'department'));
    }

    function getCategoryList()
    {

        // $categoryList = Category::orderBy('id', 'desc')->where('record_status',1)->get();
        $categoryList = DB::table('categories')
            ->select('categories.id', 'categories.category_name', 'categories.record_status', 'departments.department_name')
            ->leftJoin('departments', 'categories.department_id', '=', 'departments.id')
            ->where('categories.record_status', 1)
            ->get();
        return DataTables::of($categoryList)
            ->addColumn('action', function ($categoryList) {
                $button = "<button class='btn btn-warning btn-sm editCategoryBtn' id='" . $categoryList->id . "' data-toggle='tooltip' data-placement='left' title='Edit Category'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteCategoryBtn' id='" . $categoryList->id . "' data-toggle='tooltip' data-placement='left' title='Delete Category'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($categoryList) {
                $status = ($categoryList->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'category' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = Category::create([
                'category_name' => $request->category,
                'department_id' => $request->department,
                'created_by' => Auth::user()->user_id
            ]);
            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Add Category successfully.'
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

    // Delete Category Function 
    function deleteCategory(Request $request)
    {
        $id = $request->id;
        $data = Category::where(['id' => $id])->first();

        if ($data) {
            $result = Category::where(['id' => $id])->update([
                'record_status' => 0,
                'updated_by' => Auth::user()->user_id
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
                'message' => 'Category not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }


    // Function for get details of category for update
    function getDetails(Request $request)
    {
        $id = $request->id;
        $details = Category::where(['id' => $id])->first();
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
    function updateCategory(Request $request)
    {
        $id = base64_decode($request->hidden_id);
        $category = Category::find($id);
        if (!$category) {
            return Response::json([
                'status' => false,
                'message' => 'Category not found, please contact support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'department' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                $category->category_name = $request->category;
                $category->department_id = $request->department;
                $category->updated_by = Auth::user()->user_id;
                if ($category->save()) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Update Category successfully.'
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
