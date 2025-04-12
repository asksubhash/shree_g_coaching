<?php

namespace App\Http\Controllers;

use Response;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    public function storeEnquiry(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'enqName' => 'required',
            'enqEmailId' => 'required|email',
            'enqPhoneNumber' => 'required',
            'enqMessage' => 'required',
            'enqCaptcha' => 'required|captcha'
        ], [
            'enqCaptcha' => 'The captcha value entered is incorrect. Please try again.'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            // Your existing database transaction logic here
            DB::beginTransaction();

            // Save the enquiry data
            $result = Enquiry::create([
                'name' => $request->enqName,
                'email_id' => $request->enqEmailId,
                'phone_number' => $request->enqPhoneNumber,
                'message' => $request->enqMessage,
                'created_by' => $request->enqEmailId,
                'created_at' => now(),
                'updated_by' => $request->enqEmailId,
                'updated_at' => now(),
            ]);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Enquiry submitted successfully. We will revert you as soon as possible.'
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
