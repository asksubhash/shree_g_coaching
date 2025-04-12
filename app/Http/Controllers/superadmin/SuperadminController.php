<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        $roles = Role::where('status', 1)->get();
        return view('superadmin.dashboard', ['roles' => $roles]);
    }
    public function updateProfileDetail(Request $request)
    {

        // dd(Auth::user()->user_id);
        $user = User::find(Auth::user()->UserDetail->id);
        if (!$user) {
            return Response::json([
                'status' => false,
                'message' => 'User not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'profile_f_name' => 'required|string',
            'profile_l_name' => 'required|string',
            'profile_phone' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            $user->f_name = $request->profile_f_name;
            $user->l_name = $request->profile_l_name;
            $user->mobile_no = $request->profile_phone;
            if ($user->save()) {
                return Response::json([
                    'status' => true,
                    'message' => 'Profile updated successfully.'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }

    public function changePassword(Request $request)
    {

        // dd(Auth::user()->user_id);
        $user = Authentication::find(Auth::user()->id);
        if (!$user) {
            return Response::json([
                'status' => false,
                'message' => 'User not found, please check your user or contact support team'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'password' => 'required|different:old_password',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                return Response::json([
                    'status' => true,
                    'message' => 'Password changed successfully.'
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
