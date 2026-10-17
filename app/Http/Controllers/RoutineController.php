<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Client;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index()
    {
        try {
            $routines = Routine::with('trainer', 'client.user', 'exercises.exercise')->get();
            return response()->json($routines);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener rutinas: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $routine = Routine::with('trainer', 'client.user', 'exercises.exercise')->findOrFail($id);
            return response()->json($routine);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Rutina no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener rutina: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'name' => 'required',
                'description' => 'nullable',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'exercises' => 'nullable|array',
                'exercises.*.exercise_id' => 'required|exists:exercises,id',
                'exercises.*.sets' => 'nullable|integer',
                'exercises.*.reps' => 'nullable|integer',
                'exercises.*.weight' => 'nullable',
                'exercises.*.notes' => 'nullable',
            ]);

            $routine = Routine::create([
                'trainer_id' => auth()->id(),
                'client_id' => $request->client_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date ?? now(),
                'end_date' => $request->end_date,
                'active' => true,
            ]);

            if ($request->has('exercises')) {
                foreach ($request->exercises as $index => $exerciseData) {
                    $routine->exercises()->create([
                        'exercise_id' => $exerciseData['exercise_id'],
                        'sets' => $exerciseData['sets'] ?? 3,
                        'reps' => $exerciseData['reps'] ?? 10,
                        'weight' => $exerciseData['weight'] ?? null,
                        'notes' => $exerciseData['notes'] ?? null,
                        'order' => $index,
                    ]);
                }
            }

            return response()->json(['message' => 'Rutina creada exitosamente', 'routine' => $routine->load('trainer', 'client.user', 'exercises.exercise')], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear rutina: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $routine = Routine::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required',
            'description' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'active' => 'sometimes|boolean',
            'exercises' => 'nullable|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'nullable|integer',
            'exercises.*.reps' => 'nullable|integer',
            'exercises.*.weight' => 'nullable',
            'exercises.*.notes' => 'nullable',
        ]);

        if ($request->has('name')) {
            $routine->name = $request->name;
        }
        if ($request->has('description')) {
            $routine->description = $request->description;
        }
        if ($request->has('start_date')) {
            $routine->start_date = $request->start_date;
        }
        if ($request->has('end_date')) {
            $routine->end_date = $request->end_date;
        }
        if ($request->has('active')) {
            $routine->active = $request->active;
        }
        $routine->save();

        if ($request->has('exercises')) {
            $routine->exercises()->delete();
            foreach ($request->exercises as $index => $exerciseData) {
                $routine->exercises()->create([
                    'exercise_id' => $exerciseData['exercise_id'],
                    'sets' => $exerciseData['sets'] ?? 3,
                    'reps' => $exerciseData['reps'] ?? 10,
                    'weight' => $exerciseData['weight'] ?? null,
                    'notes' => $exerciseData['notes'] ?? null,
                    'order' => $index,
                ]);
            }
        }

        return response()->json(['message' => 'Rutina actualizada exitosamente', 'routine' => $routine->load('trainer', 'client.user', 'exercises.exercise')]);
    }

    public function destroy($id)
    {
        $routine = Routine::findOrFail($id);
        $routine->delete();
        return response()->json(['message' => 'Rutina eliminada exitosamente']);
    }

    public function clientRoutines($clientId)
    {
        $client = Client::findOrFail($clientId);
        $routines = $client->routines()->with('trainer', 'exercises.exercise')->get();
        return response()->json($routines);
    }

    public function myRoutines(Request $request)
    {
        try {
            $user = $request->user();
            $client = $user->client;
            
            if (!$client) {
                return response()->json(['message' => 'No tienes perfil de cliente'], 404);
            }

            $routines = $client->routines()->with('trainer', 'exercises.exercise')->where('active', true)->get();
            return response()->json($routines);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener tus rutinas: ' . $e->getMessage()], 500);
        }
    }
}
