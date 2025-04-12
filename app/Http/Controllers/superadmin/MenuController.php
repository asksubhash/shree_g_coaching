<?php

namespace App\Http\Controllers\superadmin;

use Carbon\Carbon;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Resource;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function allMenu()
    {
        $page_title = 'All Menu';
        $resources = Resource::where('record_status', 1)->get(['id', 'resource_name']);
        $roles = Role::where('status', 1)->get(['role_code', 'role_name']);

        return view('superadmin.all-menu', compact('page_title', 'resources', 'roles'));
    }

    public function getAllMenuList(Request $request)
    {
        $query = Menu::query();
        $query->select('menus.*');
        $query->where('menus.role_code', $request->role_code);
        $allMenus = $query->orderBy('menus.sl_no', 'ASC');

        return DataTables::of($allMenus)
            ->addColumn('action', function ($menu) {
                $button = "<button class='btn btn-warning btn-sm editMenuBtn' id='" . base64_encode($menu->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Resource'><i class=' bx bx-edit'></i></button>";
                return $button;
            })
            ->addColumn('resource_name', function ($menu) {
                return (isset($menu->resource->resource_name)) ? $menu->resource->resource_name : $menu->resource_id;
            })
            ->editColumn('record_status', function ($menu) {
                $status = ($menu->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['record_status', 'action'])
            ->make(true);
    }

    public function getParentMenu(Request $request)
    {
        $role_code = $request->role_code;
        if ($role_code) {
            $query = Menu::where(['record_status' => 1, 'role_code' => $role_code]);
            $menus = $query->get(['id', 'menu_name']);

            if ($menus) {
                $output = [
                    'status' => true,
                    'data' => $menus
                ];
            } else {
                $output = [
                    'status' => false,
                    'message' => 'Something went wrong. Please try again or contact support team.'
                ];
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'Please select role.'
            ];
        }

        return Response::json($output);
    }

    public function storeMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_code' => 'required',
            'menu' => 'required',
            'menu_icon' => 'required',
            'resource' => 'required',
            'menu_order' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {


            DB::beginTransaction();

            $result = Menu::create([
                'role_code' => $request->role_code,
                'menu_name' => $request->menu,
                'resource_id' => ($request->resource == '#') ? 0 : $request->resource,
                'sl_no' => $request->menu_order,
                'icon_class' => $request->menu_icon,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id
            ]);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Menu created successfully.'
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

    public function menuDetails(Request $request)
    {
        $resource = Menu::select('id', 'menu_name', 'resource_id', 'sl_no', 'icon_class', 'record_status')->find(base64_decode($request->id));
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

    public function updateMenu(Request $request, Menu $menu)
    {
        $find = Menu::find(base64_decode($request->menu_id));
        if (!$find) {
            return Response::json([
                'status' => false,
                'message' => 'Menu not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'menu' => 'required',
            'menu_icon' => 'required',
            'resource' => 'required',
            'menu_order' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            $find->menu_name = $request->menu;
            $find->icon_class = $request->menu_icon;
            $find->resource_id = $request->resource;
            $find->sl_no = $request->menu_order;
            $find->record_status = $request->status;
            $find->updated_by = Auth::user()->user_id;
            $find->updated_on = Carbon::now();
            if ($find->save()) {
                return Response::json([
                    'status' => true,
                    'message' => 'Menu updated successfully.'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }
}
