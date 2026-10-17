# Sistema de Gestión de Gimnasio

Sistema web para la gestión integral de un gimnasio con control de acceso basado en roles, gestión de clientes, facturación, rutinas de entrenamiento y control de asistencias.



 Instrucciones para Ejecutar el Proyecto

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
