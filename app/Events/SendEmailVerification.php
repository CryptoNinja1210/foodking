<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmailVerification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $info;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
    }
}
