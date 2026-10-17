<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();
        return response()->json($schedules);
    }

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);
        return response()->json($schedule);
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        $schedule = Schedule::create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_capacity' => $request->max_capacity ?? 20,
            'active' => true,
        ]);

        return response()->json(['message' => 'Horario creado exitosamente', 'schedule' => $schedule], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'day_of_week' => 'sometimes|required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required',
            'max_capacity' => 'sometimes|integer|min:1',
            'active' => 'sometimes|boolean',
        ]);

        if ($request->has('day_of_week')) {
            $schedule->day_of_week = $request->day_of_week;
        }
        if ($request->has('start_time')) {
            $schedule->start_time = $request->start_time;
        }
        if ($request->has('end_time')) {
            $schedule->end_time = $request->end_time;
        }
        if ($request->has('max_capacity')) {
            $schedule->max_capacity = $request->max_capacity;
        }
        if ($request->has('active')) {
            $schedule->active = $request->active;
        }
        $schedule->save();

        return response()->json(['message' => 'Horario actualizado exitosamente', 'schedule' => $schedule]);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Horario eliminado exitosamente']);
    }
}
