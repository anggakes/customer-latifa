<?php

namespace App\Listeners;

use App\Events\AfterRegister;
use App\Repository\Wallet\WalletInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateWallet
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public $wallet;

    public function __construct(WalletInterface $wallet)
    {
        //
        $this->wallet = $wallet;
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
        $this->wallet->setUserId($event->user->id);
        $this->wallet->create();
    }
}
