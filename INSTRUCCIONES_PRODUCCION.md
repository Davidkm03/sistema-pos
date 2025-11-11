# 🔍 INSTRUCCIONES PARA REVISAR EN PRODUCCIÓN

## ⚠️ Problema Reportado

"En producción me aparecen reportes no por mi business id si no de todas las empresas globales y eso no debería ser. Incluso me aparecen ventas de otros usuarios de otros negocios."

## 📋 Pasos para Diagnosticar en Producción

### 1. Conectarse al servidor de producción

```bash
ssh usuario@servidor-produccion
cd /ruta/al/proyecto
```

### 2. Ejecutar script de diagnóstico

```bash
php diagnose-empresa-scope.php
```

**Qué buscar en el output:**

❌ **MALO** - Si aparece:
```
⚠️  PROBLEMA: Hay X usuarios sin empresa_id
   - ID: 123, Email: usuario@ejemplo.com, Name: Juan Pérez
```

✅ **BUENO** - Si aparece:
```
✅ Todos los usuarios tienen empresa_id asignado
```

### 3. Verificar el usuario con el que estás logueado

**Opción A: Desde navegador con sesión activa**

1. Abrir Chrome DevTools (F12)
2. Ir a Console
3. Pegar este código en la consola del navegador:

```javascript
// Esto solo funciona si tienes una ruta que retorne el usuario
fetch('/api/user')
  .then(r => r.json())
  .then(u => console.log('Usuario:', u.email, 'Empresa ID:', u.empresa_id));
```

**Opción B: Desde terminal en servidor**

```bash
php artisan tinker
```

Luego en tinker:

```php
// Ver TODOS los usuarios y sus empresas
\App\Models\User::select('id', 'email', 'empresa_id')->get();

// Ver usuario específico (reemplaza el email)
$user = \App\Models\User::where('email', 'TU-EMAIL@ejemplo.com')->first();
echo "Empresa ID: " . $user->empresa_id . "\n";

// Ver cuántas ventas tiene esa empresa
\App\Models\Sale::withoutGlobalScopes()->where('empresa_id', $user->empresa_id)->count();

// Ver TODAS las ventas (de todas las empresas)
\App\Models\Sale::withoutGlobalScopes()->count();
```

### 4. Revisar logs de error

```bash
# Ver últimas 200 líneas del log
tail -200 storage/logs/laravel.log

# Buscar errores relacionados con empresa
grep -i "empresa" storage/logs/laravel.log | tail -50

# Buscar el error específico del scope
grep -i "EmpresaScope" storage/logs/laravel.log
```

**Qué buscar:**

❌ **CRÍTICO** - Si aparece:
```
[ERROR] EmpresaScope: Usuario autenticado sin empresa_id
user_id: 123
email: usuario@ejemplo.com
```

**Acción:** Este usuario NO tiene empresa asignada. Ver paso 5.

### 5. Si encuentras usuario sin empresa_id

```bash
php artisan tinker
```

```php
// Ver el usuario problemático
$user = \App\Models\User::find(ID_AQUI);
echo "Email: " . $user->email . "\n";
echo "Empresa actual: " . $user->empresa_id . "\n";

// Asignar empresa correcta (reemplaza 1 por el ID de su empresa)
$user->empresa_id = 1; // O el ID que corresponda
$user->save();

echo "✅ Empresa asignada correctamente\n";
exit;
```

### 6. Limpiar TODOS los caches

```bash
# Cache de aplicación
php artisan cache:clear

# Cache de configuración
php artisan config:clear

# Cache de vistas
php artisan view:clear

# Cache de rutas
php artisan route:clear

# Si usas Redis
php artisan redis:flush
# O manualmente:
redis-cli FLUSHALL

# Si usas Memcached
echo "flush_all" | nc localhost 11211
```

### 7. Probar nuevamente

1. **Cerrar TODAS las sesiones activas:**

```bash
php artisan tinker
```

```php
// Eliminar TODAS las sesiones
\DB::table('sessions')->truncate();
exit;
```

2. **Hacer logout completo del navegador**

3. **Login nuevamente**

4. **Verificar que SOLO veas datos de TU empresa**

### 8. Test rápido en navegador

**En la pantalla de ventas:**

1. Anota el **email del usuario con el que estás logueado**
2. Anota la **empresa a la que perteneces**
3. Ve a la lista de ventas
4. Anota cuántas ventas ves

