# 📱 Guía de Despliegue - POS Móvil

## ✅ Implementación Completada

Se ha implementado un **POS Móvil optimizado** con las siguientes características:

### 🎯 Funcionalidades Principales

1. **Layout Mobile-First**
   - Header fijo con buscador y estado online/offline
   - Contenido scrollable con grid de productos
   - Bottom navigation con 5 tabs
   - FAB (Floating Action Button) en zona del pulgar
   - Soporte para safe areas (iPhone notch)

2. **Grid de Productos Responsive**
   - 2 columnas en móvil (360px)
   - 3 columnas en tablet (640px+)
   - Cards con imagen, nombre, precio y botón agregar
   - Badge de stock bajo

3. **Bottom Sheet del Carrito**
   - Estado colapsado: resumen + total
   - Estado expandido: lista completa con controles
   - Toggle con click o swipe
   - Cálculo automático de subtotal, IVA y total

4. **Modal de Checkout**
   - 4 métodos de pago: Efectivo, Tarjeta, Link, Billetera
   - Sistema de propinas (5%, 10%, 15%, personalizada)
   - Validación de monto para efectivo
   - Cálculo de cambio automático
   - Botones rápidos de denominaciones

5. **Procesamiento de Ventas**
   - Componente Livewire `MobileSaleCheckout`
   - Validación de stock en tiempo real
   - Reducción automática de inventario
   - Generación de recibo de venta
   - Integración con modelos Sale, SaleItem, PaymentDetail

6. **UX Optimizado**
   - Touch targets mínimo 44px
   - Vibración háptica para feedback
   - Transiciones suaves (0.3s)
   - Feedback visual inmediato
   - Diseño para uso con una sola mano

---

## 🚀 Pasos para Desplegar en Hostinger

### 1. Conectar por SSH

```bash
ssh u301792158@sistemapos.paginaswebscolombia.com
```

### 2. Navegar al directorio del proyecto

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos
```

### 3. Descargar cambios del repositorio

```bash
git pull origin main
```

**Salida esperada:**
```
remote: Enumerating objects: 29, done.
remote: Counting objects: 100% (29/29), done.
...
Updating 3032a94..eae3981
Fast-forward
 MOBILE_POS_SPEC.md                                    | 853 ++++++++++++++++++
 app/Http/Controllers/PosController.php                |  15 +
 app/Livewire/MobileSaleCheckout.php                   | 251 ++++++
 resources/views/livewire/mobile-sale-checkout.blade.php|   3 +
 resources/views/pos/mobile.blade.php                  | 435 +++++++++
 routes/web.php                                         |   1 +
 6 files changed, 1566 insertions(+)
