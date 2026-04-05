<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function index()
    {
        $plans = Plan::all();
        return response()->json([
            'message' => 'Plans retrieved successfully.',
            'data' => $plans
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trial_days' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:AED,USD,EGP',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $plan = Plan::create($validated);

        return response()->json([
            'message' => 'Plan created successfully.',
            'data' => $plan
        ], 201);
    }

    public function show(Plan $plan)
    {
        return response()->json($plan);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'trial_days' => 'sometimes|integer|min:0',
            'amount' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|in:AED,USD,EGP',
            'billing_cycle' => 'sometimes|in:monthly,yearly',
        ]);

        $plan->update($validated);

        return response()->json([
            'message' => 'Plan updated successfully.',
            'data' => $plan
        ], 200);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully.']);
    }
}
