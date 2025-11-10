# 🏢 Sistema Multi-Empresa - Permisos y Restricciones

## 📋 Resumen

El sistema implementa **filtrado automático por empresa** usando `EmpresaScope`. Cada usuario pertenece a una empresa y solo puede ver/gestionar datos de su propia empresa.

---

## 👥 Roles y Permisos

### 🔴 Super Admin
**Características:**
- ✅ Puede ver **TODAS las empresas**
- ✅ Puede crear/editar/eliminar empresas
- ✅ Puede gestionar usuarios de **TODAS las empresas**
- ✅ Puede asignar cualquier empresa a un usuario
- ✅ Tiene acceso a **TODOS los datos** del sistema (sin filtro por empresa)
- ✅ Acceso a `/admin/empresas` (Gestión de Empresas)

**Restricciones:**
- ❌ Ninguna

### 🟢 Admin (de una empresa específica)
**Características:**
- ✅ Puede gestionar **solo usuarios de su empresa**
- ✅ Puede ver/editar productos, ventas, clientes de su empresa
- ✅ Puede crear usuarios en su empresa
- ✅ Acceso a `/usuarios` (Gestión de Usuarios)

**Restricciones:**
- ❌ **NO puede** ver usuarios de otras empresas
- ❌ **NO puede** editar usuarios de otras empresas
- ❌ **NO puede** cambiar la empresa de un usuario
- ❌ **NO puede** acceder a `/admin/empresas`
- ❌ El campo "Empresa" está **deshabilitado** (solo muestra su empresa)

### 🟡 Supervisor / 🔵 Cajero
**Características:**
- ✅ Solo ven datos de su propia empresa (filtrado automático)
- ✅ No tienen acceso a gestión de usuarios

**Restricciones:**
- ❌ **NO pueden** gestionar usuarios
- ❌ **NO pueden** ver datos de otras empresas

---

## 🔒 Filtrado Automático por Empresa

### EmpresaScope

Todos los modelos principales tienen aplicado `EmpresaScope`:

```php
protected static function booted()
{
    static::addGlobalScope(new EmpresaScope);
}
```

**Modelos con scope:**
- ✅ Product
- ✅ Category
- ✅ Customer
- ✅ Sale
- ✅ Quote
- ✅ Goal
- ✅ InventoryMovement

**Cómo funciona:**
1. Usuario inicia sesión
2. El sistema detecta su `empresa_id`
3. Todas las consultas automáticamente filtran por `empresa_id = usuario.empresa_id`
4. El usuario **SOLO ve datos de su empresa**

**Excepciones:**
- ❌ `Empresa` model **NO** tiene scope (para que super-admin pueda verlas todas)
- ❌ `User` model **NO** tiene scope (pero se filtra manualmente en UserManager)

---

## 🛡️ Validaciones en UserManager

### Al crear usuario:
```php
// Admin solo puede crear usuarios en su empresa
if (!$currentUser->hasRole('super-admin') && $this->empresa_id != $currentUser->empresa_id) {
    error: 'Solo puedes gestionar usuarios de tu empresa'
}
```

### Al editar usuario:
```php
// Validar que el usuario pertenezca a la misma empresa
if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
    error: 'No tienes permiso para editar este usuario'
}
```

### Al eliminar usuario:
```php
// Validar que el usuario pertenezca a la misma empresa
if (!auth()->user()->hasRole('super-admin') && $user->empresa_id !== auth()->user()->empresa_id) {
    error: 'No tienes permiso para eliminar este usuario'
}
```

### Al listar usuarios:
```php
// Solo super-admin puede ver todos los usuarios
if (!$currentUser->hasRole('super-admin')) {
    $query->where('empresa_id', $currentUser->empresa_id);
}
```

---

## 📊 Ejemplos de Uso

