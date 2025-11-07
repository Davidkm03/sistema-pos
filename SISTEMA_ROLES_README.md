# Sistema de Gestión de Roles y Permisos

## 📋 Descripción General

Sistema completo de administración de roles y permisos que permite al Super Administrador gestionar visualmente qué permisos tiene cada rol del sistema.

## ✨ Características Implementadas

### 1. **Controlador de Gestión**
- **Archivo**: `app/Http/Controllers/RolePermissionController.php`
- **Funcionalidades**:
  - ✅ Listar todos los roles con sus permisos y usuarios asignados
  - ✅ Editar permisos de cualquier rol (excepto super-admin)
  - ✅ Crear nuevos roles
  - ✅ Eliminar roles (con validación de usuarios activos)
  - ✅ Agrupación automática de permisos por módulo

### 2. **Vistas Administrativas**

#### Vista de Índice (`resources/views/admin/roles/index.blade.php`)
- Tabla con información de cada rol:
  - Nombre del rol con icono distintivo
  - Cantidad de permisos asignados
  - Número de usuarios con ese rol
  - Acciones (Editar/Eliminar)
- Modal para crear nuevos roles
- Mensajes de éxito/error con iconos SVG
- Diseño responsivo (tabla scrollable en móviles)
- Protección contra eliminación del rol super-admin

#### Vista de Edición (`resources/views/admin/roles/edit.blade.php`)
- Información del rol (nombre, permisos actuales, usuarios)
- Checkbox grid organizado por módulos:
  - Products
  - Sales
  - Customers
  - Inventory
  - Reports
  - Goals
  - Settings
  - Users
- Funciones JavaScript:
  - `toggleAllPermissions()`: Seleccionar/deseleccionar todos
  - `toggleModulePermissions(module)`: Seleccionar/deseleccionar por módulo
- Advertencia visual para el rol super-admin
- Inputs deshabilitados para super-admin (no modificable)

### 3. **Rutas Protegidas**
- **Archivo**: `routes/web.php`
- **Middleware**: `auth` + `role:super-admin`
- **Rutas**:
  ```
  GET    /admin/roles              → index
  POST   /admin/roles              → store
  GET    /admin/roles/{role}/edit  → edit
  PUT    /admin/roles/{role}       → update
  DELETE /admin/roles/{role}       → destroy
  ```

### 4. **Navegación**
- **Archivo**: `resources/views/layouts/navigation.blade.php`
- **Desktop**: Dropdown "Admin" con icono de escudo (solo visible para super-admin)
- **Mobile**: Link directo "Super Admin - Roles" con icono
- **Iconos**: SVG Heroicons profesionales

## 🔒 Medidas de Seguridad

1. **Protección del Super Admin**:
   - No se puede editar el rol super-admin
   - No se puede eliminar el rol super-admin
   - Todos los inputs están deshabilitados en el formulario de edición

2. **Validación de Usuario**:
   - No se puede eliminar un rol si tiene usuarios asignados
   - Muestra advertencia con número de usuarios activos

3. **Control de Acceso**:
   - Solo usuarios con el rol `super-admin` pueden acceder
   - Middleware protege todas las rutas de administración

## 📊 Estructura de Permisos

Los permisos siguen el formato: `{acción}-{módulo}`

### Ejemplos:
- `view-products` → Módulo: **products**
- `edit-sales` → Módulo: **sales**
- `create-customers` → Módulo: **customers**
- `delete-inventory` → Módulo: **inventory**

### Agrupación Automática:
El controlador agrupa permisos por módulo extrayendo la parte después del guion:
```php
$permissions->groupBy(function($permission) {
    $parts = explode('-', $permission->name);
    return count($parts) > 1 ? $parts[1] : 'general';
});
```

## 🎨 Diseño UI/UX

