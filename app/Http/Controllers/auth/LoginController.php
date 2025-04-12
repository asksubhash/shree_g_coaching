<?php

namespace App\Http\Controllers\auth;

use Auth;
use Response;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\MenuTrait;
use App\Traits\SendEmails;
use App\Models\LoginDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Authentication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use MenuTrait;
    use SendEmails;

    public function index()
    {
        return view('login');
    }


    public function checkLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_id' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ], [
            'captcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_error',
                'message' => $validator->errors()->all()
            ]);
        } else {
            // Auth::attempt(['username' => $request->email_id, 'password' => $request->password, 'record_status' => 1])
            if (Auth::attempt(['username' => $request->email_id, 'password' => $request->password, 'record_status' => 1])) {
                if (Auth::user()->userDetail->is_verified) {

                    $landing_page_link = $this->getLandingPage(Auth::user()->role_code);
                    $redirect_to = URL::to($landing_page_link);

                    LoginDetail::create([
                        'user_id' => Auth::user()->user_id,
                        'login_datetime' => Carbon::now(),
                        'ip_address' => $request->ip(),
                        'current_status' => 'Login successfully',
                        'status' => 'Logged In',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    return Response::json([
                        'status' => true,
                        'redirect_to' => $redirect_to,
                        'message' => 'Login Successfully'
                    ]);
                } else {
                    // $request->session()->put('user', [
                    //     'name' => Auth::user()->userDetail->f_name . ' ' . Auth::user()->userDetail->l_name,
                    //     'email' => Auth::user()->userDetail->email_id,
                    //     'mobile_no' => Auth::user()->userDetail->mobile_no
                    // ]);
                    // $request->session()->put('verify_account', true);

                    // $data = [
                    //     'user_id' => Auth::user()->userDetail->user_id,
                    //     'name' => Auth::user()->userDetail->f_name . ' ' . Auth::user()->userDetail->l_name,
                    //     'email' => Auth::user()->userDetail->email_id,
                    //     'mobile_no' => Auth::user()->userDetail->mobile_no
                    // ];

                    // Auth::logout();

                    // $result = $this->sendVerificationCode($data);
                    // if ($result) {
                    //     return Response::json([
                    //         'status' => true,
                    //         'redirect_to' => URL::to('/verify-user'),
                    //         'message' => 'Your account is not verified. Please verify your account.'
                    //     ]);
                    // } else {
                    //     return Response::json([
                    //         'status' => false,
                    //         'message' => 'Server is not responding. Please try again.'
                    //     ]);
                    // }

                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Credentials not matched. Please try again.'
                ]);
            }
        }
    }

    public function studentCheckLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ], [
            'captcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_error',
                'message' => $validator->errors()->all()
            ]);
        } else {
            // Auth::attempt(['username' => $request->username, 'password' => $request->password, 'record_status' => 1])
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'record_status' => 1])) {
                if (Auth::user()->userDetail->is_verified) {

                    $landing_page_link = $this->getLandingPage(Auth::user()->role_code);
                    $redirect_to = URL::to($landing_page_link);

                    LoginDetail::create([
                        'user_id' => Auth::user()->user_id,
                        'login_datetime' => Carbon::now(),
                        'ip_address' => $request->ip(),
                        'current_status' => 'Login successfully',
                        'status' => 'Logged In',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    return Response::json([
                        'status' => true,
                        'redirect_to' => $redirect_to,
                        'message' => 'Login Successfully'
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Credentials not matched. Please try again.'
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        $redirect_to = route('login');
        if (Auth::check()) {

            if (Auth::user()->role_code == 'STUDENT') {
                $redirect_to = route('student-login');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect($redirect_to);
        } else {
            return redirect($redirect_to);
        }
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