```

### 4. Limpiar cachés de Laravel

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 5. Regenerar cachés optimizados

```bash
php artisan config:cache
php artisan route:cache
```

### 6. Ejecutar migraciones pendientes (si hay)

```bash
php artisan migrate --force
```

**Nota:** Esto ejecutará la migración de columnas de facturación que se creó anteriormente.

---

## 📲 Acceso al POS Móvil

### URL de Acceso

```
https://sistemapos.paginaswebscolombia.com/pos/mobile
```

### Requisitos de Acceso

- Usuario con permiso `access-pos`
- Sesión activa (login previo)
- Navegador móvil recomendado: Chrome Mobile, Safari iOS

### Primera Prueba

1. Accede desde tu smartphone a la URL
2. Inicia sesión con tus credenciales
3. Navega a `/pos/mobile`
4. Deberías ver:
   - ✅ Header con buscador
   - ✅ Grid de productos (2 columnas)
   - ✅ Categorías scrollables
   - ✅ Bottom navigation (5 tabs)

---

## 🧪 Pruebas Funcionales

### Test 1: Agregar Productos al Carrito

1. Click en "Agregar" de cualquier producto
2. Deberías sentir vibración (si tu dispositivo lo soporta)
3. Ver bottom sheet del carrito aparecer
4. Badge con número de items en tab "Vender"

### Test 2: Modificar Cantidades

1. Click en el carrito para expandirlo
2. Usa botones + / - para ajustar cantidades
3. Verifica que el total se actualice en tiempo real
4. Reduce a 0 para eliminar item

### Test 3: Proceso de Checkout

1. Con items en el carrito, click en FAB (botón flotante azul)
2. Se abre modal de checkout
3. Selecciona método de pago "Efectivo"
4. Ingresa monto recibido (ejemplo: 50000)
5. Verifica cálculo de cambio
6. Agrega propina (5% o 10%)
7. Click en "Cobrar"

### Test 4: Procesamiento de Venta

1. Completa el checkout
2. Deberías ver:
   - Mensaje de éxito
   - Vibración de confirmación
   - Carrito limpiado
3. Verifica en `/sales` que la venta se registró
4. Confirma que el stock se redujo

---

## 🐛 Troubleshooting

### Problema 1: Error 500 al procesar venta

**Causa posible:** Columnas faltantes en `business_settings`

**Solución:**
```bash
php artisan migrate --force
```

### Problema 2: No se ven productos

**Causa posible:** No hay productos con stock > 0

**Solución:**
1. Accede a `/products`
2. Edita productos y asigna stock
3. Recarga `/pos/mobile`

### Problema 3: Modal no se abre

**Causa posible:** Alpine.js no cargó correctamente

**Solución:**
1. Abre DevTools (F12)
2. Verifica errores en Console
3. Confirma que `Alpine` está definido
4. Recarga con Ctrl+Shift+R

### Problema 4: Estilos rotos

**Causa posible:** Assets de Vite no compilados

**Solución:**
```bash
npm run build
```

### Problema 5: Vibración no funciona

**Causa posible:** Navegador no soporta Vibration API o permisos denegados

**Solución:**
- Es normal, solo funciona en HTTPS
- Safari iOS requiere interacción del usuario
- Chrome Android funciona sin restricciones

---

## 📊 Verificación Post-Despliegue

### Checklist de Validación

- [ ] La ruta `/pos/mobile` es accesible
- [ ] Se cargan productos correctamente
- [ ] Se puede agregar items al carrito
- [ ] El bottom sheet se expande/colapsa
- [ ] El modal de checkout se abre
- [ ] Los métodos de pago están disponibles
- [ ] Se puede procesar una venta de prueba
- [ ] El stock se reduce correctamente
- [ ] La venta aparece en `/sales`
- [ ] Los botones son touch-friendly (44px+)
- [ ] El layout es responsive

### Comandos de Verificación

**Ver últimas ventas:**
```bash
php artisan tinker
>>> \App\Models\Sale::latest()->take(5)->get(['id', 'total', 'payment_method', 'created_at']);
```

**Ver productos con bajo stock:**
```bash
>>> \App\Models\Product::where('stock', '<=', DB::raw('min_stock'))->get(['name', 'stock', 'min_stock']);
```

**Verificar configuración de negocio:**
```bash
>>> \App\Models\BusinessSetting::first(['billing_type', 'receipt_prefix', 'receipt_counter']);
```

---

## 🔐 Seguridad

### Middleware Aplicado

La ruta `/pos/mobile` está protegida por:

```php
Route::middleware(['permission:access-pos'])->group(function () {
    Route::get('/pos/mobile', [PosController::class, 'mobile'])->name('pos.mobile');
});
```

Solo usuarios con el permiso `access-pos` pueden acceder.

### Validación de Datos

El componente `MobileSaleCheckout` valida:
- ✅ Carrito no vacío
- ✅ Método de pago válido
- ✅ Stock suficiente antes de procesar
- ✅ Monto recibido >= total (para efectivo)

---

## 📈 Próximas Mejoras

### Fase 2: Offline-First (Pendiente)

- [ ] Service Worker para caché
- [ ] IndexedDB para almacenamiento local
- [ ] Sincronización en segundo plano
- [ ] Indicador de conexión con reintento

### Fase 3: Funcionalidades Avanzadas (Pendiente)

- [ ] Escáner de código de barras
- [ ] Búsqueda por voz
- [ ] Impresión de recibos Bluetooth
- [ ] Compartir recibo por WhatsApp/Email
- [ ] Estadísticas del día en tiempo real

### Fase 4: Optimización (Pendiente)

- [ ] Lazy loading de imágenes
- [ ] Virtual scrolling para +100 productos
- [ ] Minificación adicional de assets
- [ ] PWA manifest y app icons

---

## 📞 Soporte

Si encuentras algún problema durante el despliegue:

1. **Revisa los logs de Laravel:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Verifica permisos de archivos:**
   ```bash
   ls -la storage/
   ls -la bootstrap/cache/
   ```

3. **Confirma que el servidor web tiene acceso:**
   ```bash
   ps aux | grep php
   ```

---

## ✨ Resultado Final

Después del despliegue exitoso, tendrás:

✅ POS móvil completamente funcional  
✅ Experiencia optimizada para touch  
✅ Checkout rápido (2-3 toques)  
✅ Sistema de propinas integrado  
✅ Vibración háptica para feedback  
✅ Responsive design (móvil, tablet, desktop)  
✅ Integración completa con sistema de ventas existente  

**¡Listo para vender desde cualquier dispositivo móvil! 📱💰**

---

**Versión:** 1.0.0  
**Fecha:** 7 Noviembre 2025  
**Commit:** eae3981  
**Estado:** ✅ Producción
