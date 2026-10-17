# Sistema de Gestión de Gimnasio

Sistema web para la gestión integral de un gimnasio con control de acceso basado en roles, gestión de clientes, facturación, rutinas de entrenamiento y control de asistencias.

## 🏋️ Características

### Roles de Usuario
- **Administrador**: Gestión completa de clientes, cobros, planes y configuración del sistema
- **Trainer**: Gestión de rutinas de entrenamiento y seguimiento de clientes
- **Cliente**: Panel personal para ver cuenta, rutinas asignadas y asistencia

### Funcionalidades
- ✅ Autenticación con roles diferenciados
- ✅ Gestión de clientes (CRUD completo)
- ✅ Gestión de planes de suscripción
- ✅ Sistema de pagos y cuentas corrientes
- ✅ Catálogo de ejercicios
- ✅ Creación y asignación de rutinas
- ✅ Control de horarios y asistencia
- ✅ API RESTful completa
- ✅ Frontend Vue.js funcional

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 12 + PHP 8.2+
- **Base de datos**: SQLite
- **Autenticación**: Laravel Sanctum
- **Frontend**: Vue.js 3 + Vite
- **API**: RESTful con rutas protegidas por roles

## 🚀 Instrucciones para Ejecutar el Proyecto

### 1. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 2. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 3. Iniciar servidores
**Terminal 1 - Servidor Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Servidor Vite:**
```bash
npm run dev
```

### 4. Acceder a la aplicación
- **URL:** http://127.0.0.1:8000/#/login

## 👥 Usuarios de Prueba

El sistema se inicializa con 3 usuarios de prueba:

- **Administrador**: admin@gimnasio.com / password
- **Trainer**: trainer@gimnasio.com / password
- **Cliente**: cliente@gimnasio.com / password

## 📡 API RESTful

Para probar la API, importar la colección de Postman ubicada en la carpeta `postman/`:
- `postman/Gimnasio API.postman_collection.json`
- `postman/Gimnasio_API_Environment.json`

### Endpoints Principales

#### Autenticación
- `POST /api/login` - Iniciar sesión

#### Administrador
- `GET /api/clients` - Listar clientes
- `POST /api/clients` - Crear cliente
- `PUT /api/clients/{id}` - Actualizar cliente
- `DELETE /api/clients/{id}` - Eliminar cliente
- `GET /api/plans` - Listar planes
- `POST /api/plans` - Crear plan
- `GET /api/payments` - Listar pagos
- `POST /api/payments` - Registrar pago
- `GET /api/exercises` - Listar ejercicios
- `POST /api/exercises` - Crear ejercicio
- `GET /api/schedules` - Listar horarios
- `POST /api/schedules` - Crear horario
- `GET /api/attendance` - Listar asistencia
- `POST /api/attendance` - Registrar asistencia

#### Trainer
- `GET /api/trainer/clients` - Ver clientes
- `GET /api/exercises` - Ver ejercicios
- `GET /api/routines` - Listar rutinas
- `POST /api/routines` - Crear rutina
- `GET /api/routines/client/{id}` - Ver rutinas de cliente

#### Cliente
- `GET /api/my-profile` - Mi perfil
- `GET /api/my-account-status` - Estado de mi cuenta
- `GET /api/my-routines` - Mis rutinas
- `GET /api/my-attendance` - Mi asistencia

## 🗄️ Estructura de la Base de Datos

- `users` - Usuarios del sistema
- `clients` - Información adicional de clientes
- `plans` - Planes de suscripción
- `client_plans` - Asignación de planes a clientes
- `payments` - Registro de pagos
- `exercises` - Catálogo de ejercicios
- `routines` - Rutinas asignadas
- `routine_exercises` - Ejercicios en cada rutina
- `schedules` - Horarios disponibles
- `attendance` - Registro de asistencia

---

**Estado**: Backend completo ✅ | Frontend funcional ✅
