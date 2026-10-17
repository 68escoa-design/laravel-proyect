<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        try {
            $exercises = Exercise::all();
            return response()->json($exercises);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener ejercicios: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $exercise = Exercise::findOrFail($id);
            return response()->json($exercise);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Ejercicio no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener ejercicio: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'nullable',
                'muscle_group' => 'nullable',
                'equipment' => 'nullable',
                'instructions' => 'nullable',
            ]);

            $exercise = Exercise::create([
                'name' => $request->name,
                'description' => $request->description,
                'muscle_group' => $request->muscle_group,
                'equipment' => $request->equipment,
                'instructions' => $request->instructions,
            ]);

            return response()->json(['message' => 'Ejercicio creado exitosamente', 'exercise' => $exercise], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear ejercicio: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required',
            'description' => 'nullable',
            'muscle_group' => 'nullable',
            'equipment' => 'nullable',
            'instructions' => 'nullable',
        ]);

        if ($request->has('name')) {
            $exercise->name = $request->name;
        }
        if ($request->has('description')) {
            $exercise->description = $request->description;
        }
        if ($request->has('muscle_group')) {
            $exercise->muscle_group = $request->muscle_group;
        }
        if ($request->has('equipment')) {
            $exercise->equipment = $request->equipment;
        }
        if ($request->has('instructions')) {
            $exercise->instructions = $request->instructions;
        }
        $exercise->save();

        return response()->json(['message' => 'Ejercicio actualizado exitosamente', 'exercise' => $exercise]);
    }

    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();
        return response()->json(['message' => 'Ejercicio eliminado exitosamente']);
    }
}
