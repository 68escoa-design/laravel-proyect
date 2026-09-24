# Reglas del Proyecto - Sistema de Gimnasio

## Git Auto-Commit Habilitado

Este proyecto está configurado para permitir commits automáticos sin restricciones por parte del asistente de IA.

### Permisos Habilitados

✅ **Auto-commit**: El asistente puede hacer commits automáticos
✅ **File operations**: Crear, modificar y eliminar archivos
✅ **Dependency management**: Instalar dependencias con composer/npm
✅ **Database operations**: Ejecutar migraciones y seeders
✅ **Config changes**: Modificar archivos de configuración
✅ **Testing**: Ejecutar tests cuando sea necesario

### Restricciones de Seguridad

❌ **Destructive operations**: No se permiten operaciones destructivas sin confirmación
❌ **Force push**: No se permite force push
❌ **Branch deletion**: No se permite eliminar ramas
❌ **Database destruction**: No se permite eliminar tablas o bases de datos

## Flujo de Trabajo con Auto-Commit

1. **Cambios de código**: El asistente puede hacer commits automáticos
2. **Refactorización**: Commits descriptivos para cambios de código
3. **Bug fixes**: Commits inmediatos para correcciones
4. **Features**: Commits por funcionalidad completada
5. **Documentation**: Commits para actualizaciones de documentación

## Formato de Commits

Los commits automáticos seguirán este formato:
```
feat: descripción de la nueva funcionalidad
fix: descripción de la corrección
refactor: descripción de la refactorización
docs: descripción de la documentación
chore: descripción de tareas de mantenimiento
```

## Proyecto Laravel + Vue.js

Este es un proyecto full-stack con:
- **Backend**: Laravel 12
- **Frontend**: Vue.js 3
- **Database**: SQLite (desarrollo) / MySQL (producción)
- **Auth**: Laravel Sanctum

## Prioridades de Desarrollo

1. **Funcionalidad**: Código que funcione correctamente
2. **Seguridad**: Validaciones y autenticación robusta
3. **Performance**: Optimización de consultas y assets
4. **Mantenibilidad**: Código limpio y bien documentado
5. **Testing**: Tests cuando sea apropiado

## Especialización Web Development

El asistente está especializado en:
- Desarrollo Laravel (APIs, Eloquent, Controllers)
- Desarrollo Vue.js (Components, State Management)
- Integración Frontend-Backend
- Base de datos y migraciones
- Patrones de diseño y arquitectura