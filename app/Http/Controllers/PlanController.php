<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json($plans);
    }

    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json($plan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'weekly_sessions' => 'nullable|integer|min:0',
            'monthly_sessions' => 'nullable|integer|min:0',
        ]);

        $plan = Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'weekly_sessions' => $request->weekly_sessions ?? 0,
            'monthly_sessions' => $request->monthly_sessions ?? 0,
            'active' => true,
        ]);

        return response()->json(['message' => 'Plan creado exitosamente', 'plan' => $plan], 201);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required',
            'description' => 'nullable',
            'price' => 'sometimes|numeric|min:0',
            'weekly_sessions' => 'sometimes|integer|min:0',
            'monthly_sessions' => 'sometimes|integer|min:0',
            'active' => 'sometimes|boolean',
        ]);

        if ($request->has('name')) {
            $plan->name = $request->name;
        }
        if ($request->has('description')) {
            $plan->description = $request->description;
        }
        if ($request->has('price')) {
            $plan->price = $request->price;
        }
        if ($request->has('weekly_sessions')) {
            $plan->weekly_sessions = $request->weekly_sessions;
        }
        if ($request->has('monthly_sessions')) {
            $plan->monthly_sessions = $request->monthly_sessions;
        }
        if ($request->has('active')) {
            $plan->active = $request->active;
        }
        $plan->save();

        return response()->json(['message' => 'Plan actualizado exitosamente', 'plan' => $plan]);
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return response()->json(['message' => 'Plan eliminado exitosamente']);
    }
}