**En terminal:**

```bash
php artisan tinker
```

```php
$user = \App\Models\User::where('email', 'TU-EMAIL@ejemplo.com')->first();
echo "Tu empresa_id: " . $user->empresa_id . "\n";

// Ventas de TU empresa
$ventas = \App\Models\Sale::withoutGlobalScopes()
    ->where('empresa_id', $user->empresa_id)
    ->count();
echo "Ventas de tu empresa: " . $ventas . "\n";

// Ventas TOTALES (todas las empresas)
$total = \App\Models\Sale::withoutGlobalScopes()->count();
echo "Ventas totales (todas empresas): " . $total . "\n";

// ✅ DEBE COINCIDIR
// Las ventas que ves en navegador === $ventas
// Si ves más ventas en navegador, hay problema
exit;
```

## 🚨 Casos Específicos

### Caso 1: "Veo ventas de TODAS las empresas"

**Causa más probable:** Usuario sin `empresa_id`

**Solución:**
1. Identificar usuario (paso 3)
2. Asignar empresa_id (paso 5)
3. Limpiar cache (paso 6)
4. Cerrar sesiones (paso 7)
5. Login nuevamente

### Caso 2: "A veces veo mis datos, a veces de otros"

**Causa más probable:** Cache compartido entre usuarios

**Solución:**
```bash
# Verificar configuración de cache
cat .env | grep CACHE

# Debe ser:
CACHE_DRIVER=file  # O redis con prefijo único
SESSION_DRIVER=file # O database

# Si usas redis, verificar:
cat .env | grep REDIS
```

Si usas Redis, asegurar que cada request use keys únicas:

```bash
# En config/cache.php verificar:
'redis' => [
    'driver' => 'redis',
    'connection' => 'cache',
    'lock_connection' => 'default',
    // Debe tener prefix único
],
```

### Caso 3: "Solo pasa con algunos usuarios"

**Causa probable:** Algunos usuarios tienen `empresa_id` NULL

**Solución:**
```bash
php artisan tinker
```

```php
// Ver TODOS los usuarios sin empresa
$sinEmpresa = \App\Models\User::whereNull('empresa_id')->get();
foreach ($sinEmpresa as $u) {
    echo "ID: {$u->id}, Email: {$u->email}\n";
}

// Asignar empresa a cada uno
// EJEMPLO: asignar empresa_id = 1 a todos los sin empresa
\App\Models\User::whereNull('empresa_id')->update(['empresa_id' => 1]);
exit;
```

## 📊 Queries Útiles

```sql
-- Ver distribución de usuarios por empresa
SELECT empresa_id, COUNT(*) as total_usuarios 
FROM users 
GROUP BY empresa_id;

-- Ver distribución de ventas por empresa
SELECT empresa_id, COUNT(*) as total_ventas, SUM(total) as total_monto
FROM sales 
GROUP BY empresa_id;

-- Encontrar ventas sin empresa_id (NO debería haber)
SELECT * FROM sales WHERE empresa_id IS NULL;

-- Encontrar usuarios sin empresa_id (NO debería haber, excepto super-admin)
SELECT id, email, name FROM users WHERE empresa_id IS NULL;
```

## ✅ Checklist de Verificación

- [ ] Ejecuté `diagnose-empresa-scope.php`
- [ ] No hay usuarios sin `empresa_id`
- [ ] No hay ventas sin `empresa_id`
- [ ] Limpié TODOS los caches
- [ ] Cerré TODAS las sesiones
- [ ] Hice logout y login nuevamente
- [ ] Verifiqué que solo veo datos de MI empresa
- [ ] Revisé los logs (no hay errores de EmpresaScope)

## 📝 Información para Reportar

Si el problema persiste, reportar:

```
INFORMACIÓN DEL USUARIO:
- Email: _________________
- ID: _________________
- empresa_id: _________________

INFORMACIÓN DE LA SESIÓN:
- URL donde ves el problema: _________________
- Navegador: _________________
- Cache driver (.env CACHE_DRIVER): _________________

DIAGNÓSTICO:
- Output de: php diagnose-empresa-scope.php
- Últimas 50 líneas de: tail -50 storage/logs/laravel.log
- Query: SELECT id, email, empresa_id FROM users;
```

---

**Fecha:** 2025-11-11  
**Versión:** 1.0
