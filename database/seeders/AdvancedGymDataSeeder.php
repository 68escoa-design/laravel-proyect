<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Exercise;
use App\Models\Routine;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvancedGymDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear rutinas de prueba
        $this->createRoutines();
        
        // Crear horarios de prueba
        $this->createSchedules();
        
        // Crear registros de asistencia de prueba
        $this->createAttendance();
    }
    
    private function createRoutines()
    {
        $clients = Client::with('user')->get();
        $trainer = User::where('role', 'trainer')->first();
        $exercises = Exercise::all();
        
        if (!$trainer) {
            $this->command->warn('No se encontró trainer, creando rutinas sin trainer');
            return;
        }
        
        foreach ($clients as $index => $client) {
            $routine = Routine::create([
                'trainer_id' => $trainer->id,
                'client_id' => $client->id,
                'name' => 'Rutina ' . ($index + 1) . ' - ' . $client->user->name,
                'description' => 'Rutina personalizada para ' . $client->user->name,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'active' => true,
            ]);
            
            // Agregar ejercicios a la rutina
            $selectedExercises = $exercises->random(3);
            foreach ($selectedExercises as $exerciseIndex => $exercise) {
                $routine->exercises()->create([
                    'exercise_id' => $exercise->id,
                    'sets' => 3,
                    'reps' => 10 + $exerciseIndex,
                    'weight' => null,
                    'notes' => 'Mantener buena forma',
                    'order' => $exerciseIndex,
                ]);
            }
        }
    }
    
    private function createSchedules()
    {
        $schedules = [
            [
                'day_of_week' => 'Lunes',
                'start_time' => '06:00',
                'end_time' => '22:00',
                'max_capacity' => 20,
            ],
            [
                'day_of_week' => 'Martes',
                'start_time' => '06:00',
                'end_time' => '22:00',
                'max_capacity' => 20,
            ],
            [
                'day_of_week' => 'Miércoles',
                'start_time' => '06:00',
                'end_time' => '22:00',
                'max_capacity' => 20,
            ],
            [
                'day_of_week' => 'Jueves',
                'start_time' => '06:00',
                'end_time' => '22:00',
                'max_capacity' => 20,
            ],
            [
                'day_of_week' => 'Viernes',
                'start_time' => '06:00',
                'end_time' => '22:00',
                'max_capacity' => 20,
            ],
            [
                'day_of_week' => 'Sábado',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'max_capacity' => 15,
            ],
            [
                'day_of_week' => 'Domingo',
                'start_time' => '08:00',
                'end_time' => '14:00',
                'max_capacity' => 10,
            ],
        ];
        
        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
    
    private function createAttendance()
    {
        $clients = Client::all();
        $schedules = Schedule::all();
        
        // Crear registros de asistencia para los últimos 7 días
        foreach ($clients as $client) {
            for ($i = 0; $i < 3; $i++) {
                $schedule = $schedules->random();
                $attendanceDate = now()->subDays(rand(0, 6));
                
                Attendance::create([
                    'client_id' => $client->id,
                    'schedule_id' => $schedule->id,
                    'attendance_date' => $attendanceDate->format('Y-m-d'),
                    'check_in_time' => $attendanceDate->format('H:i'),
                    'check_out_time' => $attendanceDate->addHours(rand(1, 2))->format('H:i'),
                    'status' => 'present',
                ]);
            }
        }
    }
}
