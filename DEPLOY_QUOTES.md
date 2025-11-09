# Despliegue del Sistema de Cotizaciones

## Pasos para activar el módulo de cotizaciones en producción

### 1. Hacer backup de la base de datos
```bash
# Es importante hacer un respaldo antes de cualquier cambio
php artisan db:backup  # Si tienes configurado backup
# O manualmente exporta tu base de datos
```

### 2. Ejecutar las migraciones
```bash
php artisan migrate
```

Esto creará las tablas necesarias:
- `quotes` - Tabla principal de cotizaciones
- `quote_items` - Tabla de items de cotizaciones

### 3. Ejecutar el seeder de permisos
```bash
php artisan db:seed --class=QuotesPermissionsSeeder
```

Esto creará los siguientes permisos:
- `quotes.view` - Ver cotizaciones
- `quotes.create` - Crear cotizaciones
- `quotes.edit` - Editar cotizaciones
- `quotes.delete` - Eliminar cotizaciones
- `quotes.convert` - Convertir cotización a venta

Y los asignará a los roles:
- **Super Admin**: Todos los permisos
- **Admin**: Todos los permisos
- **Supervisor**: Todos los permisos
- **Cajero**: Solo ver y crear

### 4. Limpiar cachés
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 5. Optimizar para producción (opcional pero recomendado)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Verificar permisos de usuario
Si ya tienes usuarios creados, verifica que tengan los permisos correctos:
```bash
php artisan tinker
```

Luego en tinker:
```php
// Ver permisos de un usuario específico
$user = App\Models\User::find(1);
$user->getAllPermissions()->pluck('name');

// Si necesitas asignar permisos manualmente a un usuario:
$user->givePermissionTo('quotes.view');
$user->givePermissionTo('quotes.create');
```

### 7. Verificar que todo funcione
- Inicia sesión en el sistema
- Deberías ver el botón "Cotizaciones" en el menú lateral
- Accede a `/cotizaciones` para verificar que la página funcione

## Solución de problemas

### El botón de cotizaciones no aparece
1. Verifica que el usuario tenga el permiso `quotes.view`:
   ```bash
   php artisan tinker
   $user = App\Models\User::where('email', 'tu@email.com')->first();
   $user->hasPermissionTo('quotes.view'); // Debe retornar true
   ```

2. Si no tiene el permiso, ejecuta:
   ```bash
   php artisan db:seed --class=QuotesPermissionsSeeder
   ```

3. Limpia el caché de permisos:
   ```bash
   php artisan cache:clear
   ```

### Error 500 al acceder a cotizaciones
1. Verifica que la tabla `quotes` exista:
   ```bash
   php artisan tinker
   Schema::hasTable('quotes'); // Debe retornar true
   ```

2. Si la tabla no existe:
   ```bash
   php artisan migrate
   ```

### Los permisos no se aplican
1. Limpia el caché de permisos de Spatie:
   ```bash
   php artisan permission:cache-reset
   ```

2. Reinicia el servidor web si estás usando php-fpm:
   ```bash
   sudo service php8.x-fpm restart  # Ajusta según tu versión de PHP
   ```

## Archivos importantes del módulo

- Migración: `database/migrations/2025_11_09_201313_create_quotes_table.php`
- Seeder: `database/seeders/QuotesPermissionsSeeder.php`
- Modelo: `app/Models/Quote.php`
- Controlador: `app/Http/Controllers/QuoteController.php`
- Rutas: `routes/web.php` (líneas 65-74)
- Vistas: `resources/views/quotes/`

## Notas adicionales

- El sistema de cotizaciones usa el mismo sistema de roles y permisos que el resto de la aplicación
- Las cotizaciones pueden convertirse a ventas, lo que descuenta el inventario
- Cada cotización tiene un número único (QT-00001, QT-00002, etc.)
- Las cotizaciones tienen fecha de validez configurable
