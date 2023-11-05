<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public string $name;
    public int $orderId;
    public mixed $message;

    public function __construct($name, $orderId, $message)
    {
        $this->name    = $name;
        $this->orderId = $orderId;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject("Order Notification")->markdown('emails.order');
    }
}
