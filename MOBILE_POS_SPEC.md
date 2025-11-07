# 📱 POS Móvil "Friendly" - Especificación Técnica

## 🎯 Objetivo

Un POS optimizado para **uso con una sola mano**, que funcione **offline**, sea **rápido para cobrar** y **minimice errores**. Pensado para iOS/Android (PWA) en pantallas de **360-430px** de ancho.

---

## 1️⃣ Principios de UX

### ✋ Una Sola Mano
- **Botones grandes**: Mínimo 44-56px de altura (Apple/Google guidelines)
- **Zonas táctiles inferiores**: Acciones principales en la mitad inferior de la pantalla
- **Pulgar como centro**: Todo alcanzable con el pulgar derecho o izquierdo

### ⚡ 2-3 Toques Máximo para Cobrar
**Flujo ideal:**
1. Tap en producto o escanear código
2. Tap en "Cobrar"
3. Tap en método de pago → Listo

**Flujo completo (máximo):**
1. Buscar producto
2. Ajustar cantidad
3. Cobrar
4. Seleccionar método de pago
5. Confirmar → Listo

### 🎯 Enfoque en la Tarea
- Flujo lineal: una pantalla = una tarea
- Reducir distracciones: ocultar info secundaria
- Contexto claro: breadcrumbs, títulos descriptivos
- Progreso visible: indicadores de paso 1/3, 2/3, etc.

### 💫 Feedback Inmediato
- **Vibración ligera** (haptic): al agregar producto, confirmar venta
- **Toasts claros**: "✅ Producto agregado", "⚠️ Stock bajo"
- **Estados visibles**: Loading, Success, Error con iconos y colores
- **Animaciones suaves**: transiciones de 200-300ms

### ♿ Accesible
- **Contraste AA**: Mínimo 4.5:1 para texto normal
- **Texto escalable**: Usar `rem` en lugar de `px`
- **Lector de pantalla**: ARIA labels en todos los botones
- **Foco visible**: Border 2px en elementos con foco

### 📶 Offline-First
- **Flujo completo offline**: Vender, ver productos, revisar ventas
- **Sincronización en background**: Queue de ventas pendientes
- **Indicador de estado**: Badge en header (●Online / ●Offline)
- **Caché inteligente**: Productos, categorías, clientes frecuentes

### 🔄 Errores Recuperables
- **Deshacer**: Botón "Deshacer" en toast (5 segundos)
- **Editar**: Modificar venta antes de confirmar
- **Reintentar**: Si falla pago, reintentar sin perder info
- **Sin callejones sin salida**: Siempre hay botón "Volver" o "Cancelar"

---

## 2️⃣ Arquitectura de Navegación (Mobile-First)

### 📊 Estructura de Capas

```
┌─────────────────────────── Header (64px) ──────────────────────────┐
│ [☰] Buscar productos...              Tienda X  ●Offline           │
└────────────────────────────────────────────────────────────────────┘
                                ↓
┌─────────────────── Contenido Principal (dinámico) ─────────────────┐
│                                                                     │
│  • Vender: Grid de productos + carrito bottom sheet                │
│  • Órdenes: Lista de ventas del día                                │
│  • Inventario: Lista de productos con stock                        │
│  • Reportes: Gráficas y métricas                                   │
│  • Más: Configuración, perfil, ayuda                               │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
                                ↓
┌─────────────────── Carrito Bottom Sheet (plegable) ────────────────┐
│ 🛒 3 items - $45.00                               [↓ Ver carrito]  │
└────────────────────────────────────────────────────────────────────┘
                                ↓
┌─────────────────── FAB (Floating Action Button) ───────────────────┐
│                              [+]                                    │
│                         (Cobrar / Escanear)                         │
└────────────────────────────────────────────────────────────────────┘
                                ↓
┌──────────────────── Bottom Navigation (56px) ──────────────────────┐
│  Vender  │  Órdenes  │  Inventario  │  Reportes  │  Más            │
└────────────────────────────────────────────────────────────────────┘
```

### 🔢 Bottom Navigation (5 tabs máximo)

