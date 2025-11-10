# 🚀 Guía de Despliegue - Sistema de Descuentos y Propinas

## ⚠️ IMPORTANTE - Leer antes de desplegar

Este documento contiene los pasos necesarios para desplegar el sistema de descuentos y propinas en **PRODUCCIÓN**.

---

## 📋 Pre-requisitos

✅ Tener acceso SSH al servidor de producción
✅ Tener permisos para ejecutar comandos de Artisan
✅ Hacer backup de la base de datos antes de proceder
✅ Notificar a los usuarios que habrá mantenimiento breve

---

## 🔧 Pasos de Despliegue

### 1. **Hacer Backup de la Base de Datos** 🛡️

```bash
# En el servidor de producción
php artisan backup:run
# O manualmente con mysqldump:
mysqldump -u usuario -p nombre_base_datos > backup_antes_descuentos_$(date +%Y%m%d_%H%M%S).sql
```

### 2. **Subir los cambios al repositorio**

```bash
# En tu máquina local
git status
git add .
git commit -m "feat: Sistema de descuentos y propinas completo"
git push origin main
```

### 3. **Actualizar código en producción**

```bash
# En el servidor de producción
cd /ruta/a/tu/proyecto
git pull origin main
```

### 4. **Ejecutar las migraciones** 🔄

```bash
# En el servidor de producción

# Primero, verificar qué migraciones se ejecutarán (sin ejecutarlas)
php artisan migrate --pretend

# Si todo se ve bien, ejecutar las migraciones
php artisan migrate --force

# Las migraciones que se ejecutarán son:
# - 2025_11_10_164143_add_tip_amount_to_sales_table
# - 2025_11_10_165245_add_discount_fields_to_sales_table  
# - 2025_11_10_165300_add_discount_settings_to_business_settings
```

### 5. **Limpiar caché**

```bash
# En el servidor de producción
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

### 6. **Compilar assets (si usas Vite/Mix)**

```bash
# En el servidor de producción
npm run build
```

### 7. **Verificar que todo funciona** ✅

1. Ir a **Configuración del Negocio** → Sección "Descuentos"
2. Configurar los límites de descuento por rol
3. Probar una venta en el POS Desktop con descuento
4. Probar una venta en el POS Mobile con descuento
5. Verificar que los tickets imprimen correctamente
6. Revisar los reportes de descuentos

---

## 🗄️ Migraciones que se ejecutarán

### 1. `add_tip_amount_to_sales_table.php`
Agrega la columna `tip_amount` a la tabla `sales`

```sql
ALTER TABLE sales ADD tip_amount DECIMAL(10, 2) DEFAULT 0 AFTER retention_amount;
```

### 2. `add_discount_fields_to_sales_table.php`
Agrega las columnas de descuento a la tabla `sales`

```sql
ALTER TABLE sales ADD discount_percentage DECIMAL(5, 2) DEFAULT 0;
ALTER TABLE sales ADD discount_amount DECIMAL(10, 2) DEFAULT 0;
ALTER TABLE sales ADD discount_reason VARCHAR(255) NULL;
```

### 3. `add_discount_settings_to_business_settings.php`
Agrega configuración de descuentos a `business_settings`

```sql
ALTER TABLE business_settings ADD max_discount_cashier DECIMAL(5, 2) DEFAULT 15;
ALTER TABLE business_settings ADD max_discount_seller DECIMAL(5, 2) DEFAULT 10;
ALTER TABLE business_settings ADD max_discount_admin DECIMAL(5, 2) DEFAULT 100;
ALTER TABLE business_settings ADD require_discount_reason BOOLEAN DEFAULT TRUE;
ALTER TABLE business_settings ADD require_reason_from DECIMAL(5, 2) DEFAULT 5;
```

---

## 🔄 Plan de Rollback (Por si algo sale mal)

Si algo sale mal, puedes revertir los cambios:

```bash
# En el servidor de producción

# Revertir las 3 últimas migraciones
php artisan migrate:rollback --step=3

# Restaurar el backup de la base de datos
mysql -u usuario -p nombre_base_datos < backup_antes_descuentos_YYYYMMDD_HHMMSS.sql

# Volver al commit anterior
git reset --hard HEAD~1
```

---

## 📝 Checklist de Verificación Post-Despliegue

- [ ] Las migraciones se ejecutaron sin errores
- [ ] El panel de configuración muestra los campos de descuento
- [ ] Se pueden configurar los límites de descuento
- [ ] El POS Desktop permite aplicar descuentos
- [ ] El POS Mobile permite aplicar descuentos
- [ ] Los descuentos respetan los límites por rol
- [ ] Se requiere razón cuando el descuento es >= 5%
- [ ] Los tickets muestran el descuento correctamente
- [ ] Los reportes de descuentos funcionan
- [ ] Las propinas se suman correctamente al total
- [ ] Los tickets muestran las propinas

---

## 🆘 Solución de Problemas Comunes

### Error: "Column 'tip_amount' not found"
**Solución:** La migración no se ejecutó. Ejecutar `php artisan migrate --force`

### Error: "Column 'discount_percentage' not found"
**Solución:** La migración no se ejecutó. Ejecutar `php artisan migrate --force`

### Error 500 al guardar configuración de descuentos
**Solución:** Limpiar caché con `php artisan config:clear && php artisan cache:clear`

### Los descuentos no respetan los límites
**Solución:** Verificar que el usuario tenga un rol asignado (Admin, Cajero, Vendedor)

---

## 📞 Soporte

Si encuentras algún problema durante el despliegue:

1. **No entres en pánico** 🧘‍♂️
2. Revisa los logs: `tail -f storage/logs/laravel.log`
3. Verifica el estado de las migraciones: `php artisan migrate:status`
4. Si es necesario, haz rollback y contacta al equipo de desarrollo

---

## 📊 Tiempo Estimado de Despliegue

- Backup: 2-5 minutos
- Pull del código: 1 minuto
- Migraciones: 1 minuto
- Limpieza de caché: 1 minuto
- Compilación de assets: 2-3 minutos
- Verificación: 5 minutos

**Total: ~15 minutos**

---

## ✨ Nuevas Funcionalidades Disponibles

Después del despliegue, los usuarios podrán:

✅ **Propinas:**
- Agregar propinas en el POS (Desktop y Mobile)
- Propinas predefinidas: 5%, 10%, 15%
- Propina personalizada en monto fijo
- Propina se muestra en el ticket impreso

✅ **Descuentos:**
- Aplicar descuentos con límites por rol:
  - **Cajero**: Hasta 15% (configurable)
  - **Vendedor**: Hasta 10% (configurable)
  - **Admin**: Hasta 100% (configurable)
- Descuentos rápidos: 5%, 10%, 15%
- Descuento personalizado (validado por rol)
- Razón obligatoria para descuentos >= 5%
- Descuento se muestra en el ticket con porcentaje y razón

✅ **Configuración:**
- Panel de administración para configurar límites de descuento
- Configurar desde qué porcentaje se requiere razón

✅ **Reportes:**
- Reporte de descuentos otorgados
- Análisis por usuario
- Total descontado y porcentaje promedio

---

**Fecha de creación:** 10 de Noviembre, 2025
**Versión:** 1.0
**Autor:** Sistema POS Team
