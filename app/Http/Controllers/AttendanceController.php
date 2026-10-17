<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Client;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        try {
            $attendance = Attendance::with('client.user', 'schedule')->get();
            return response()->json($attendance);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener registros de asistencia: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $attendance = Attendance::with('client.user', 'schedule')->findOrFail($id);
            return response()->json($attendance);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Registro de asistencia no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener registro de asistencia: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'schedule_id' => 'required|exists:schedules,id',
                'status' => 'nullable|in:present,absent,late',
                'notes' => 'nullable|string',
            ]);

            $attendance = Attendance::create([
                'client_id' => $request->client_id,
                'schedule_id' => $request->schedule_id,
                'attendance_date' => now(),
                'check_in_time' => now(),
                'status' => $request->status ?? 'present',
                'notes' => $request->notes,
            ]);

            return response()->json(['message' => 'Asistencia registrada exitosamente', 'attendance' => $attendance->load('client.user', 'schedule')], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al registrar asistencia: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'check_out_time' => 'nullable',
            'status' => 'sometimes|in:present,absent,late',
            'notes' => 'nullable|string',
        ]);

        if ($request->has('check_out_time')) {
            $attendance->check_out_time = $request->check_out_time;
        }
        if ($request->has('status')) {
            $attendance->status = $request->status;
        }
        if ($request->has('notes')) {
            $attendance->notes = $request->notes;
        }
        $attendance->save();

        return response()->json(['message' => 'Asistencia actualizada exitosamente', 'attendance' => $attendance->load('client.user', 'schedule')]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(['message' => 'Asistencia eliminada exitosamente']);
    }

    public function clientAttendance($clientId)
    {
        $client = Client::findOrFail($clientId);
        $attendance = $client->attendance()->with('schedule')->get();
        return response()->json($attendance);
    }

    public function myAttendance(Request $request)
    {
        try {
            $user = $request->user();
            $client = $user->client;
            
            if (!$client) {
                return response()->json(['message' => 'No tienes perfil de cliente'], 404);
            }

            $attendance = $client->attendance()->with('schedule')->get();
            return response()->json($attendance);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener tu asistencia: ' . $e->getMessage()], 500);
        }
    }
}
