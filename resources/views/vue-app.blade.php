<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimnasio - Vue.js App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .navbar {
            background: #667eea;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            cursor: pointer;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-admin { background: #e74c3c; }
        .badge-trainer { background: #3498db; }
        .badge-client { background: #27ae60; }
        .loading {
            text-align: center;
            padding: 20px;
        }
        .error {
            color: #e74c3c;
            background: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .success {
            color: #27ae60;
            background: #d5f4e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .nav-links {
            display: flex;
            gap: 15px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        @media (max-width: 768px) {
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div id="vue-app">
        @verbatim
        <!-- Loading Screen -->
        <div v-if="!appLoaded" class="login-container">
            <div class="card">
                <h2 style="text-align: center;">Cargando aplicación...</h2>
            </div>
        </div>
        
        <!-- Login Page -->
        <div v-else-if="!isAuthenticated" class="login-container">
            <div class="card">
                <h2 style="text-align: center; margin-bottom: 20px;">🏋️ Login - Gimnasio</h2>
                
                <div v-if="error" class="error">{{ error }}</div>
                <div v-if="success" class="success">{{ success }}</div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" v-model="loginForm.email" placeholder="admin@gimnasio.com">
                </div>
                
                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" v-model="loginForm.password" placeholder="password">
                </div>
                
                <button class="btn btn-primary" @click="login" :disabled="loading">
                    {{ loading ? 'Cargando...' : 'Iniciar Sesión' }}
                </button>
                
                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                    <h4>Usuarios de prueba:</h4>
                    <p><strong>Admin:</strong> admin@gimnasio.com / password</p>
                    <p><strong>Trainer:</strong> trainer@gimnasio.com / password</p>
                    <p><strong>Cliente:</strong> cliente@gimnasio.com / password</p>
                </div>
            </div>
        </div>
        
        <!-- Main App -->
        <div v-else>
            <!-- Navbar -->
            <div class="navbar">
                <h1>🏋️ Sistema de Gimnasio</h1>
                <div class="nav-links">
                    <a @click="navigate('/dashboard')">Dashboard</a>
                    <a v-if="isAdmin" @click="navigate('/clients')">Clientes</a>
                    <a v-if="isAdmin" @click="navigate('/plans')">Planes</a>
                    <a v-if="isAdmin" @click="navigate('/payments')">Pagos</a>
                    <a v-if="isAdmin || isTrainer" @click="navigate('/exercises')">Ejercicios</a>
                    <a v-if="isAdmin || isTrainer" @click="navigate('/routines')">Rutinas</a>
                    <a v-if="isClient" @click="navigate('/my-routines')">Mis Rutinas</a>
                    <a v-if="isClient" @click="navigate('/my-attendance')">Mi Asistencia</a>
                    <a @click="logout" style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 5px;">Cerrar Sesión</a>
                </div>
            </div>
            
            <div class="container">
                <div v-if="error" class="error">{{ error }}</div>
                <div v-if="success" class="success">{{ success }}</div>
                
                <!-- Dashboard -->
                <div v-if="currentRoute === '/dashboard'">
                    <div class="card">
                        <h2>¡Bienvenido, {{ user.name }}!</h2>
                        <p><strong>Email:</strong> {{ user.email }}</p>
                        <p><strong>Rol:</strong> <span class="badge badge-{{ user.role }}">{{ user.role }}</span></p>
                        
                        <div v-if="isAdmin" style="margin-top: 20px;">
                            <h3>Panel de Administrador</h3>
                            <p>Tienes acceso completo a:</p>
                            <ul>
                                <li>Gestión de clientes</li>
                                <li>Gestión de planes y pagos</li>
                                <li>Gestión de ejercicios y rutinas</li>
                                <li>Control de asistencia</li>
                            </ul>
                        </div>
                        
                        <div v-if="isTrainer" style="margin-top: 20px;">
                            <h3>Panel de Trainer</h3>
                            <p>Tienes acceso a:</p>
                            <ul>
                                <li>Ver lista de clientes</li>
                                <li>Crear y asignar rutinas</li>
                                <li>Ver ejercicios disponibles</li>
                            </ul>
                        </div>
                        
                        <div v-if="isClient" style="margin-top: 20px;">
                            <h3>Panel de Cliente</h3>
                            <p>Tienes acceso a:</p>
                            <ul>
                                <li>Tu perfil personal</li>
                                <li>Tus rutinas asignadas</li>
                                <li>Tu historial de asistencia</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Clients (Admin only) -->
                <div v-if="currentRoute === '/clients' && isAdmin">
                    <div class="card">
                        <h2>Gestión de Clientes</h2>
                        
                        <div class="form-group">
                            <h3>Crear Nuevo Cliente</h3>
                            <div class="grid-2">
                                <input type="text" v-model="clientForm.name" placeholder="Nombre">
                                <input type="email" v-model="clientForm.email" placeholder="Email">
                                <input type="password" v-model="clientForm.password" placeholder="Contraseña">
                                <input type="text" v-model="clientForm.phone" placeholder="Teléfono">
                                <input type="text" v-model="clientForm.address" placeholder="Dirección">
                                <input type="date" v-model="clientForm.birth_date">
                            </div>
                            <button class="btn btn-success" @click="createClient" :disabled="loading">
                                {{ loading ? 'Guardando...' : 'Crear Cliente' }}
                            </button>
                        </div>
                        
                        <h3 style="margin-top: 20px;">Lista de Clientes</h3>
                        <div v-if="loading" class="loading">Cargando...</div>
                        <table v-else class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="client in clients" :key="client.id">
                                    <td>{{ client.user?.name }}</td>
                                    <td>{{ client.user?.email }}</td>
                                    <td>{{ client.phone }}</td>
                                    <td>
                                        <button class="btn btn-danger" @click="deleteClient(client.id)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Plans (Admin only) -->
                <div v-if="currentRoute === '/plans' && isAdmin">
                    <div class="card">
                        <h2>Gestión de Planes</h2>
                        
                        <div class="form-group">
                            <h3>Crear Nuevo Plan</h3>
                            <div class="grid-2">
                                <input type="text" v-model="planForm.name" placeholder="Nombre del plan">
                                <input type="number" v-model="planForm.price" placeholder="Precio">
                                <input type="number" v-model="planForm.weekly_sessions" placeholder="Sesiones semanales">
                                <input type="number" v-model="planForm.monthly_sessions" placeholder="Sesiones mensuales">
                            </div>
                            <textarea v-model="planForm.description" placeholder="Descripción" style="width: 100%; margin-top: 10px;"></textarea>
                            <button class="btn btn-success" @click="createPlan" :disabled="loading">
                                {{ loading ? 'Guardando...' : 'Crear Plan' }}
                            </button>
                        </div>
                        
                        <h3 style="margin-top: 20px;">Lista de Planes</h3>
                        <div v-if="loading" class="loading">Cargando...</div>
                        <table v-else class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Sesiones Semanales</th>
                                    <th>Sesiones Mensuales</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="plan in plans" :key="plan.id">
                                    <td>{{ plan.name }}</td>
                                    <td>${{ plan.price }}</td>
                                    <td>{{ plan.weekly_sessions }}</td>
                                    <td>{{ plan.monthly_sessions }}</td>
                                    <td>
                                        <button class="btn btn-danger" @click="deletePlan(plan.id)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Exercises (Admin/Trainer) -->
                <div v-if="currentRoute === '/exercises' && (isAdmin || isTrainer)">
                    <div class="card">
                        <h2>Catálogo de Ejercicios</h2>
                        
                        <div class="form-group">
                            <h3>Crear Nuevo Ejercicio</h3>
                            <div class="grid-2">
                                <input type="text" v-model="exerciseForm.name" placeholder="Nombre del ejercicio">
                                <input type="text" v-model="exerciseForm.muscle_group" placeholder="Grupo muscular">
                                <input type="text" v-model="exerciseForm.equipment" placeholder="Equipamiento">
                            </div>
                            <textarea v-model="exerciseForm.description" placeholder="Descripción" style="width: 100%; margin-top: 10px;"></textarea>
                            <textarea v-model="exerciseForm.instructions" placeholder="Instrucciones" style="width: 100%; margin-top: 10px;"></textarea>
                            <button class="btn btn-success" @click="createExercise" :disabled="loading">
                                {{ loading ? 'Guardando...' : 'Crear Ejercicio' }}
                            </button>
                        </div>
                        
                        <h3 style="margin-top: 20px;">Lista de Ejercicios</h3>
                        <div v-if="loading" class="loading">Cargando...</div>
                        <div v-else class="grid-3">
                            <div v-for="exercise in exercises" :key="exercise.id" class="card">
                                <h4>{{ exercise.name }}</h4>
                                <p><strong>Músculo:</strong> {{ exercise.muscle_group }}</p>
                                <p><strong>Equipamiento:</strong> {{ exercise.equipment }}</p>
                                <p>{{ exercise.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- My Routines (Client) -->
                <div v-if="currentRoute === '/my-routines' && isClient">
                    <div class="card">
                        <h2>Mis Rutinas</h2>
                        <div v-if="loading" class="loading">Cargando...</div>
                        <div v-else-if="routines.length === 0">
                            <p>No tienes rutinas asignadas.</p>
                        </div>
                        <div v-else>
                            <div v-for="routine in routines" :key="routine.id" class="card">
                                <h3>{{ routine.name }}</h3>
                                <p>{{ routine.description }}</p>
                                <p><strong>Trainer:</strong> {{ routine.trainer?.name }}</p>
                                <p><strong>Fecha inicio:</strong> {{ routine.start_date }}</p>
                                <h4>Ejercicios:</h4>
                                <ul>
                                    <li v-for="routineExercise in routine.exercises" :key="routineExercise.id">
                                        {{ routineExercise.exercise?.name }} - {{ routineExercise.sets }} series x {{ routineExercise.reps }} repeticiones
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- My Attendance (Client) -->
                <div v-if="currentRoute === '/my-attendance' && isClient">
                    <div class="card">
                        <h2>Mi Asistencia</h2>
                        <div v-if="loading" class="loading">Cargando...</div>
                        <div v-else-if="attendance.length === 0">
                            <p>No tienes registros de asistencia.</p>
                        </div>
                        <table v-else class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="att in attendance" :key="att.id">
                                    <td>{{ att.attendance_date }}</td>
                                    <td>{{ att.schedule?.day_of_week }} {{ att.schedule?.start_time }} - {{ att.schedule?.end_time }}</td>
                                    <td>{{ att.status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endverbatim
    </div>
</body>
</html>