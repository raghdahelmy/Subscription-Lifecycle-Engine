<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Subscription;
use Carbon\Carbon;

Schedule::call(function () {
    $now = Carbon::now();

    Subscription::where('status', 'trialing')
        ->where('trial_expire_at', '<', $now)
        ->update([
            'status' => 'past_due',
            'grace_period_ends_at' => Carbon::now()->addDays(3)
        ]);

    Subscription::where('status', 'active')
        ->where('expires_at', '<', $now)
        ->update([
            'status' => 'past_due',
            'grace_period_ends_at' => Carbon::now()->addDays(3)
        ]);

    Subscription::where('status', 'past_due')
        ->whereNotNull('grace_period_ends_at')
        ->where('grace_period_ends_at', '<', $now)
        ->update(['status' => 'canceled']);
})->daily()->name('check-subscriptions');
