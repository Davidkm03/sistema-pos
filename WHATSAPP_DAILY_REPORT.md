# 📱 Sistema de Reporte Diario Automático por WhatsApp

## 🚀 Descripción

Este módulo permite recibir automáticamente un reporte completo del negocio todos los días a la hora configurada, directamente en WhatsApp. **NO requiere API de WhatsApp**, utiliza WhatsApp Web con enlaces prellenados.

### ✨ Características

- **📊 Análisis de Ventas**: Total vendido, número de transacciones, ticket promedio
- **💰 Ganancias Estimadas**: Utilidad y margen de ganancia del día
- **⚠️ Alertas de Stock**: Productos con bajo inventario
- **🔴 Predicción de Agotamiento**: Productos que se agotarán mañana basado en velocidad de venta
- **🎯 Combos Sugeridos**: Productos que se compran frecuentemente juntos con precio de combo recomendado
- **✨ Recomendación IA**: Sugerencia inteligente generada por GPT-4o-mini de OpenAI

---

## 📋 Archivos Creados

```
app/
├── Console/Commands/
│   └── SendDailyWhatsAppReport.php      # Comando Artisan para generar reporte
├── Services/
│   └── DailyReportService.php           # Lógica de negocio y análisis
└── Livewire/
    └── DailyReportSettings.php          # Componente de configuración

resources/views/livewire/
└── daily-report-settings.blade.php      # Vista de configuración

database/migrations/
└── 2025_11_11_044112_add_whatsapp_daily_report_to_business_settings_table.php

routes/
├── console.php                          # Task scheduling
└── web.php                              # Ruta /configuracion/reporte-diario
```

---

## ⚙️ Configuración

### 1. **Migrar la Base de Datos**

Ya se ejecutó la migración. Campos agregados a `business_settings`:

- `whatsapp_daily_report_enabled` (boolean): Activar/desactivar reporte
- `whatsapp_report_time` (time): Hora del reporte (default: 19:00)
- `owner_whatsapp` (string): Número de WhatsApp del dueño
- `whatsapp_report_include_combos` (boolean): Incluir análisis de combos

### 2. **Configurar OpenAI (Opcional)**

Para habilitar las recomendaciones IA, agrega tu API key de OpenAI en `.env`:

```bash
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxx
```

Si no configuras OpenAI, el reporte seguirá funcionando sin la sección de recomendación IA.

### 3. **Configurar Cron Job en el Servidor**

El reporte se ejecuta automáticamente mediante Laravel Task Scheduler. Para que funcione, debes agregar un cron job en tu servidor:

#### **VPS/Servidor Linux**

Edita el crontab:

```bash
crontab -e
```

Agrega esta línea (reemplaza `/ruta/del/proyecto` con la ruta real):

```bash
* * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

#### **Hosting Compartido (cPanel)**

1. Ve a **Cron Jobs** en cPanel
2. Selecciona "Once Per Minute (* * * * *)"
3. Agrega el comando:

```bash
cd /home/usuario/public_html/sistema-pos && php artisan schedule:run >> /dev/null 2>&1
```

#### **Verificar que el Cron está funcionando**

Revisa los logs:

```bash
tail -f storage/logs/laravel.log
```

---

## 🎯 Uso

### **Desde la Interfaz Web**

1. Ve a **Configuración** → **Reporte Diario WhatsApp**
2. Activa el toggle "Activar Reporte Diario"
3. Selecciona la hora (ej: 19:00 para 7pm)
4. Ingresa tu número de WhatsApp (ej: 3001234567)
5. (Opcional) Activa "Incluir análisis de combos"
6. Click en **Guardar Configuración**
7. Usa **Probar Ahora** para generar un reporte de prueba

### **Desde la Terminal (Manual)**

Generar reporte inmediato:

```bash
php artisan whatsapp:daily-report --force
```

El comando mostrará:
- El mensaje completo del reporte
- La URL de WhatsApp Web para abrir con el mensaje prellenado

---

## 📊 Ejemplo de Reporte

```
🚀 *REPORTE DIARIO* 🚀

📊 *Ventas de hoy*
• Total vendido: $985,200
• Transacciones: 47
• Ticket promedio: $20,961

💰 *Ganancias*
• Utilidad estimada: $315,264
• Margen: 32%

🔴 *Se agotarán mañana*
• Arroz Diana (quedan 8)
• Azúcar (quedan 12)
• Coca-Cola 2L (quedan 15)

⚠️ *Stock bajo*
• Aceite Girasol: 5 unidades
• Papel Higiénico: 7 unidades
• Detergente: 9 unidades

🎯 *Combos sugeridos* (compran juntos)
• Hamburguesa + Papitas
  23 veces • Precio combo: $18,400
• Gaseosa + Papas Fritas
  18 veces • Precio combo: $8,280

✨ *Sugerencia IA*
Compra 5 cajas de Coca-Cola para evitar desabasto. El proveedor BebidasMar tiene mejor precio esta semana.

