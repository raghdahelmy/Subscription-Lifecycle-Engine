<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function createSubscription($user, $planId)
    {
        $plan = Plan::findOrFail($planId);
        $trialDays = $plan->trial_days;
        $isTrial = $trialDays > 0;

        $existingActive = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing', 'past_due'])
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                      ->orWhere('grace_period_ends_at', '>', now());
            })
            ->first();

        if ($existingActive) {
            throw new \Exception('لديك اشتراك فعال أو في فترة سماح بالفعل.');
        }

        if ($isTrial) {
            $hasUsedTrialBefore = Subscription::where('user_id', $user->id)
                ->whereNotNull('trial_at')
                ->exists();

            if ($hasUsedTrialBefore) {
                throw new \Exception('عذراً، لقد استنفدت حقك في الفترة التجريبية مسبقاً.');
            }

            return Subscription::create([
                'user_id'         => $user->id,
                'plan_id'         => $plan->id,
                'status'          => 'trialing',
                'starts_at'       => now(),
                'expires_at'      => now()->addDays($trialDays),
                'trial_at'        => now(),
                'trial_expire_at' => now()->addDays($trialDays),
            ]);
        }

        return Subscription::create([
            'user_id'         => $user->id,
            'plan_id'         => $plan->id,
            'status'          => 'pending',
           'starts_at'       => now(),
            'expires_at'      => $this->calculateExpiry($plan->billing_cycle),
            'trial_at'        => now(),
            'trial_expire_at' => now()->addDays($trialDays),
            'grace_period_ends_at' => null,
        ]);
    }

//when payment fails, start grace period
    public function startGracePeriod($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);

        $subscription->update([
            'status' => 'past_due',
            'grace_period_ends_at' => now()->addDays(3),
        ]);
    }

    private function calculateExpiry($cycle)
    {
        return $cycle === 'monthly' ? now()->addMonth() : now()->addYear();
    }
}
