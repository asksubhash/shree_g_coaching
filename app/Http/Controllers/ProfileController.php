<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function profile(){
        return view('user.profile', [
            'page_title' => 'Profile',
            'page_heading' => 'Profile'
        ]);   
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ],[
            'password.required' => 'New password field is required.'
        ]);

        if($validator->fails()){
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        }
        else{

            if(!Hash::check($request->old_password, Auth::user()->password)){
                return Response::json([
                    'status' => false,
                    'message' => 'Old password is incorrect.'
                ]);
            }

            $result = User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->password)
            ]);

            if($result){
                return Response::json([
                    'status' => true,
                    'message' => 'Password changed successfully.'
                ]);
            }
            else{
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'profile_name' => 'required'
        ]);

        if($validator->fails()){
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        }
        else{

            $result = User::where('id', Auth::user()->id)->update([
                'name' => $request->profile_name,
                'phone_number' => $request->profile_phone
            ]);

            if($result){
                return Response::json([
                    'status' => true,
                    'message' => 'Profile updated successfully.'
                ]);
            }
            else{
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }
}