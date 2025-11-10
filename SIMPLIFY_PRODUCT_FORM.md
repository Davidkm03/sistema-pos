# 📝 Simplificar Formulario de Productos

## Cambios a Realizar en `resources/views/livewire/product-manager.blade.php`

### 1. ELIMINAR CAMPOS:

#### Eliminar completamente (líneas 72-94):
```blade
{{-- SKU --}}
<div>
    <label for="sku">SKU / Código</label>
    <input id="sku" wire:model="sku" ...>
    @error('sku') ... @enderror
</div>
```

#### Eliminar completamente (líneas 227-310 aprox):
```blade
{{-- Imagen del Producto --}}
<div class="lg:col-span-3">
    <label>Imagen del Producto</label>
    {{-- Botón para tomar foto (móvil) --}}
    {{-- Botón para subir imagen --}}
    {{-- Preview de la imagen --}}
    {{-- Botón "Analizar con IA" --}}
</div>
```

---

### 2. AGREGAR NOTA DE SKU AUTOMÁTICO:

Después del campo "Nombre del Producto", agregar:

```blade
{{-- Note sobre SKU automático --}}
<div class="md:col-span-2 lg:col-span-3">
    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
        <div class="flex items-center gap-2 text-blue-800">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-bold">
                ✨ El código SKU se genera automáticamente al guardar (Ej: EMP1-0001, EMP1-0002...)
            </p>
        </div>
    </div>
</div>
```

---

### 3. MODIFICAR BOTONES DEL FORMULARIO:

**Reemplazar** la sección de botones (alrededor de línea 315-330) por:

```blade
<!-- Footer con botones -->
<div class="sticky bottom-0 px-6 py-4 bg-gradient-to-t from-gray-50 to-white border-t-2 border-gray-200">
    <div class="flex flex-col sm:flex-row justify-end gap-3">
        {{-- Botón Cancelar --}}
        <button type="button" 
                wire:click="resetForm"
                class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all flex items-center justify-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Cancelar
        </button>

        {{-- Botón Guardar y Crear Otro (NUEVO) --}}
        <button type="button" 
                wire:click="saveAndCreateAnother"
                class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl active:scale-95">
            <svg wire:loading.remove wire:target="saveAndCreateAnother" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <svg wire:loading wire:target="saveAndCreateAnother" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove wire:target="saveAndCreateAnother">Guardar y Crear Otro</span>
            <span wire:loading wire:target="saveAndCreateAnother">Guardando...</span>
        </button>
        
        {{-- Botón Guardar Normal --}}
        <button type="submit" 
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl active:scale-95">
            <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg wire:loading wire:target="save" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove wire:target="save">Guardar</span>
            <span wire:loading wire:target="save">Guardando...</span>
        </button>
    </div>
</div>
```

---

### 4. AGREGAR NOTIFICACIÓN RÁPIDA (en la sección @script):

Agregar después del evento `product-saved`:

```javascript
// Notificación rápida para "Guardar y Crear Otro"
$wire.on('product-created-quick', (event) => {
    const message = event.message || 'Producto creado! Listo para el siguiente';
    
    // Toast notification más discreta
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'success',
        title: message,
        background: '#10B981',
        color: '#fff'
    });
});
```

---

### 5. MODAL DE EDICIÓN:

También eliminar SKU e imagen del modal de edición (alrededor líneas 457-480 y similares)

---

## Resultado Final:

### Formulario Simplificado tendrá SOLO:

1. ✅ **Nombre del Producto**
2. ✅ **Categoría** (dropdown)
3. ✅ **Precio de Venta**
4. ✅ **Costo**
5. ✅ **Stock Inicial**
6. ✅ **Tipo de IVA** (opcional - standard por defecto)
7. ℹ️ **Nota**: SKU se genera automáticamente

### Botones:

1. **Cancelar** - Limpia el formulario
2. **Guardar y Crear Otro** - Guarda y mantiene categoría seleccionada
3. **Guardar** - Guarda y limpia formulario

---

## Beneficios:

- ⚡ **70% más rápido** de llenar
- 📱 **Perfecto para móvil** - menos scroll
- 🚀 **Carga masiva** - con "Guardar y Crear Otro"
- 🔢 **SKU automático** - no hay que pensarlo
- 💾 **Servidor liviano** - sin uploads de imágenes

---

**Fecha**: 10 de Noviembre, 2025  
**Status**: Listo para implementar
