<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class StudentAuthController extends Controller
{
    public function studentLogin()
    {
        return view('website.student.studentLogin');
    }

    public function studentRegister()
    {
        return view('website.student.studentRegister');
    }


    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'enqName' => 'required',
            'enqEmailId' => 'required|email',
            'enqPhoneNumber' => 'required',
            'enqMessage' => 'required',
            'captcha' => 'required|captcha'
        ], [
            'captcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $user = new StudentDetail();
            $user->edu_type = "10";
            $user->adm_sesh = $request->admission_session;
            $user->course = $request->course;
            $user->lang_subj = $request->language_subject;
            $user->non_lang_subj = $request->non_language_subject;
            $user->name = $request->name;

            $user->created_by = ""; //admin
            $user->created_at = now();
            if ($user->save()) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Student registered successfully.'
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
