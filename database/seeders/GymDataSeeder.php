<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Plan;
use App\Models\ClientPlan;
use App\Models\Payment;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GymDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear clientes de prueba
        $this->createClients();
        
        // Crear planes de prueba
        $this->createPlans();
        
        // Crear ejercicios de prueba
        $this->createExercises();
        
        // Asignar planes a clientes
        $this->assignPlansToClients();
        
        // Crear pagos de prueba
        $this->createPayments();
    }
    
    private function createClients()
    {
        $clientsData = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@gmail.com',
                'password' => 'password123',
                'phone' => '123456789',
                'address' => 'Calle 123',
                'birth_date' => '1990-01-01',
            ],
            [
                'name' => 'María García',
                'email' => 'maria@gmail.com',
                'password' => 'password123',
                'phone' => '987654321',
                'address' => 'Avenida 456',
                'birth_date' => '1985-05-15',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@gmail.com',
                'password' => 'password123',
                'phone' => '555555555',
                'address' => 'Plaza 789',
                'birth_date' => '1992-10-20',
            ],
        ];
        
        foreach ($clientsData as $clientData) {
            $user = User::create([
                'name' => $clientData['name'],
                'email' => $clientData['email'],
                'password' => Hash::make($clientData['password']),
                'role' => 'client',
            ]);
            
            Client::create([
                'user_id' => $user->id,
                'phone' => $clientData['phone'],
                'address' => $clientData['address'],
                'birth_date' => $clientData['birth_date'],
                'registration_date' => now(),
                'active' => true,
            ]);
        }
    }
    
    private function createPlans()
    {
        $plans = [
            [
                'name' => 'Plan Básico',
                'description' => 'Acceso 3 días por semana',
                'price' => 30.00,
                'weekly_sessions' => 3,
                'monthly_sessions' => 12,
            ],
            [
                'name' => 'Plan Estándar',
                'description' => 'Acceso 5 días por semana',
                'price' => 50.00,
                'weekly_sessions' => 5,
                'monthly_sessions' => 20,
            ],
            [
                'name' => 'Plan Premium',
                'description' => 'Acceso ilimitado',
                'price' => 80.00,
                'weekly_sessions' => 7,
                'monthly_sessions' => 30,
            ],
        ];
        
        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
    
    private function createExercises()
    {
        $exercises = [
            [
                'name' => 'Press de Banca',
                'description' => 'Ejercicio compuesto para pecho, hombros y tríceps',
                'muscle_group' => 'Pecho',
                'equipment' => 'Barra y banco',
                'instructions' => 'Acuéstate en el banco, baja la barra hasta el pecho y sube',
            ],
            [
                'name' => 'Sentadilla',
                'description' => 'Ejercicio fundamental para piernas',
                'muscle_group' => 'Piernas',
                'equipment' => 'Barra o peso libre',
                'instructions' => 'Flexiona las rodillas manteniendo la espalda recta',
            ],
            [
                'name' => 'Dominadas',
                'description' => 'Ejercicio para espalda y bíceps',
                'muscle_group' => 'Espalda',
                'equipment' => 'Barra fija',
                'instructions' => 'Cuélgate de la barra y sube hasta que tu barbilla pase la barra',
            ],
            [
                'name' => 'Press Militar',
                'description' => 'Desarrollo de hombros',
                'muscle_group' => 'Hombros',
                'equipment' => 'Barra o mancuernas',
                'instructions' => 'Empuja el peso hacia arriba desde los hombros',
            ],
            [
                'name' => 'Curl de Bíceps',
                'description' => 'Aislamiento de bíceps',
                'muscle_group' => 'Bíceps',
                'equipment' => 'Mancuernas o barra',
                'instructions' => 'Flexiona los codos llevando el peso hacia los hombros',
            ],
            [
                'name' => 'Extensiones de Tríceps',
                'description' => 'Aislamiento de tríceps',
                'muscle_group' => 'Tríceps',
                'equipment' => 'Mancuernas o polea',
                'instructions' => 'Extiende los codos llevando el peso hacia atrás',
            ],
        ];
        
        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
    
    private function assignPlansToClients()
    {
        $clients = Client::all();
        $plans = Plan::all();
        
        foreach ($clients as $index => $client) {
            $plan = $plans[$index % $plans->count()];
            
            ClientPlan::create([
                'client_id' => $client->id,
                'plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'active' => true,
            ]);
        }
    }
    
    private function createPayments()
    {
        $clients = Client::all();
        
        foreach ($clients as $client) {
            $plan = $client->plans()->where('active', true)->first();
            
            if ($plan) {
                Payment::create([
                    'client_id' => $client->id,
                    'amount' => $plan->plan->price,
                    'payment_date' => now(),
                    'payment_method' => 'cash',
                    'reference' => 'REC-' . rand(1000, 9999),
                    'notes' => 'Pago mensual del plan ' . $plan->plan->name,
                ]);
            }
        }
    }
}
