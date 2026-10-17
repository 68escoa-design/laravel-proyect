<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gimnasio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        .navbar {
            background: #667eea;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.5rem;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .welcome-card h2 {
            color: #333;
            margin-bottom: 1rem;
        }
        .user-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .user-info p {
            margin-bottom: 0.5rem;
            color: #555;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .role-admin {
            background: #e74c3c;
            color: white;
        }
        .role-trainer {
            background: #3498db;
            color: white;
        }
        .role-client {
            background: #27ae60;
            color: white;
        }
        .api-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .api-info h3 {
            color: #856404;
            margin-bottom: 0.5rem;
        }
        .api-info p {
            color: #856404;
            font-size: 0.9rem;
        }
        .api-info code {
            background: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏋️ Sistema de Gimnasio</h1>
        <div>
            <a href="/login" class="logout-btn" style="margin-right: 0.5rem;">Cambiar Usuario</a>
            <a href="/logout" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>¡Bienvenido, {{ $user->name }}!</h2>
            
            <div class="user-info">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Rol:</strong> <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span></p>
                <p><strong>ID:</strong> {{ $user->id }}</p>
                <p><strong>Nombre de usuario:</strong> {{ $user->name }}</p>
            </div>
            
            <div class="api-info">
                <h3>🔌 API Token (Guardado en sesión)</h3>
                <p>Token: <code>{{ session('api_token') ? substr(session('api_token'), 0, 20) . '...' : 'No hay token' }}</code></p>
                <p>Usa este token para hacer peticiones a la API RESTful.</p>
            </div>
            
            @if($user->role === 'admin')
                <div class="api-info" style="background: #d5f4e6; border-color: #27ae60;">
                    <h3>👤 Permisos de Administrador</h3>
                    <p>Tienes acceso completo a:</p>
                    <ul style="margin-left: 1.5rem; color: #27ae60;">
                        <li>Gestión de usuarios (GET /api/users)</li>
                        <li>Crear usuarios (POST /api/users)</li>
                        <li>Editar usuarios (PUT /api/users/{id})</li>
                        <li>Eliminar usuarios (DELETE /api/users/{id})</li>
                    </ul>
                </div>
            @endif
            
            @if($user->role === 'trainer')
                <div class="api-info" style="background: #d1ecf1; border-color: #3498db;">
                    <h3>🏋️‍♂️ Permisos de Trainer</h3>
                    <p>Tienes acceso a:</p>
                    <ul style="margin-left: 1.5rem; color: #3498db;">
                        <li>Ver lista de clientes (GET /api/clients)</li>
                        <li>Crear y asignar rutinas (próximamente)</li>
                    </ul>
                </div>
            @endif
            
            @if($user->role === 'client')
                <div class="api-info" style="background: #f8d7da; border-color: #e74c3c;">
                    <h3>👥 Permisos de Cliente</h3>
                    <p>Tienes acceso a:</p>
                    <ul style="margin-left: 1.5rem; color: #e74c3c;">
                        <li>Tu perfil personal (GET /api/my-profile)</li>
                        <li>Tus rutinas asignadas (próximamente)</li>
                        <li>Tus turnos (próximamente)</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</body>
</html>