### Colores y Estilos:
- **Primary**: Indigo (#4F46E5) - Botones principales
- **Success**: Green (#10B981) - Mensajes de éxito
- **Error**: Red (#EF4444) - Mensajes de error
- **Warning**: Purple (#9333EA) - Advertencias de super-admin

### Iconografía:
- ✅ Todos los iconos son SVG Heroicons
- ✅ Sin emojis (según requerimiento del cliente)
- Ejemplos:
  - Escudo: Super Admin
  - Usuarios: Roles estándar
  - Lápiz: Editar
  - Papelera: Eliminar
  - Checkmark: Guardar

### Responsive Design:
- **Mobile**: Tabla scrollable, formularios apilados
- **Tablet**: Grid 2-3 columnas
- **Desktop**: Grid 4 columnas, tabla completa

## 🚀 Uso del Sistema

### Como Super Admin:

1. **Acceder al Panel**:
   - Desktop: Click en dropdown "Admin" → "Gestión de Roles"
   - Mobile: Click en "Super Admin - Roles"

2. **Crear un Nuevo Rol**:
   - Click en "Crear Nuevo Rol"
   - Ingresar nombre en minúsculas (ej: vendedor, supervisor)
   - Click "Crear Rol"
   - El rol se crea sin permisos, editar para asignarlos

3. **Editar Permisos de un Rol**:
   - Click en "Editar" del rol deseado
   - Marcar/desmarcar checkboxes por permiso individual
   - Usar "Seleccionar Todos" del módulo para marcar todos los permisos de ese módulo
   - Click "Guardar Cambios"

4. **Eliminar un Rol**:
   - Solo posible si el rol NO tiene usuarios asignados
   - Click en "Eliminar"
   - Confirmar en el diálogo de confirmación
   - Si hay usuarios, aparece tooltip explicativo

## 🔄 Flujo de Datos

```
Usuario Super Admin
    ↓
Navegación → /admin/roles
    ↓
RolePermissionController@index
    ↓
Obtiene roles con permisos y cuenta de usuarios
    ↓
Agrupa permisos por módulo
    ↓
Vista: index.blade.php
    ↓
Usuario hace cambios
    ↓
RolePermissionController@update
    ↓
Valida super-admin
    ↓
Sincroniza permisos con syncPermissions()
    ↓
Redirecciona con mensaje de éxito
```

## 📝 Notas Técnicas

### Dependencias:
- Spatie Laravel Permission 6.21
- Laravel 12
- Alpine.js (para modales)
- Tailwind CSS (estilos)

### Métodos Clave:

**syncPermissions()**: Método de Spatie que reemplaza todos los permisos del rol
```php
$role->syncPermissions($request->permissions ?? []);
```

**hasRole()**: Verifica si el usuario tiene un rol específico
```php
auth()->user()->hasRole('super-admin')
```

**users()->count()**: Cuenta usuarios asignados a un rol
```php
$role->users()->count()
```

## ⚠️ Consideraciones Importantes

1. **Super Admin es Inmutable**: 
   - El rol `super-admin` siempre tiene TODOS los permisos
   - No se puede modificar ni eliminar
   - Es el rol más alto del sistema

2. **Permisos vs Roles**:
   - Los permisos son atómicos (view-products, edit-sales)
   - Los roles son colecciones de permisos
   - Un usuario puede tener múltiples roles

3. **Cache de Permisos**:
   - Spatie cachea permisos por defecto
   - Si hay cambios manuales en BD, ejecutar: `php artisan permission:cache-reset`

## 🧪 Testing

### Casos de Prueba:

✅ Super admin puede acceder a /admin/roles
✅ Usuario regular recibe 403 Forbidden
✅ No se puede editar super-admin role
✅ No se puede eliminar rol con usuarios activos
✅ Crear nuevo rol funciona correctamente
✅ Editar permisos se refleja inmediatamente
✅ Navegación solo visible para super-admin

### Comandos Útiles:

```bash
# Ver todas las rutas admin
php artisan route:list --path=admin

# Limpiar cache de permisos
php artisan permission:cache-reset

# Ver roles y permisos en base de datos
php artisan tinker
>>> Role::with('permissions')->get();
```

## 📚 Recursos Adicionales

- [Spatie Permission Docs](https://spatie.be/docs/laravel-permission)
- [Heroicons](https://heroicons.com)
- [Tailwind CSS](https://tailwindcss.com)

---

**Fecha de Implementación**: Noviembre 2024
**Versión**: 1.0.0
**Estado**: ✅ Producción
