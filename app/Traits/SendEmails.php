<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerificationCode;
use Exception;

/**
 *
 */
trait SendEmails
{
    public function sendVerificationCode($data)
    {
        try {
            $verification_code = rand(99999, 999999);

            $result = VerificationCode::create([
                'email_id' => $data['email'],
                'code' => $verification_code,
                'expiration_time' => Carbon::now()->addHour()
            ]);

            Mail::to($data['email'])->send(new AccountVerificationCode($data, $verification_code));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
