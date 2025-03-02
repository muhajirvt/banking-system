<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;


    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;

    }

    public function build()
    {
        return $this->subject('2fa authentication!')
        ->view('auth.2fa')
        ->with(['user' => $this->user, 'otp' => $this->otp]);
    }
}