### Escenario 1: Super Admin
```
Usuario: Super Administrador
Rol: super-admin
Empresa: Tienda Principal (ID: 1)

✅ Ve usuarios de TODAS las empresas
✅ Puede crear usuario en Sucursal Norte (ID: 2)
✅ Puede editar usuario de Tienda Sur (ID: 3)
✅ Campo "Empresa" está habilitado
```

### Escenario 2: Admin de Tienda
```
Usuario: Admin Tienda Principal
Rol: Admin
Empresa: Tienda Principal (ID: 1)

✅ Ve SOLO usuarios de Tienda Principal
❌ NO ve usuarios de Sucursal Norte
❌ NO puede editar usuarios de otras empresas
❌ Campo "Empresa" está deshabilitado (solo muestra Tienda Principal)
```

### Escenario 3: Cajero
```
Usuario: Cajero Norte
Rol: Cajero
Empresa: Sucursal Norte (ID: 2)

✅ Ve SOLO productos/ventas de Sucursal Norte
❌ NO ve productos/ventas de otras tiendas
❌ NO tiene acceso a /usuarios
```

---

## 🔐 Rutas Protegidas

### Super Admin Only
```php
Route::middleware(['role:super-admin'])->prefix('admin')->group(function () {
    Route::resource('empresas', EmpresaController::class);
    Route::get('/roles', [RolePermissionController::class, 'index']);
});
```

### Admin o Super Admin
```php
Route::middleware(['role:Admin|super-admin'])->group(function () {
    Route::get('/usuarios', function () {
        return view('users.index');
    });
});
```

---

## 🎯 Mejores Prácticas

### ✅ DO (Hacer)
1. **Siempre asignar empresa_id al crear registros:**
   ```php
   Product::create([
       'nombre' => 'Producto X',
       'empresa_id' => auth()->user()->empresa_id,
   ]);
   ```

2. **Verificar permisos antes de acciones sensibles:**
   ```php
   if (!auth()->user()->hasRole('super-admin') && $record->empresa_id !== auth()->user()->empresa_id) {
       abort(403);
   }
   ```

3. **Usar EmpresaScope en modelos relacionados con empresas**

### ❌ DON'T (No hacer)
1. **No hacer queries sin filtro de empresa** (excepto super-admin)
2. **No permitir cambiar empresa_id sin validación**
3. **No olvidar agregar empresa_id en fillable de modelos**

---

## 🚨 Seguridad

### Capas de Seguridad:
1. **Middleware de roles:** `role:Admin|super-admin`
2. **EmpresaScope:** Filtrado automático en queries
3. **Validaciones manuales:** En controllers y Livewire components
4. **Gates:** Super-admin bypass en AppServiceProvider

### Prevención de Ataques:
- ✅ Mass Assignment Protection (fillable)
- ✅ CSRF Protection (Laravel default)
- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ Authorization Checks (Spatie Permission)

---

## 📝 Checklist para Nuevos Modelos

Al crear un nuevo modelo que debe estar filtrado por empresa:

- [ ] Agregar columna `empresa_id` en migration
- [ ] Agregar `empresa_id` al array `$fillable`
- [ ] Agregar relación `empresa()` al modelo
- [ ] Aplicar `EmpresaScope` en método `booted()`
- [ ] Asignar `empresa_id` automáticamente al crear
- [ ] Validar permisos en controller/Livewire

---

## 🔄 Migración de Datos Existentes

Si tienes datos sin `empresa_id`:

```php
// Asignar todos los registros sin empresa a la primera empresa
$empresa = Empresa::first();

User::whereNull('empresa_id')->update(['empresa_id' => $empresa->id]);
Product::whereNull('empresa_id')->update(['empresa_id' => $empresa->id]);
Category::whereNull('empresa_id')->update(['empresa_id' => $empresa->id]);
// ... etc
```

---

## 📞 Soporte

Para preguntas sobre el sistema multi-empresa, consultar:
- `app/Models/Scopes/EmpresaScope.php` - Implementación del scope
- `app/Livewire/UserManager.php` - Validaciones de permisos
- `app/Http/Controllers/EmpresaController.php` - CRUD de empresas
