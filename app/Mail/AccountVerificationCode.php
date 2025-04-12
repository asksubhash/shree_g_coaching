<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationCode extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $verification_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $verification_code)
    {
        $this->data = $data;
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verification_code');
    }
}