| Tab | Icono | Función | Badge |
|-----|-------|---------|-------|
| **Vender** | 🛒 | POS principal | Items en carrito (3) |
| **Órdenes** | 📋 | Historial de ventas | Pendientes de sync (2) |
| **Inventario** | 📦 | Stock de productos | Stock bajo (5) |
| **Reportes** | 📊 | Métricas del día | - |
| **Más** | ⋯ | Config y opciones | - |

### 🔘 FAB (Floating Action Button)

**Contexto: Vender**
- **Primario**: 💳 Cobrar (si hay items en carrito)
- **Secundario**: 📷 Escanear código

**Contexto: Inventario**
- **Primario**: ➕ Agregar producto rápido

**Contexto: Órdenes**
- **Primario**: 🔄 Sincronizar ventas pendientes

### 📱 Header Compacto (64px)

```html
┌────────────────────────────────────────────────┐
│ [☰] 🔍 Buscar...      Tienda X  ●Offline      │
└────────────────────────────────────────────────┘
    ↑      ↑                ↑         ↑
   Menú  Buscar         Contexto  Conexión
```

**Elementos:**
1. **Menú hamburguesa** (izquierda): Drawer con opciones avanzadas
2. **Buscador**: Input expandible al hacer tap
3. **Contexto**: Tienda actual o turno activo
4. **Indicador de conexión**: Dot verde/rojo + tooltip

---

## 3️⃣ Flujo Clave: Venta Rápida

### 📍 Paso 1: Tab "Vender" (vista por defecto)

```
┌────────────────────────────────────────────────┐
│ [☰] 🔍 Buscar productos...    Turno 1  ●Online │
├────────────────────────────────────────────────┤
│                                                 │
│ Categorías:                                     │
│ ┌────────┬────────┬────────┬────────┐          │
│ │ Bebidas│  Snacks│Comidas │  Otros │ →        │
│ └────────┴────────┴────────┴────────┘          │
│                                                 │
│ Productos Recientes:                            │
│ ┌────────────────────────────────┐              │
│ │ 🥤 Coca Cola 500ml             │              │
│ │ $2.50          [Agregar +1]    │              │
│ └────────────────────────────────┘              │
│ ┌────────────────────────────────┐              │
│ │ 🍫 Snickers                    │              │
│ │ $1.50          [Agregar +1]    │              │
│ └────────────────────────────────┘              │
│                                                 │
├────────────────────────────────────────────────┤
│ 🛒 2 items - $4.00         [↓ Ver carrito]     │
├────────────────────────────────────────────────┤
│                      [+]                        │
│                 (Escanear código)               │
└────────────────────────────────────────────────┘
│  Vender  │  Órdenes  │  Inventario  │ ...       │
└────────────────────────────────────────────────┘
```

### 📍 Paso 2: Agregar Productos (3 opciones)

#### Opción A: 📷 Escanear Código (EAN/QR)

**Tap en FAB "Escanear"**:
```
┌────────────────────────────────────────────────┐
│                  [X] Cerrar                     │
├────────────────────────────────────────────────┤
│                                                 │
│           ┌─────────────────────┐               │
│           │                     │               │
│           │   [Cámara activa]   │               │
│           │                     │               │
│           │   Apunta al código  │               │
│           │      de barras      │               │
│           │                     │               │
│           └─────────────────────┘               │
│                                                 │
│              Código detectado:                  │
│                7501234567890                    │
│                                                 │
│  ✅ Coca Cola 500ml agregada                   │
│                                                 │
└────────────────────────────────────────────────┘
```

