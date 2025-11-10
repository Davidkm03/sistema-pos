# Optimización de Caché - Sistema POS

## Estado Actual: Hostinger Shared Hosting (Sin Redis)

**Limitación**: Hostinger Shared NO soporta Redis.

**Solución Actual**: File Cache con optimizaciones estratégicas.

---

## 1. Configuración Actual (.env)

```env
# Caché en archivos (mejor opción sin Redis)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Optimización de vistas y rutas
VIEW_CACHE=true
ROUTE_CACHE=true
CONFIG_CACHE=true
```

---

## 2. Comandos de Optimización (Ejecutar en Producción)

```bash
# Limpiar cachés anteriores
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generar cachés optimizados
php artisan config:cache      # Caché de configuración
php artisan route:cache       # Caché de rutas
php artisan view:cache        # Caché de vistas Blade

# Optimizar Composer autoload
composer install --optimize-autoloader --no-dev
```

---

## 3. Implementar Caché en Queries Críticos

### A. Dashboard (Más Usado)

```php
// app/Http/Controllers/HomeController.php
public function index()
{
    $empresaId = Auth::user()->empresa_id;
    
    // Caché del dashboard por 5 minutos
    $dashboardData = Cache::remember("dashboard_empresa_{$empresaId}", 300, function () {
        return [
            'total_sales_today' => Sale::whereDate('created_at', today())->sum('total'),
            'sales_count_today' => Sale::whereDate('created_at', today())->count(),
            'total_sales_month' => Sale::whereMonth('created_at', now()->month)->sum('total'),
            'recent_sales' => Sale::with(['customer', 'user'])
                ->latest()
                ->take(10)
                ->get(),
        ];
    });
    
    return view('dashboard', $dashboardData);
}
```

### B. Productos en POS (Mucho Tráfico)

```php
// app/Http/Controllers/PosController.php
public function index()
{
    $empresaId = Auth::user()->empresa_id;
    
    // Caché de productos activos por 10 minutos
    $products = Cache::remember("pos_products_empresa_{$empresaId}", 600, function () {
        return Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    });
    
    $categories = Cache::remember("categories_empresa_{$empresaId}", 600, function () {
        return Category::orderBy('name')->get();
    });
    
    return view('pos.index', compact('products', 'categories'));
}
```

### C. Reportes (Query Pesado)

```php
// app/Http/Controllers/ReportController.php
public function index(Request $request)
{
    $empresaId = Auth::user()->empresa_id;
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now());
    
    // Caché solo si no hay filtros personalizados
    $cacheKey = "reports_empresa_{$empresaId}_{$startDate}_{$endDate}";
    
    $data = Cache::remember($cacheKey, 1800, function () use ($startDate, $endDate) {
        return [
            'total_sales' => Sale::whereBetween('created_at', [$startDate, $endDate])
                ->sum('total'),
            'sales_count' => Sale::whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'top_products' => SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->take(10)
                ->with('product')
                ->get(),
        ];
    });
    
    return view('reports.index', $data);
}
```

### D. Configuración del Negocio (Rara Vez Cambia)

```php
// app/Models/BusinessSetting.php
public static function current()
{
    $empresaId = Auth::user()->empresa_id;
    
    // Caché por 1 hora (cambia muy poco)
    return Cache::remember("business_settings_empresa_{$empresaId}", 3600, function () {
        return BusinessSetting::where('user_id', Auth::id())->first();
    });
}
```

---

## 4. Invalidar Caché al Actualizar

### Ejemplo: Cuando se crea/actualiza un producto

```php
// app/Livewire/ProductManager.php
public function save()
{
    // ... lógica de guardado ...
    
    // Invalidar caché de productos
    $empresaId = Auth::user()->empresa_id;
    Cache::forget("pos_products_empresa_{$empresaId}");
    Cache::forget("categories_empresa_{$empresaId}");
    
    // ... resto del código ...
}

public function delete($id)
{
    // ... lógica de borrado ...
    
    // Invalidar caché
    $empresaId = Auth::user()->empresa_id;
    Cache::forget("pos_products_empresa_{$empresaId}");
    
    // ... resto del código ...
}
```

### Ejemplo: Cuando se crea una venta

```php
// app/Livewire/SaleCart.php
public function completeSale()
{
    // ... lógica de venta ...
    
    // Invalidar caché de dashboard y reportes
    $empresaId = Auth::user()->empresa_id;
    Cache::forget("dashboard_empresa_{$empresaId}");
    Cache::tags(["empresa_{$empresaId}", 'reports'])->flush();
    
    // ... resto del código ...
}
```

---

## 5. Estrategia de Caché por Duración