---
_Reporte automático - 10/11/2025 19:00_
```

---

## 🔧 Cómo Funciona (Sin API)

1. **Laravel Scheduler** ejecuta el comando cada minuto
2. **El comando verifica** si:
   - El reporte está activado
   - La hora actual coincide con la hora configurada
3. **Genera el análisis**:
   - Consulta ventas del día
   - Calcula ganancias y margen
   - Detecta stock bajo y productos en riesgo
   - Analiza combos frecuentes (últimos 30 días)
   - Genera recomendación IA con OpenAI
4. **Crea URL de WhatsApp** con el mensaje prellenado
5. **Log del reporte** en `storage/logs/laravel.log`

**Importante**: El usuario debe **abrir la URL** en su navegador para enviar el mensaje. No es automático al 100%, pero no requiere API paga de WhatsApp.

---

## 🚀 Automatización Completa (Avanzado)

Si quieres que el mensaje se envíe automáticamente sin intervención manual, puedes integrar:

### Opción 1: WhatsApp Business API Oficial (Pago)
- Requiere cuenta de WhatsApp Business API
- Costo por mensaje
- Envío automático real

### Opción 2: Puppeteer + WhatsApp Web (Gratis pero frágil)
- Automatiza navegador para abrir WhatsApp Web
- Puede romperse con cambios de WhatsApp
- Requiere servidor con navegador instalado

### Opción 3: Twilio WhatsApp (Freemium)
- API sencilla
- Plan gratuito limitado
- Envío automático

**Recomendación actual**: La URL de WhatsApp es suficiente para negocios pequeños/medianos. El dueño solo debe abrir el link una vez al día.

---

## 📝 Campos de la Base de Datos

Tabla: `business_settings`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `whatsapp_daily_report_enabled` | boolean | Reporte activado (default: false) |
| `whatsapp_report_time` | time | Hora del reporte (default: 19:00:00) |
| `owner_whatsapp` | string | Número WhatsApp del dueño |
| `whatsapp_report_include_combos` | boolean | Incluir combos (default: true) |

---

## 🧪 Pruebas

### Generar Reporte de Prueba

```bash
php artisan whatsapp:daily-report --force
```

### Ver Logs

```bash
tail -f storage/logs/laravel.log | grep "Daily WhatsApp Report"
```

### Verificar Scheduler

```bash
php artisan schedule:list
```

Debe aparecer:
```
whatsapp-daily-report-check  Every minute
```

---

## 🐛 Troubleshooting

### El reporte no se genera automáticamente

**Causa**: El cron job no está configurado.

**Solución**: Verifica que el cron esté agregado correctamente:

```bash
crontab -l
```

Debe aparecer:
```
* * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

### El reporte no incluye recomendación IA

**Causa**: OpenAI API key no configurada o inválida.

**Solución**: Verifica `.env`:

```bash
OPENAI_API_KEY=sk-proj-xxxxx
```

Prueba la conexión:

```bash
php artisan tinker
>>> config('services.openai.api_key')
```

### El número de WhatsApp no funciona

**Causa**: Formato incorrecto.

**Solución**: El número debe ser de 10 dígitos (Colombia):
- ✅ Correcto: `3001234567`
- ❌ Incorrecto: `+57 300 123 4567`

El sistema automáticamente agrega el código `57` si falta.

### La URL de WhatsApp no abre correctamente

**Causa**: Navegador bloqueando pop-ups o caracteres especiales en el mensaje.

**Solución**: Copia la URL y pégala en una nueva pestaña manualmente.

---

## 🎁 Extras

### Modificar Umbral de Stock Bajo

En `app/Services/DailyReportService.php`, línea ~75:

```php
public function getLowStockProducts($threshold = 10)  // Cambiar 10 por el valor deseado
```

### Modificar Días para Análisis de Combos

En `app/Services/DailyReportService.php`, línea ~115:

```php
$thirtyDaysAgo = Carbon::now()->subDays(30);  // Cambiar 30 por los días deseados
```

### Personalizar Mensaje de Reporte

En `app/Services/DailyReportService.php`, método `formatWhatsAppMessage()`, línea ~190+.

---

## 📚 Recursos

- [Laravel Task Scheduling](https://laravel.com/docs/11.x/scheduling)
- [WhatsApp Web URL Format](https://faq.whatsapp.com/general/chats/how-to-use-click-to-chat)
- [OpenAI API Documentation](https://platform.openai.com/docs/api-reference)

---

## ✅ Checklist de Implementación

- [x] Migración de base de datos
- [x] Service para análisis de datos
- [x] Command Artisan
- [x] Task Scheduling
- [x] Componente Livewire de configuración
- [x] Vista Blade
- [x] Ruta web
- [x] Enlace en menú de settings
- [ ] Configurar cron job en servidor de producción
- [ ] Configurar OpenAI API key (opcional)
- [ ] Probar reporte en producción

---

**Desarrollado con ❤️ para Sistema POS**  
Versión: 1.0.0 | Fecha: Noviembre 2025