**Tecnología**: [html5-qrcode](https://github.com/mebjas/html5-qrcode) o QuaggaJS

#### Opción B: 🔍 Búsqueda Rápida

**Tap en buscador del header**:
```
┌────────────────────────────────────────────────┐
│ [←] 🔍 coca___                                 │
├────────────────────────────────────────────────┤
│ Resultados (3):                                 │
│                                                 │
│ ┌────────────────────────────────┐              │
│ │ 🥤 Coca Cola 500ml             │              │
│ │ $2.50 • Stock: 45  [Agregar]  │              │
│ └────────────────────────────────┘              │
│ ┌────────────────────────────────┐              │
│ │ 🥤 Coca Cola 1L                │              │
│ │ $4.00 • Stock: 23  [Agregar]  │              │
│ └────────────────────────────────┘              │
│ ┌────────────────────────────────┐              │
│ │ 🥤 Coca Cola Zero 500ml        │              │
│ │ $2.50 • Stock: 12  [Agregar]  │              │
│ └────────────────────────────────┘              │
│                                                 │
└────────────────────────────────────────────────┘
```

**Features**:
- Búsqueda instant search (debounce 300ms)
- Busca en: nombre, SKU, código de barras
- Resalta coincidencias
- Muestra stock disponible
- Botón "Agregar" directo

#### Opción C: 🏷️ Categorías

**Swipe horizontal en categorías**:
```
┌────────────────────────────────────────────────┐
│ Bebidas (23 productos):                         │
│                                                 │
│ ┌──────┬──────┬──────┬──────┐                  │
│ │ 🥤   │ 🥤   │ 🧃   │ 🧃   │                  │
│ │ Coca │ Pepsi│Jugo  │Agua  │                  │
│ │ $2.50│ $2.30│$3.00 │$1.00 │                  │
│ │ [+]  │ [+]  │ [+]  │ [+]  │                  │
│ └──────┴──────┴──────┴──────┘                  │
│ ┌──────┬──────┬──────┬──────┐                  │
│ │ 🍺   │ 🍺   │ ☕   │ ☕   │                  │
│ │Cerveza│Vino │Café  │Té    │                  │
│ │ $5.00│ $8.00│$2.00 │$1.50 │                  │
│ │ [+]  │ [+]  │ [+]  │ [+]  │                  │
│ └──────┴──────┴──────┴──────┘                  │
│                                                 │
└────────────────────────────────────────────────┘
```

**Grid**: 2 columnas en 360px, 3 en 400px+

### 📍 Paso 3: Carrito Compacto (Bottom Sheet)

#### Estado Colapsado (Chip)
```
┌────────────────────────────────────────────────┐
│ 🛒 3 items • $45.50          [↑ Ver carrito]  │
└────────────────────────────────────────────────┘
```

#### Estado Expandido (Swipe up o tap)
```
┌────────────────────────────────────────────────┐
│                     ━━━                         │ ← Handle para drag
│ Carrito (3 items)                 [✕] Cerrar   │
├────────────────────────────────────────────────┤
│ 🥤 Coca Cola 500ml              $2.50          │
│    [-] 2 [+]                                    │
│    ────────────────────────────────────        │
│ 🍫 Snickers                      $1.50         │
│    [-] 1 [+]                                    │
│    ────────────────────────────────────        │
│ 🍪 Oreo                          $3.00         │
│    [-] 1 [+]                                    │
├────────────────────────────────────────────────┤
│ Subtotal:                        $7.00         │
│ IVA (19%):                       $1.33         │
│ ──────────────────────────────────────         │
│ TOTAL:                          $8.33          │
├────────────────────────────────────────────────┤
│          [💳 COBRAR - $8.33]                   │
└────────────────────────────────────────────────┘
```

**Interacciones**:
- **Swipe up/down**: Expandir/colapsar
- **Tap en handle**: Toggle expandir
- **Swipe item left**: Eliminar producto
- **Tap [-]/[+]**: Ajustar cantidad
- **Tap [✕]**: Vaciar carrito (con confirmación)

### 📍 Paso 4: Cobrar (Métodos de Pago)

**Tap en botón "COBRAR"**:
```
┌────────────────────────────────────────────────┐
│ [←] Cobrar $8.33                                │
├────────────────────────────────────────────────┤
│ Método de pago:                                 │
│                                                 │
│ ┌──────────┬──────────┐                         │
│ │ 💵       │ 💳       │                         │
│ │ Efectivo │ Tarjeta  │                         │
│ │ ✓ Activo │          │                         │
│ └──────────┴──────────┘                         │
│ ┌──────────┬──────────┐                         │
│ │ 🔗       │ 📱       │                         │
│ │ Link     │ Billetera│                         │
│ │          │          │                         │
│ └──────────┴──────────┘                         │
│                                                 │
│ Monto recibido:                                 │
│ ┌────────────────────────────────┐              │
│ │ $ 10.00                        │              │
│ └────────────────────────────────┘              │
│                                                 │
│ Cambio: $1.67 ✅                                │
│                                                 │
│ ┌─────────────────────────────────────┐         │
│ │     [CONFIRMAR VENTA - $8.33]       │         │
│ └─────────────────────────────────────┘         │
│                                                 │
└────────────────────────────────────────────────┘
```

**Features**:
- **Efectivo**: Calculadora integrada, muestra cambio
- **Tarjeta**: Integración con terminal o link de pago
- **Link**: Genera link de pago (WhatsApp, SMS, email)
- **Billetera**: Apple Pay, Google Pay, Samsung Pay

#### Propina (Opcional)

**Después de seleccionar método**:
```
┌────────────────────────────────────────────────┐
│ ¿Agregar propina? (Opcional)                   │
│                                                 │
│ ┌──────┬──────┬──────┬────────┐                │
│ │ 10%  │ 15%  │ 20%  │ Otra   │                │
│ │ $0.83│ $1.25│ $1.67│ $____  │                │
│ └──────┴──────┴──────┴────────┘                │
│                                                 │
│ [Sin propina]    [Continuar]                   │
└────────────────────────────────────────────────┘
```

### 📍 Paso 5: Confirmar y Generar Recibo

**Después de confirmar venta**:
```
┌────────────────────────────────────────────────┐
│              ✅ Venta Exitosa                   │
│                                                 │
│             Ticket #00123                       │
│              $8.33 pagado                       │
│                                                 │
│ ┌──────────┬──────────┬──────────┐              │
│ │ 📄       │ 📱       │ 🖨️       │              │
│ │ Ver PDF  │ WhatsApp │ Imprimir │              │
│ └──────────┴──────────┴──────────┘              │
│                                                 │
│ ┌─────────────────────────────────────┐         │
│ │     [NUEVA VENTA]                   │         │
│ └─────────────────────────────────────┘         │
│                                                 │
│ Sincronizando... ●●○○                           │
│                                                 │
└────────────────────────────────────────────────┘
```

**Opciones de recibo**:
1. **PDF**: Genera PDF y lo abre en visor nativo
2. **WhatsApp**: Comparte ticket por WhatsApp
3. **QR**: Genera QR para que cliente descargue
4. **Imprimir**: Si hay impresora Bluetooth conectada

### 📍 Paso 6: Sincronización (Offline)

**Si está offline durante la venta**:
```
┌────────────────────────────────────────────────┐
│ ⚠️ Modo Offline                                 │
│                                                 │
│ Venta guardada localmente                       │
│ Se sincronizará cuando haya conexión            │
│                                                 │
│ Ventas pendientes: 3                            │
│                                                 │
│ [Ver cola de sincronización]                   │
└────────────────────────────────────────────────┘
```

**Badge en tab "Órdenes"**:
- Muestra número de ventas pendientes de sincronizar
- Color naranja si hay pendientes
- Intenta sincronizar automáticamente cada 30 segundos
- Botón manual "Sincronizar ahora"

---

## 4️⃣ Especificaciones Técnicas

### 📦 Stack Tecnológico

| Componente | Tecnología | Justificación |
|------------|-----------|---------------|
| **PWA** | Laravel + Workbox | Service Worker para offline |
| **UI Framework** | Livewire + Alpine.js | Reactive sin compilación pesada |
| **CSS** | Tailwind CSS | Mobile-first utilities |
| **Icons** | Heroicons | SVG optimizados |
| **Gestures** | Hammer.js | Swipe, pan, tap |
| **Scanner** | html5-qrcode | EAN/QR desde cámara |
| **Haptics** | Navigator.vibrate() | Feedback táctil |
| **Storage** | IndexedDB | Base de datos local |
| **Sync** | Background Sync API | Sincronización offline |

### 🎨 Diseño Responsivo

#### Breakpoints Mobile

```css
/* Extra Small (iPhone SE, Android compact) */
@media (max-width: 374px) {
  --grid-cols: 2;
  --font-base: 14px;
  --button-height: 44px;
}

/* Small (iPhone 12/13/14, Android standard) */
@media (min-width: 375px) and (max-width: 429px) {
  --grid-cols: 2;
  --font-base: 15px;
  --button-height: 48px;
}

/* Medium (iPhone Plus, Android large) */
@media (min-width: 430px) {
  --grid-cols: 3;
  --font-base: 16px;
  --button-height: 52px;
}
```

#### Zonas Táctiles (Thumb Zone)

```
┌────────────────────────────────────────┐
│ 🔴 Difícil (Top)                       │ ← Header (solo info)
├────────────────────────────────────────┤
│ 🟡 Moderado (Middle)                   │ ← Contenido scrollable
│                                        │
├────────────────────────────────────────┤
│ 🟢 Fácil (Bottom)                      │ ← Acciones principales
│   • FAB                                │
│   • Bottom Nav                         │
│   • Bottom Sheet                       │
└────────────────────────────────────────┘
```

### 🔧 Service Worker (Offline)

#### Cache Strategy

```javascript
// productos, categorías, imágenes
workbox.strategies.CacheFirst({
  cacheName: 'static-resources',
  plugins: [
    new workbox.expiration.Plugin({
      maxEntries: 200,
      maxAgeSeconds: 7 * 24 * 60 * 60, // 7 días
    }),
  ],
});

// API calls (ventas, clientes)
workbox.strategies.NetworkFirst({
  cacheName: 'api-cache',
  networkTimeoutSeconds: 5,
  plugins: [
    new workbox.backgroundSync.Plugin('ventas-queue', {
      maxRetentionTime: 24 * 60, // 24 horas
    }),
  ],
});
```

#### Background Sync

```javascript
// Encolar ventas offline
if ('serviceWorker' in navigator && 'SyncManager' in window) {
  navigator.serviceWorker.ready.then(sw => {
    return sw.sync.register('sync-ventas');
  });
}

// Listener en service worker
self.addEventListener('sync', event => {
  if (event.tag === 'sync-ventas') {
    event.waitUntil(syncPendingSales());
  }
});
```

### 📊 Estructura de Datos (IndexedDB)

```javascript
// Stores locales
{
  productos: {
    keyPath: 'id',
    indexes: ['sku', 'barcode', 'category_id']
  },
  ventas_pendientes: {
    keyPath: 'local_id',
    indexes: ['timestamp', 'synced']
  },
  carrito: {
    keyPath: 'session_id'
  }
}
```

---

## 5️⃣ Métricas de Éxito

### ⏱️ Performance

| Métrica | Target | Método |
|---------|--------|--------|
| **First Contentful Paint** | < 1.5s | Lighthouse |
| **Time to Interactive** | < 3s | Lighthouse |
| **Tap to action** | < 100ms | Chrome DevTools |
| **Scroll fluidity** | 60 FPS | Performance Monitor |

### 📈 UX

| Métrica | Target | Método |
|---------|--------|--------|
| **Toques para venta simple** | ≤ 3 | User testing |
| **Tiempo de venta promedio** | < 30s | Analytics |
| **Tasa de error** | < 5% | Error tracking |
| **Satisfacción (NPS)** | > 8/10 | Encuesta |

---

## 6️⃣ Roadmap de Implementación

### Fase 1: MVP (2 semanas)
- ✅ Bottom navigation
- ✅ Carrito bottom sheet
- ✅ Búsqueda rápida
- ✅ Métodos de pago básicos
- ✅ Recibo PDF

### Fase 2: Offline (1 semana)
- ✅ Service Worker
- ✅ IndexedDB cache
- ✅ Background sync
- ✅ Queue de ventas

### Fase 3: Avanzado (2 semanas)
- ✅ Escáner de códigos
- ✅ Propinas
- ✅ Impresión Bluetooth
- ✅ Gestos (swipe)
- ✅ Haptic feedback

### Fase 4: Optimización (1 semana)
- ✅ Performance tuning
- ✅ A11y audit
- ✅ User testing
- ✅ Bug fixes

---

## 📚 Referencias

- [Material Design Mobile](https://material.io/design/platform-guidance/android-bars.html)
- [Apple Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/ios)
- [PWA Best Practices](https://web.dev/pwa-checklist/)
- [Offline UX Considerations](https://developers.google.com/web/fundamentals/instant-and-offline/offline-ux)

---

**Versión**: 1.0.0  
**Fecha**: 7 Noviembre 2025  
**Estado**: 📝 Especificación → 🚧 Implementación próxima
