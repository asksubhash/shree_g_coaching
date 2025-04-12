<?php

namespace App\Http\Controllers\superadmin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ResourceController extends Controller
{
    public function allResources()
    {
        $page_title = 'All Resources';
        return view('superadmin.all-resource', compact('page_title'));
    }

    public function getAllResourceList()
    {
        $query = Resource::query();
        $allResource = $query->orderBy('record_status', 'desc')->orderBy('id', 'desc');
        return DataTables::of($allResource)
            ->addColumn('action', function ($allResource) {
                //  <button class='btn btn-danger btn-sm deleteResourceBtn' id='" . base64_encode($allResource->id) . "' data-toggle='tooltip' data-placement='left' title='Delete Resource'><i class='fadeIn animated bx bx-trash'></i></button>
                $button = "<button class='btn btn-warning btn-sm editResourceBtn' id='" . base64_encode($allResource->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Resource'><i class='fadeIn animated bx bx-edit'></i></button>";
                return $button;
            })
            ->editColumn('record_status', function ($allResource) {
                $status = ($allResource->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->editColumn('is_maintenance', function ($allResource) {
                $status = ($allResource->is_maintenance == 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                return $status;
            })
            ->rawColumns(['record_status', 'action', 'is_maintenance'])
            ->make(true);
    }

    public function resourceDetails(Request $request)
    {
        $resource = Resource::select('id', 'resource_name', 'resource_link', 'is_maintenance', 'record_status')->find(base64_decode($request->id));
        if ($resource) {
            $output = [
                'status' => true,
                'data' => $resource
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return Response::json($output);
    }

    public function storeResource(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'link' => 'required',
            'is_maintenance' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $result = Resource::create([
                'resource_name' => $request->name,
                'resource_link' => $request->link,
                'is_maintenance' => $request->is_maintenance,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
            ]);
            // dd($result);
            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Resource created successfully.'
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

    public function updateResource(Request $request, Resource $resource)
    {
        $find = Resource::find(base64_decode($request->resource_id));
        if (!$find) {
            return Response::json([
                'status' => false,
                'message' => 'Resource not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'link' => 'required',
            'is_maintenance' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $find->resource_name = $request->name;
            $find->resource_link = $request->link;
            $find->is_maintenance = $request->is_maintenance;
            $find->record_status = $request->status;
            $find->updated_by = Auth::user()->user_id;
            $find->updated_on = Carbon::now();

            if ($find->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Resource updated successfully.'
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
    public function deleteResource(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Resource::find($id);
        if ($data->count() > 0) {
            DB::beginTransaction();
            $record_status = ($data->record_status == 1) ? 0 : 1;
            $data->record_status = $record_status;
            if ($data->save()) {
                DB::commit();
                $output = [
                    'status' => true,
                    'message' => 'Record updated successfully.'
                ];
            } else {
                DB::rollBack();
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
