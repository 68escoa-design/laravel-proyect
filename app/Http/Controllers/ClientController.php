<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        try {
            $clients = Client::with('user')->get();
            return response()->json($clients);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener clientes: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::with('user')->findOrFail($id);
            return response()->json($client);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener cliente: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone' => 'nullable',
                'address' => 'nullable',
                'birth_date' => 'nullable|date',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'client',
            ]);

            $client = Client::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'registration_date' => now(),
                'active' => true,
            ]);

            return response()->json(['message' => 'Cliente creado exitosamente', 'client' => $client->load('user')], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear cliente: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $user = $client->user;

        $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'phone' => 'nullable',
            'address' => 'nullable',
            'birth_date' => 'nullable|date',
            'active' => 'sometimes|boolean',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->has('phone')) {
            $client->phone = $request->phone;
        }
        if ($request->has('address')) {
            $client->address = $request->address;
        }
        if ($request->has('birth_date')) {
            $client->birth_date = $request->birth_date;
        }
        if ($request->has('active')) {
            $client->active = $request->active;
        }
        $client->save();

        return response()->json(['message' => 'Cliente actualizado exitosamente', 'client' => $client->load('user')]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['message' => 'Cliente eliminado exitosamente']);
    }
}
