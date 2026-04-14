<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-donations')]
#[Description('Command description')]
class ExpireDonations extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\Donation::where('status', 'tersedia')
            ->where('pickup_deadline', '<', now())
            ->update(['status' => 'expired']);
    }
}
