# 🚀 Deploy: Sistema de Cotizaciones

## 📋 Cambios en este Deploy

- ✨ Nuevo módulo completo de Cotizaciones
- 🗄️ Tablas de base de datos para quotes y quote_items
- 🔐 Sistema de permisos para cotizaciones
- 📄 Vistas de creación, edición, visualización e impresión
- 🔄 Conversión de cotizaciones a ventas
- 📊 Gestión de estados y fechas de validez

---

## 🔌 Conectarse al Servidor

```bash
ssh u301792158@paginaswebscolombia.com
```

**Contraseña**: [Tu contraseña de Hostinger]

---

## 📦 Script de Deployment Completo

Una vez conectado por SSH, ejecuta este comando TODO EN UNO:

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos && \
echo "📡 Trayendo cambios del repositorio..." && \
git pull origin main && \
echo "🗄️ Ejecutando migraciones..." && \
php artisan migrate --force && \
echo "🔐 Creando permisos de cotizaciones..." && \
php artisan db:seed --class=QuotesPermissionsSeeder --force && \
echo "🧹 Limpiando cachés..." && \
php artisan cache:clear && \
php artisan config:clear && \
php artisan route:clear && \
php artisan view:clear && \
php artisan permission:cache-reset && \
echo "🎯 Optimizando para producción..." && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
echo "✅ ¡Deployment de cotizaciones completado exitosamente!"
```

**IMPORTANTE**: Este deploy incluye:
- ✅ Migración de tablas `quotes` y `quote_items`
- ✅ Permisos: `quotes.view`, `quotes.create`, `quotes.edit`, `quotes.delete`, `quotes.convert`
- ✅ Asignación automática de permisos a roles existentes
- ✅ Limpieza y optimización de cachés

---

## 📝 Paso a Paso (Alternativa Manual)

Si prefieres hacerlo paso a paso:

### 1. Navegar al directorio
```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos
```

### 2. Verificar estado actual
```bash
git status
git branch
```

### 3. Hacer pull de los cambios
```bash
git pull origin main
```

Deberías ver:
```
Updating [hash]...[hash]
Fast-forward
 app/Http/Controllers/QuoteController.php           | ...
 app/Models/Quote.php                                | ...
 app/Models/QuoteItem.php                            | ...
 database/migrations/..._create_quotes_table.php     | ...
 database/migrations/..._create_quote_items_table.php| ...
 database/seeders/QuotesPermissionsSeeder.php        | ...
 resources/views/quotes/...                          | ...
```

### 4. Ejecutar migraciones
```bash
php artisan migrate --force
```

Deberías ver:
```
INFO  Running migrations.

2025_11_09_201313_create_quotes_table ................ DONE
2025_11_09_201316_create_quote_items_table ........... DONE
```

### 5. Ejecutar seeder de permisos
```bash
php artisan db:seed --class=QuotesPermissionsSeeder --force
```

Deberías ver:
```
✅ Permisos de cotizaciones creados y asignados correctamente.
   - Super Admin: Todos los permisos
   - Admin: Todos los permisos
   - Supervisor: Todos los permisos
   - Cajero: Ver y crear solamente
```

### 6. Limpiar cachés
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset
```

### 7. Optimizar para producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔍 Verificación

### Abrir en el navegador:
- **Lista de cotizaciones**: https://sistemapos.paginaswebscolombia.com/cotizaciones
- **Nueva cotización**: https://sistemapos.paginaswebscolombia.com/cotizaciones/create

### Verificar que se vean:
1. ✅ Botón "Cotizaciones" en el menú lateral
2. ✅ Página de lista de cotizaciones funciona
3. ✅ Puede crear nueva cotización
4. ✅ Puede ver detalles de una cotización
5. ✅ Puede imprimir cotización
6. ✅ Puede convertir cotización a venta (según permisos)
7. ✅ Los permisos funcionan correctamente según el rol

---

## ⚠️ Troubleshooting

### Si no aparece el botón de "Cotizaciones":
```bash
# Verificar que los permisos existan
php artisan tinker
>>> \Spatie\Permission\Models\Permission::where('name', 'like', 'quotes.%')->pluck('name');

# Si están vacíos, ejecutar el seeder:
php artisan db:seed --class=QuotesPermissionsSeeder --force

# Limpiar caché de permisos
php artisan permission:cache-reset
php artisan cache:clear
```

### Si aparece error 500 al acceder a /cotizaciones:
```bash
# Ver logs
tail -50 storage/logs/laravel.log

# Verificar que las tablas existan
php artisan tinker
>>> Schema::hasTable('quotes');  // Debe retornar true
>>> Schema::hasTable('quote_items');  // Debe retornar true

# Si no existen, ejecutar migraciones:
php artisan migrate --force

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Para verificar permisos de un usuario específico:
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'tu@email.com')->first();
>>> $user->getAllPermissions()->pluck('name');

# Si el usuario no tiene permisos, ejecutar:
>>> php artisan db:seed --class=QuotesPermissionsSeeder --force
```

### Si los cachés causan problemas:
```bash
# Limpiar TODOS los cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset

# Recompilar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📊 Checklist Post-Deployment

- [ ] Tablas `quotes` y `quote_items` creadas
- [ ] Permisos de cotizaciones creados
- [ ] Botón "Cotizaciones" visible en menú
- [ ] Página de lista de cotizaciones funciona
- [ ] Puede crear nueva cotización
- [ ] Puede ver detalles de cotización
- [ ] Puede editar cotización
- [ ] Puede imprimir cotización
- [ ] Puede convertir cotización a venta
- [ ] No hay errores en logs
- [ ] Permisos funcionan según roles

---

## 🎯 Comandos Útiles

### Ver logs en tiempo real:
```bash
tail -f storage/logs/laravel.log
```

### Verificar migraciones pendientes:
```bash
php artisan migrate:status
```

### Ver último commit:
```bash
git log -1
```

### Verificar tablas en la base de datos:
```bash
php artisan tinker
>>> Schema::hasTable('quotes');
>>> Schema::hasTable('quote_items');
>>> DB::table('quotes')->count();
```

### Verificar permisos creados:
```bash
php artisan tinker
>>> \Spatie\Permission\Models\Permission::where('name', 'like', 'quotes.%')->get();
```

---

## 📋 Permisos Creados

| Permiso | Descripción | Super Admin | Admin | Supervisor | Cajero |
|---------|-------------|-------------|-------|------------|--------|
| `quotes.view` | Ver cotizaciones | ✅ | ✅ | ✅ | ✅ |
| `quotes.create` | Crear cotizaciones | ✅ | ✅ | ✅ | ✅ |
| `quotes.edit` | Editar cotizaciones | ✅ | ✅ | ✅ | ❌ |
| `quotes.delete` | Eliminar cotizaciones | ✅ | ✅ | ✅ | ❌ |
| `quotes.convert` | Convertir a venta | ✅ | ✅ | ✅ | ❌ |

---

**Fecha**: 9 de Noviembre, 2025  
**Commit**: 11bf498 - Módulo completo de Cotizaciones  
**Tiempo estimado**: 5-10 minutos

---

## 🆘 Soporte

Si algo sale mal:
1. Revisar logs: `tail -f storage/logs/laravel.log`
2. Verificar tablas: `php artisan tinker` → `Schema::hasTable('quotes')`
3. Verificar permisos: Ejecutar `QuotesPermissionsSeeder`
4. Limpiar cachés: Script del paso 6
5. Hard refresh navegador: `Ctrl + Shift + R`


