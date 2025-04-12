<?php

namespace App\Http\Controllers\auth;

use Response;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\SendEmails;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerificationCode;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use SendEmails;

    public function index(){
        return view('register');
    }

    public function storeUser(Request $request){
        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email_id' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        if($validator->fails()){
            return Response::json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        else{

            // Attempt to login
            $data = [
                'user_id' => sha1($request->email_id),
                'name' => $request->name,
                'email' => $request->email_id,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'primary_role' => 'USER',
                'is_verified' => 0
            ];

            if(User::create($data)){

                $result = $this->sendVerificationCode($data);

                if($result){
                    $request->session()->put('user', [
                        'name' => $request->name,
                        'email' => $request->email_id,
                        'phone_number' => $request->phone_number,
                    ]);
                    $request->session()->put('verify_account', true);
    
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'redirect_to' => URL::to('/verify-user'),
                        'message' => 'Registeration done successfully. Please verify your account. You will receive a verification code on email id -> '.$request->email_id
                    ]);
                }
                else{
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Server failed to respond. Please try again.'
                    ]);
                }

            }
            else{
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Server failed to respond. Please try again.'
                ]);
            }
        }
    }

    public function showUserVerifyForm(Request $request){
        if($request->session()->get('verify_account')){
            return view('verify-user')->with(['pageTitle' => 'Verify User']);
        }
        else{
            return redirect('page-not-found');
        }
    }

    public function userVerify(Request $request){
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required',
            'email_id' => 'required|email'
        ]);

        if($validator->fails()){
            return Response::json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        else{

            // Attempt to login
            $verify = VerificationCode::where([
                'email_id' => $request->email_id,
                'code' => $request->verification_code
            ])->first();

            if($verify){
                // Update the users table
                $result = User::where([
                    'email' => $request->email_id
                ])->update([
                    'is_verified' => 1
                ]);

                if($result){
                    return Response::json([
                        'status' => true,
                        'redirect_to' => URL::to('/user/dashboard'),
                        'message' => 'Account verified successfully. Please login again.'
                    ]);
                }
                else{
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong. Please try again.'
                    ]);
                }
            }
            else{
                return Response::json([
                    'status' => false,
                    'message' => 'Incorrect code is provided. Please enter correct code.'
                ]);
            }
        }
    }
}
