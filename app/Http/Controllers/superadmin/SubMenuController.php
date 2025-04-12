<?php

namespace App\Http\Controllers\superadmin;

use Carbon\Carbon;

use App\Models\Menu;
use App\Models\Role;
use App\Models\SubMenu;
use App\Models\Resource;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SubMenuController extends Controller
{
    public function allMenu()
    {
        $page_title = 'All Sub Menus';

        $resources = Resource::where('record_status', 1)->get(['id', 'resource_name']);
        $roles = Role::where('status', 1)->get(['role_code', 'role_name']);

        return view('superadmin.all-submenu', compact('page_title', 'resources', 'roles'));
    }

    public function getAllMenuList(Request $request)
    {
        $query = SubMenu::leftJoin('menus', 'menus.id', '=', 'sub_menus.menu_id');
        $query->leftJoin('resources', 'resources.id', '=', 'sub_menus.resource_id');
        $query->select('sub_menus.*', 'menus.menu_name', 'resources.resource_name');
        $query->where('sub_menus.role_code', $request->role_code);
        $allMenus = $query->orderBy('menus.record_status', 'desc')->orderBy('menus.id', 'desc');

        return DataTables::of($allMenus)
            ->addColumn('action', function ($allMenus) {
                $button = "<button class='btn btn-warning btn-sm editMenuBtn' id='" . base64_encode($allMenus->id) . "' data-toggle='tooltip' data-placement='left' title='Edit Resource'><i class='bx bx-edit'></i></button>";
                return $button;
            })
            ->editColumn('record_status', function ($allMenus) {
                $status = ($allMenus->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['record_status', 'action', 'has_child', 'parent_menu'])
            ->make(true);
    }


    public function storeMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_code' => 'required',
            'parent_menu' => 'required',
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
            $result = SubMenu::create([
                'role_code' =>  $request->role_code,
                'menu_id' => $request->parent_menu,
                'sub_menu_name' => $request->menu,
                'resource_id' => $request->resource,
                'sl_no' => $request->menu_order,
                'icon_class' => $request->menu_icon,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id
            ]);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Sub Menu created successfully.'
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

    public function subMenuDetails(Request $request)
    {
        $resource = SubMenu::select('*')->find(base64_decode($request->id));
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
        $find = SubMenu::find(base64_decode($request->menu_id));
        if (!$find) {
            return Response::json([
                'status' => false,
                'message' => 'Menu not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'parent_menu' => 'required',
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
            $find->menu_id = $request->parent_menu;
            $find->sub_menu_name = $request->menu;
            $find->resource_id = $request->resource;
            $find->icon_class = $request->menu_icon;
            $find->sl_no = $request->menu_order;
            $find->record_status = $request->status;
            $find->updated_by = Auth::user()->user_id;
            $find->updated_at = Carbon::now();

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
