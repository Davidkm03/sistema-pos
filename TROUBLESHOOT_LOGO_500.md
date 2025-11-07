# 🚨 Guía de Solución: Error 500 en Configuración de Negocio

## Error Actual

```
POST https://sistemapos.paginaswebscolombia.com/livewire/update 500 (Internal Server Error)
```

Este error ocurre cuando intentas guardar la configuración del negocio (especialmente con logo).

## 🔍 Paso 1: Diagnosticar el Error en Hostinger

### Conectarse por SSH:

```bash
ssh u301792158@paginaswebscolombia.com
cd domains/paginaswebscolombia.com/public_html/sistemapos
```

### Ver el error exacto en los logs:

```bash
# Ver últimas líneas del log
tail -100 storage/logs/laravel.log

# O ver en tiempo real (mantener abierto mientras pruebas)
tail -f storage/logs/laravel.log
```

## 🛠️ Paso 2: Aplicar el Fix

### Opción A: Pull de los cambios (RECOMENDADO)

```bash
# Pull del fix
git pull origin main

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Reconstruir cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar permisos
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Opción B: Si git pull no funciona

```bash
# Guardar cambios locales
git stash

# Pull
git pull origin main

# Restaurar cambios locales (si necesario)
git stash pop

# Limpiar cachés (igual que Opción A)
```

## 🧪 Paso 3: Probar Extensión GD

La compresión de imágenes requiere la extensión GD de PHP:

```bash
# Verificar si GD está instalada
php -m | grep -i gd

# Debería mostrar: gd
```

**Si NO aparece GD:**
- Contactar a Hostinger vía chat/ticket
- Solicitar activar extensión `php-gd`
- Es una extensión estándar, debería estar disponible

## 🔧 Paso 4: Verificar Permisos de Directorios

```bash
# Ver permisos actuales
ls -la storage/app/public/

# Debería verse así:
# drwxrwxr-x  logos

# Si no tiene permisos correctos:
chmod -R 775 storage/app/public/
chmod -R 775 storage/logs/

# Verificar ownership (si es necesario)
# Normalmente Hostinger maneja esto automáticamente
```

## 🎯 Paso 5: Test Manual de Subida

```bash
# Test con Tinker
php artisan tinker
```

Luego ejecuta esto dentro de tinker:

```php
// Test 1: Verificar que puede escribir en storage
$result = Storage::disk('public')->put('logos/test.txt', 'TEST');
echo $result ? "✅ Puede escribir\n" : "❌ No puede escribir\n";

// Test 2: Verificar helper process_and_save_image existe
echo function_exists('process_and_save_image') ? "✅ Helper existe\n" : "❌ Helper no existe\n";

// Test 3: Verificar configuración actual
$settings = \App\Models\BusinessSetting::current();
echo "Business Name: " . $settings->business_name . "\n";

// Test 4: Limpiar archivo de prueba
Storage::disk('public')->delete('logos/test.txt');
echo "✅ Test completado\n";

exit
```

## 🐛 Errores Comunes y Soluciones

### Error: "GD library not installed"

**Solución:**
```bash
# Contactar a Hostinger para activar php-gd
# O temporal: desactivar compresión
```

**Fix temporal (si GD no está disponible):**
Editar `app/Livewire/BusinessSettingsManager.php` línea ~190:

```php
// Comentar el try-catch y usar store directo
$data['business_logo'] = $this->business_logo->store('logos', 'public');
```

### Error: "Permission denied"

**Solución:**
```bash
chmod -R 775 storage/
chown -R $USER:$USER storage/  # Solo si ownership está mal
```

### Error: "Class 'Log' not found"

**Solución:**
Ya está corregido en el último commit. Hacer `git pull origin main`

### Error: "Storage::delete() expects disk"

**Solución:**
Ya está corregido en el último commit. Hacer `git pull origin main`

## 📊 Verificación Post-Fix

### Checklist:

- [ ] `git pull origin main` ejecutado
- [ ] Cachés limpiados y reconstruidos
- [ ] Permisos 775 en storage/
- [ ] Extensión GD verificada (o fix temporal aplicado)
- [ ] Logs no muestran errores al intentar guardar
- [ ] Logo se guarda correctamente
- [ ] Logo se muestra en la interfaz

### Comando Todo-en-Uno (ejecutar en Hostinger):

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos && \
git pull origin main && \
chmod -R 775 storage/ bootstrap/cache/ && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
php artisan route:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
echo "" && \
echo "✅ Fix aplicado completamente" && \
echo "" && \
echo "🧪 Verificando extensión GD:" && \
php -m | grep -i gd && \
echo "" && \
echo "📝 Ahora intenta subir el logo nuevamente" && \
echo "📋 Si hay error, ejecuta: tail -f storage/logs/laravel.log"
```

## 📞 Si Nada Funciona

1. **Revisar logs en tiempo real:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Mientras tanto, desde la interfaz web:**
   - Ir a `/configuracion/negocio`
   - Intentar subir logo
   - Observar qué error aparece en el log

3. **Capturar el error exacto:**
   - Copiar el stack trace completo
   - Buscar la línea que dice "Exception:" o "Error:"
   - Esa línea dirá exactamente qué está fallando

4. **Alternativa: Subir logo sin compresión**
   
   Si GD no está disponible y Hostinger no puede activarlo, podemos:
   - Desactivar compresión de logos (solo para logos, productos seguirán comprimidos)
   - Modificar el código para que solo use `store()` sin procesamiento

---

**Última Actualización**: 7 Noviembre 2025  
**Commit con Fix**: `f4aa788`  
**Estado**: Listo para aplicar en Hostinger
