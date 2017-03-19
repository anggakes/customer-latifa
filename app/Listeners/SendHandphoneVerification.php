<?php

namespace App\Listeners;

use App\Events\AfterRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendHandphoneVerification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AfterRegister  $event
     * @return void
     */
    public function handle(AfterRegister $event)
    {
        //
    }
}
