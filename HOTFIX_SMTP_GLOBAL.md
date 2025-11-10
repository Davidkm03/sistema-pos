# Hotfix: SMTP Global para Todas las Empresas

## 🐛 Problema Detectado

Cuando un usuario admin (no super-admin) de otra empresa intentaba enviar cotizaciones por email, recibía el error:
```
"Por favor configure el servidor SMTP en Configuración del Negocio antes de enviar emails."
```

**Causa**: El sistema buscaba la configuración SMTP en el `business_settings` del usuario actual, pero solo el super-admin tenía acceso para configurarla.

---

## ✅ Solución Implementada

### Cambios Realizados

1. **Nuevo método en `BusinessSetting` model**:
   ```php
   public static function smtp()
   {
       // Busca la configuración SMTP del super-admin
       // La caché durante 1 hora
       // Retorna null si no está configurada
   }
   ```

2. **Actualizado `QuoteController::sendEmail()`**:
   - Ahora usa `BusinessSetting::smtp()` en lugar de `BusinessSetting::current()`
   - Obtiene SMTP global del super-admin
   - Sigue usando datos del negocio actual para el contenido del email
   - Mensaje de error mejorado: "Contacte al administrador del sistema"

3. **Caché optimizado**:
   - SMTP se cachea globalmente como `smtp_settings_global`
   - Se limpia automáticamente cuando super-admin guarda configuración

---

## 🚀 Deployment en Producción

### Opción 1: Usando deploy.sh (Recomendado)

```bash
# SSH a Hostinger
ssh -p 65002 u301792158@156.67.73.78

cd domains/paginaswebscolombia.com/public_html/sistemapos

# Pull cambios
git pull origin main

# Limpiar cachés
php artisan cache:clear
php artisan config:clear

# Optimizar
php artisan config:cache
```

### Opción 2: Manual

```bash
# SSH
ssh -p 65002 u301792158@156.67.73.78

cd domains/paginaswebscolombia.com/public_html/sistemapos

# 1. Pull
git pull origin main

# 2. Clear cache
php artisan cache:clear

# 3. Verificar
php artisan tinker
>>> \App\Models\BusinessSetting::smtp();
# Debe retornar la configuración del super-admin
```

---

## 🔍 Cómo Funciona Ahora

### Antes (❌ No funcionaba)
```
Usuario Admin (Empresa B) → enviar email
   ↓
Buscar SMTP en business_settings de Usuario Admin
   ↓
No encuentra (solo super-admin lo configuró)
   ↓
ERROR: "Configure SMTP..."
```

### Ahora (✅ Funciona)
```
Usuario Admin (Empresa B) → enviar email
   ↓
Buscar SMTP GLOBAL (del super-admin)
   ↓
Encuentra configuración SMTP ✓
   ↓
Usa datos de negocio de Empresa B para el contenido
   ↓
Email enviado exitosamente 🎉
```

---

## 📋 Verificación Post-Deployment

### 1. Probar como Super-Admin
```bash
# Login como super-admin
# Ir a cotización
# Enviar email
# ✅ Debe funcionar (como antes)
```

### 2. Probar como Admin (Otra Empresa)
```bash
# Login como admin de otra empresa
# Ir a cotización
# Enviar email
# ✅ Ahora debe funcionar correctamente
```

### 3. Probar Sin Configuración SMTP
```bash
# Si super-admin NO ha configurado SMTP
# Mensaje esperado: "El servidor SMTP no está configurado. Contacte al administrador del sistema."
```

---

## 🔧 Troubleshooting

### Si sigue sin funcionar después del deploy:

```bash
# 1. Limpiar TODO el caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Verificar configuración SMTP existe
php artisan tinker
>>> $smtp = \App\Models\BusinessSetting::smtp();
>>> dd($smtp);
# Debe mostrar los datos SMTP del super-admin

# 3. Verificar super-admin tiene SMTP configurado
>>> $superAdmin = \App\Models\User::role('super-admin')->first();
>>> $settings = \App\Models\BusinessSetting::where('user_id', $superAdmin->id)->first();
>>> dd($settings->smtp_host);
# Debe mostrar: "smtp.hostinger.com" u otro host
```

### Si SMTP es null:

```bash
# El super-admin debe configurar SMTP:
1. Login como super-admin
2. Ir a: Configuración del Negocio
3. Scroll hasta sección SMTP (morada)
4. Llenar todos los campos
5. Guardar
```

---

## 📊 Arquitectura Final

```
┌─────────────────────────────────────┐
│  SMTP Configuration (GLOBAL)        │
│  ---------------------------------- │
│  Configurado por: Super Admin       │
│  Usado por: TODAS las empresas      │
│  Storage: business_settings table   │
│  Campo: user_id del super-admin     │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  BusinessSetting::smtp()            │
│  - Busca super-admin                │
│  - Obtiene su business_settings     │
│  - Valida SMTP completo             │
│  - Cachea 1 hora                    │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  Email Sending                      │
│  - SMTP: Global (super-admin)       │
│  - Contenido: Negocio actual        │
│  - From Name: Negocio actual        │
└─────────────────────────────────────┘
```

---

## ✨ Beneficios

1. ✅ **Configuración única**: Super-admin configura SMTP una sola vez
2. ✅ **Funciona para todos**: Todas las empresas usan la misma config SMTP
3. ✅ **Personalización**: Cada email usa los datos del negocio correspondiente
4. ✅ **Seguridad**: Solo super-admin puede modificar SMTP
5. ✅ **Performance**: SMTP se cachea globalmente (menos queries)

---

## 📝 Archivos Modificados

- ✅ `app/Models/BusinessSetting.php` - Agregado método `smtp()`
- ✅ `app/Http/Controllers/QuoteController.php` - Usa SMTP global

---

## 🎯 Commit

- **Hash**: `986e765`
- **Mensaje**: "fix: Make SMTP configuration global for all empresas"
- **Archivos**: 2 changed, 42 insertions(+), 12 deletions(-)

---

**Fecha**: 2025-11-10  
**Tipo**: Hotfix  
**Prioridad**: Alta  
**Status**: ✅ Resuelto y Deployable