| Tipo de Dato | Duración Caché | Razón |
|--------------|----------------|-------|
| **Configuración Negocio** | 1 hora (3600s) | Cambia raramente |
| **Productos/Categorías** | 10 min (600s) | Actualizaciones ocasionales |
| **Dashboard** | 5 min (300s) | Datos en tiempo semi-real |
| **Reportes** | 30 min (1800s) | Queries pesados, datos históricos |
| **Sesiones** | 120 min (7200s) | Datos de usuario |

---

## 6. Optimizaciones Adicionales (Sin Caché)

### A. Eager Loading (Evitar N+1)

```php
// ❌ MAL (100 queries)
$sales = Sale::all();
foreach ($sales as $sale) {
    echo $sale->customer->name; // Query por cada sale
}

// ✅ BIEN (2 queries)
$sales = Sale::with('customer')->get();
foreach ($sales as $sale) {
    echo $sale->customer->name; // Sin query adicional
}
```

### B. Select Only What You Need

```php
// ❌ MAL
$products = Product::all(); // Trae todas las columnas

// ✅ BIEN
$products = Product::select('id', 'name', 'price', 'stock')->get();
```

### C. Chunk Large Datasets

```php
// ❌ MAL (consume mucha RAM)
$allSales = Sale::all();
foreach ($allSales as $sale) {
    // procesar
}

// ✅ BIEN (procesa en lotes)
Sale::chunk(100, function ($sales) {
    foreach ($sales as $sale) {
        // procesar
    }
});
```

---

## 7. Monitoreo de Rendimiento

### Laravel Debugbar (Solo Desarrollo)

```bash
composer require barryvdh/laravel-debugbar --dev
```

```php
// config/app.php (solo en local)
'providers' => [
    Barryvdh\Debugbar\ServiceProvider::class,
],
```

**Qué mide**:
- Tiempo de queries
- Número de queries (detecta N+1)
- Uso de memoria
- Tiempo de respuesta

---

## 8. Plan de Migración a Redis

### Fase 1: Ahora (File Cache)
```env
CACHE_DRIVER=file
```
- ✅ Sin costo
- ✅ Implementar AHORA
- ⚠️ Moderado rendimiento

### Fase 2: Próximo Mes (Upstash Redis Gratis)
```bash
composer require predis/predis
```

```env
REDIS_HOST=us1-solid-possum-12345.upstash.io
REDIS_PASSWORD=AZX...abc123
REDIS_PORT=6379
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

**Upstash Free Tier**:
- ✅ 10,000 comandos/día
- ✅ 30MB storage
- ✅ Gratis para siempre
- ✅ Perfecto para 10-50 empresas

**Registro** (5 minutos):
1. https://upstash.com → Sign Up (GitHub/Google)
2. Create Database → Global (latencia baja)
3. Copiar credenciales (REST API o Redis CLI)
4. Pegar en `.env`

### Fase 3: 50+ Empresas (VPS con Redis Local)
- Hostinger VPS 2: $8.99/mes
- Redis instalado localmente
- Latencia mínima
- Control total

---

## 9. Comandos de Mantenimiento (Ejecutar Semanalmente)

```bash
# SSH a producción
ssh -p 65002 u301792158@156.67.73.78

cd domains/paginaswebscolombia.com/public_html/sistemapos

# Limpiar cachés viejos
php artisan cache:clear
php artisan view:clear

# Regenerar cachés optimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar Composer
composer dump-autoload --optimize
```

---

## 10. Testing de Rendimiento

### Verificar Caché Funciona

```php
// routes/web.php (temporal, para testing)
Route::get('/test-cache', function () {
    $start = microtime(true);
    
    // Primera llamada (sin caché)
    Cache::forget('test_query');
    $result1 = Cache::remember('test_query', 60, function () {
        return DB::table('sales')->count();
    });
    $time1 = microtime(true) - $start;
    
    // Segunda llamada (con caché)
    $start2 = microtime(true);
    $result2 = Cache::get('test_query');
    $time2 = microtime(true) - $start2;
    
    return [
        'sin_cache' => $time1 . ' segundos',
        'con_cache' => $time2 . ' segundos',
        'mejora' => round(($time1 / $time2), 2) . 'x más rápido',
    ];
});
```

---

## 11. Resumen de Implementación

### Cambios Inmediatos (HOY):

1. **Verificar .env**:
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

2. **Limpiar y Optimizar**:
```bash
php artisan optimize:clear
php artisan optimize
```

3. **Agregar Caché a 3 Controllers Clave**:
- `HomeController` (Dashboard)
- `PosController` (POS Index)
- `ReportController` (Reportes)

### Beneficios Esperados:
- ⚡ Dashboard: 60-80% más rápido
- ⚡ POS: 40-60% más rápido
- ⚡ Reportes: 70-90% más rápido
- 💾 Menor carga en MySQL

---

**Fecha**: 2025-11-10  
**Estado**: File Cache en Hostinger Shared  
**Próximo Paso**: Migrar a Upstash Redis (gratis) cuando sea posible
