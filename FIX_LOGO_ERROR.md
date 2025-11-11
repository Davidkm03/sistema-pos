# 🔧 Fix: Error 500 al Guardar Logo en Configuración

## Cambios Realizados

### 1. BusinessSettingsManager.php
- ✅ Añadido uso del helper `process_and_save_image()` para comprimir logos
- ✅ Agregado manejo de errores con try-catch y fallback
- ✅ Corregido el uso de `Storage::disk('public')->delete()`
- ✅ Añadida importación de `Illuminate\Support\Facades\Log`
- ✅ Mejorado el método `removeLogo()` para actualizar la BD

### 2. BusinessSetting.php (Model)
- ✅ Mejorado el accessor `getLogoUrlAttribute()` para manejar diferentes formatos de ruta
- ✅ Añadido soporte para rutas relativas `logos/xxxxx.jpg`
- ✅ Fallback a `asset('storage/...')` si la ruta no es HTTP

## Pasos para Aplicar el Fix

### En Local:

```bash
cd /Users/admin/Downloads/sistema-pos

# Verificar cambios
git status

# Agregar cambios
git add app/Livewire/BusinessSettingsManager.php
git add app/Models/BusinessSetting.php

# Commit
git commit -m "fix: Error 500 al guardar logo en configuración de negocio

- Usar helper process_and_save_image() para comprimir logos
- Añadir manejo de errores con try-catch
- Mejorar accessor logo_url para diferentes formatos de ruta
- Corregir método removeLogo() para actualizar BD"

# Push
git push origin main
```

### En Hostinger:

```bash
ssh u301792158@paginaswebscolombia.com

cd domains/paginaswebscolombia.com/public_html/sistemapos

# Pull de los cambios
git pull origin main

# Limpiar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar permisos del directorio logos
chmod -R 775 storage/php artisan migratenpm install
npm run buildapp/public/logos

# Probar
echo "✅ Fix aplicado!"
```

## Causa del Problema

El error 500 se debía a:

1. **Falta de importación de Log**: El código usaba `\Log::error()` sin importar la facade
2. **Storage::delete() sin disco**: Debía usar `Storage::disk('public')->delete()`
3. **Sin manejo de errores**: Si `process_and_save_image()` fallaba, no había fallback

## Solución Implementada

### Código Anterior (Con Error):
```php
if ($this->business_logo) {
    if ($this->existing_logo) {
        Storage::delete($this->existing_logo); // ❌ Sin disco especificado
    }
    $data['business_logo'] = $this->business_logo->store('logos', 'public');
}
```

### Código Nuevo (Corregido):
```php
if ($this->business_logo) {
    if ($this->existing_logo) {
        Storage::disk('public')->delete($this->existing_logo); // ✅ Disco especificado
    }
    
    try {
        // Comprimir imagen
        $logoPath = process_and_save_image($this->business_logo, 'logos', 400, 90);
        $data['business_logo'] = $logoPath;
    } catch (\Exception $e) {
        Log::error('Error saving business logo: ' . $e->getMessage()); // ✅ Log importado
        // Fallback: guardar sin compresión
        $data['business_logo'] = $this->business_logo->store('logos', 'public');
    }
}
```

## Mejoras Adicionales

### Accessor logo_url Mejorado:
```php
public function getLogoUrlAttribute()
{
    if (!$this->business_logo) {
        return null;
    }

    // Si ya es URL completa
    if (str_starts_with($this->business_logo, 'http')) {
        return $this->business_logo;
    }

    // Si es ruta relativa logos/xxxxx.jpg
    if (str_starts_with($this->business_logo, 'logos/')) {
        return asset('storage/' . $this->business_logo); // ✅ Funciona en Hostinger
    }

    return Storage::url($this->business_logo);
}
```

## Testing

### Probar el Fix:

1. Ir a: `/configuracion/negocio`
2. Subir una imagen de logo
3. Click en "Guardar"
4. Verificar que:
   - ✅ No hay error 500
   - ✅ El logo se muestra correctamente
   - ✅ El archivo está comprimido (~70-80% más pequeño)
   - ✅ La imagen está en `storage/app/public/logos/`

### Verificar Compresión:

```bash
# Ver tamaños de archivos en logos/
ls -lh storage/app/public/logos/

# Debería mostrar archivos más pequeños (ej: 50KB en lugar de 500KB)
```

## Troubleshooting

### Si sigue dando error 500:

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Mientras tanto, subir logo en la interfaz
```

### Si el logo no se muestra:

```bash
# Verificar permisos
ls -la storage/app/public/logos/

# Deberían ser 755 o 775
chmod -R 775 storage/app/public/logos/

# Verificar symlink
ls -la public/storage

# Debería apuntar a ../storage/app/public
```

### Si la extensión GD no está instalada:

```bash
# Verificar extensiones PHP
php -m | grep -i gd

# Si no aparece, contactar a Hostinger para activarla
# (En su plan debería estar disponible)
```

---

**Fecha**: 7 de Noviembre, 2025  
**Estado**: ✅ Corregido y listo para deployment
