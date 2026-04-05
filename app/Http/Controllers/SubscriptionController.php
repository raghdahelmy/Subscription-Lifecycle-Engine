<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }


    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        try {
            $user = Auth::user();
            $subscription = $this->subscriptionService->createSubscription($user, $request->plan_id);

            return response()->json([
                'success' => true,
                'message' => 'تم طلب باقة الاشتراك بنجاح. سيتم تفعيلها بعد الدفع.',
                'data'    => $subscription
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function paymentFailed(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        try {
            $this->subscriptionService->startGracePeriod($request->subscription_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment failed. Subscription moved to past_due with a 3-day grace period. Access remains granted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
