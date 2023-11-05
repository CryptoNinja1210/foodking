<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $pin;
    public function __construct($pin)
    {
        $this->pin=$pin;
    }


    public function build()
    {
        return $this->subject("Reset Password")->markdown('emails.password');
    }
}
