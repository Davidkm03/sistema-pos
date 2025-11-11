# 🔒 FIX: Filtrado por Empresa (Multi-Tenancy)

## 📋 Problema Identificado

En producción aparecen **reportes y ventas de TODAS las empresas** cuando debería aparecer solo las de la empresa del usuario autenticado.

## 🔍 Diagnóstico Realizado

### ✅ Estado del Sistema

1. **EmpresaScope** está correctamente implementado en:
   - `Sale` (Ventas)
   - `Product` (Productos)
   - `Category` (Categorías)
   - `Customer` (Clientes)
   - `Supplier` (Proveedores)
   - `Expense` (Gastos)
   - `Quote` (Cotizaciones)
   - `Goal` (Metas)

2. **Modelos** tienen `empresa_id` y filtran correctamente

3. **En desarrollo** todo funciona correctamente

### ⚠️ Posibles Causas en Producción

1. **Usuario sin `empresa_id`** → Si el usuario autenticado NO tiene `empresa_id`, el scope NO filtra
2. **Cache corrupto** → Datos en cache de otra sesión/empresa
3. **Problema de Auth** → El usuario autenticado no es quien se espera

## 🛠️ Soluciones Implementadas

### 1. Middleware de Validación (`EnsureUserHasEmpresa`)

```php
app/Http/Middleware/EnsureUserHasEmpresa.php
```

**Qué hace:**
- Valida que TODO usuario autenticado tenga `empresa_id`
- Si NO tiene `empresa_id` y NO es super-admin → **cierra sesión y redirige al login**
- Loguea intentos de acceso sin empresa_id

**Registrado en:** `bootstrap/app.php`

### 2. Mejora del `EmpresaScope`

```php
app/Models/Scopes/EmpresaScope.php
```

**Cambios:**
- Si usuario autenticado NO tiene `empresa_id` → **retorna consulta vacía** (`whereRaw('1 = 0')`)
- Loguea en error cuando detecta usuario sin empresa_id
- **Previene leak de datos** en caso de usuario mal configurado

### 3. Script de Diagnóstico

```bash
php diagnose-empresa-scope.php
```

**Qué verifica:**
- Usuarios sin `empresa_id`
- Ventas, productos, categorías sin `empresa_id`
- Estadísticas por empresa
- Funcionamiento del scope

## 📦 Deployment en Producción

### Paso 1: Backup

```bash
# Backup de base de datos
php artisan db:backup

# Backup de archivos
tar -czf backup-$(date +%Y%m%d).tar.gz .
```

### Paso 2: Subir Cambios

```bash
git add .
git commit -m "🔒 Fix: Agregar validación de empresa_id para prevenir leak de datos multi-tenant"
git push origin main
```

### Paso 3: En Producción

```bash
# 1. Pull de cambios
git pull origin main

# 2. Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Ejecutar diagnóstico
php diagnose-empresa-scope.php

# 4. Verificar logs
tail -100 storage/logs/laravel.log
```

### Paso 4: Verificar Usuarios

```bash
# Ejecutar en producción para verificar usuarios sin empresa_id
php artisan tinker

# En tinker:
User::whereNull('empresa_id')->get(['id', 'email', 'name']);
```

**Si encuentras usuarios sin `empresa_id`:**

```bash
# En tinker:
$user = User::find(ID_USUARIO);
$user->empresa_id = ID_EMPRESA; # Asignar empresa correcta
$user->save();
```

### Paso 5: Monitoreo

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log | grep -i "empresa"
```

## 🔍 Qué Buscar en los Logs

### Log de Usuario sin Empresa

```
[ERROR] EmpresaScope: Usuario autenticado sin empresa_id
user_id: 123
email: usuario@ejemplo.com
model: App\Models\Sale
```

**Acción:** Asignar `empresa_id` al usuario

### Log de Middleware

```
[CRITICAL] Usuario sin empresa_id intentando acceder al sistema
user_id: 123
email: usuario@ejemplo.com
url: /pos
```

**Acción:** El middleware automáticamente lo desconectó. Corregir el usuario.

## 🧪 Testing en Producción

### 1. Login con diferentes usuarios de diferentes empresas

```bash
# Usuario Empresa A
- Login
- Ver ventas → Solo de Empresa A
- Ver productos → Solo de Empresa A
- Logout

# Usuario Empresa B
- Login
- Ver ventas → Solo de Empresa B
- Ver productos → Solo de Empresa B
```

### 2. Verificar Reportes

- Ir a módulo de reportes
- Verificar que solo aparezcan datos de la empresa actual
- Verificar totales

### 3. Verificar POS

- Abrir POS
- Verificar que solo aparezcan productos de la empresa actual
- Hacer una venta de prueba
- Verificar que solo apareció en reportes de esa empresa

## 📊 Queries de Verificación

```sql
-- Ver usuarios y sus empresas
SELECT id, name, email, empresa_id FROM users;

-- Ver ventas sin empresa_id (NO debería haber)
SELECT * FROM sales WHERE empresa_id IS NULL;

-- Ver productos sin empresa_id (NO debería haber)
SELECT * FROM products WHERE empresa_id IS NULL;

-- Contar ventas por empresa
SELECT empresa_id, COUNT(*) as total 
FROM sales 
GROUP BY empresa_id;
```

## 🚨 Si el Problema Persiste

### Opción 1: Verificar que el usuario en producción sea el correcto

```bash
php artisan tinker

# Ver usuario autenticado actual (desde navegador con sesión activa)
auth()->user();
auth()->user()->empresa_id;
```

### Opción 2: Limpiar TODAS las sesiones

```bash
php artisan session:flush
php artisan cache:clear
```

### Opción 3: Verificar configuración de cache

```env
# En .env
CACHE_DRIVER=file  # O redis, memcached
SESSION_DRIVER=file # O database, redis
```

Si usas Redis/Memcached, asegurar que las keys sean únicas por empresa:

```bash
# Limpiar cache de Redis
redis-cli FLUSHALL
```

## 📝 Archivos Modificados

1. `app/Http/Middleware/EnsureUserHasEmpresa.php` (NUEVO)
2. `app/Http/Middleware/LogUserEmpresaId.php` (NUEVO - opcional para debug)
3. `app/Models/Scopes/EmpresaScope.php` (MODIFICADO)
4. `bootstrap/app.php` (MODIFICADO - registro de middleware)
5. `diagnose-empresa-scope.php` (NUEVO - script de diagnóstico)

## ✅ Checklist Post-Deployment

- [ ] Ejecutar `diagnose-empresa-scope.php` en producción
- [ ] Verificar que NO haya usuarios sin `empresa_id`
- [ ] Verificar que NO haya ventas sin `empresa_id`
- [ ] Limpiar todos los caches
- [ ] Probar login con usuarios de diferentes empresas
- [ ] Verificar que cada usuario solo vea SUS datos
- [ ] Monitorear logs por 24 horas
- [ ] Verificar reportes de diferentes empresas

## 🆘 Soporte

Si el problema continúa después de aplicar estos cambios:

1. Revisar logs: `storage/logs/laravel.log`
2. Ejecutar diagnóstico: `php diagnose-empresa-scope.php`
3. Verificar sesiones activas
4. Verificar configuración de cache
5. Contactar soporte técnico con:
   - Logs de error
   - Output del script de diagnóstico
   - Usuario afectado (email)
   - Empresa del usuario

---

**Fecha:** 2025-11-11  
**Versión:** 1.0  
**Autor:** Sistema POS - Multi-Tenancy Fix
