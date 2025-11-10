# Sistema de Envío de Cotizaciones por Email

## Descripción General
Sistema completo de envío de cotizaciones por email con configuración SMTP dinámica por empresa.

## Características Implementadas

### 1. Configuración SMTP en Base de Datos
- ✅ Migración creada: `2025_11_10_173601_add_smtp_config_to_business_settings_table.php`
- ✅ 7 campos agregados a `business_settings`:
  - `smtp_host` - Servidor SMTP (ej: smtp.gmail.com)
  - `smtp_port` - Puerto (default: 587)
  - `smtp_username` - Usuario SMTP
  - `smtp_password` - Contraseña SMTP
  - `smtp_encryption` - Encriptación (default: tls)
  - `smtp_from_address` - Email remitente
  - `smtp_from_name` - Nombre remitente

### 2. Clase Mailable
- ✅ Archivo: `app/Mail/QuoteMail.php`
- ✅ Recibe `Quote` y `BusinessSetting` en constructor
- ✅ Subject dinámico: "Cotización #{quote_number} - {business_name}"
- ✅ Usa template Markdown: `emails.quote`

### 3. Template de Email
- ✅ Archivo: `resources/views/emails/quote.blade.php`
- ✅ Diseño profesional con componentes Laravel Mail
- ✅ Incluye:
  - Saludo personalizado
  - Detalles de la cotización
  - Tabla de productos con columnas: Producto, Cantidad, Precio, Subtotal
  - Resumen financiero: Subtotal, Impuestos, Descuento, Total
  - Notas de la cotización
  - Información de contacto del negocio
  - Botón "Ver Cotización" con enlace

### 4. Lógica de Envío
- ✅ Método: `QuoteController@sendEmail`
- ✅ Validación de email requerido
- ✅ Validación de configuración SMTP completa
- ✅ Configuración dinámica de SMTP usando `Config::set()`
- ✅ Carga eager loading de relaciones: customer, user, items.product
- ✅ Manejo de errores con try-catch y logging
- ✅ Soporte para peticiones AJAX (JSON) y tradicionales (redirect)

### 5. Ruta
- ✅ Archivo: `routes/web.php`
- ✅ Ruta: `POST /quotes/{quote}/send-email`
- ✅ Nombre: `quotes.send-email`
- ✅ Protegida con middleware: auth

### 6. Interfaz de Usuario
- ✅ Botón "Enviar por Email" en `quotes/show.blade.php`
- ✅ Modal profesional con diseño gradiente verde
- ✅ Pre-rellena email del cliente si existe
- ✅ Validación HTML5 (required, type="email")
- ✅ Envío AJAX con fetch API
- ✅ Estados de loading con spinner
- ✅ SweetAlert2 para mensajes de éxito/error
- ✅ Cierre de modal al hacer clic afuera
- ✅ Funciones JavaScript: `showEmailModal()`, `hideEmailModal()`

## Configuración Requerida

### 1. Ejecutar Migración
```bash
php artisan migrate
```

### 2. Configurar SMTP en el Sistema
Ir a **Configuración del Negocio** y llenar los siguientes campos:

#### Gmail (Ejemplo)
- **SMTP Host**: `smtp.gmail.com`
- **SMTP Port**: `587`
- **SMTP Username**: `tu-email@gmail.com`
- **SMTP Password**: Contraseña de aplicación (App Password)
- **SMTP Encryption**: `tls`
- **From Address**: `tu-email@gmail.com`
- **From Name**: Nombre de tu negocio

#### Otros Proveedores Comunes
- **Outlook/Hotmail**: `smtp-mail.outlook.com`, port `587`, TLS
- **Yahoo**: `smtp.mail.yahoo.com`, port `587`, TLS
- **SendGrid**: `smtp.sendgrid.net`, port `587`, TLS
- **Mailgun**: `smtp.mailgun.org`, port `587`, TLS

### 3. Gmail: Generar Contraseña de Aplicación
1. Ir a Google Account → Security
2. Activar 2-Step Verification
3. App passwords → Generate
4. Seleccionar "Mail" y "Other device"
5. Copiar la contraseña generada (16 caracteres)
6. Usar esa contraseña en SMTP Password

## Uso del Sistema

### Desde la Vista de Cotización
1. Abrir cualquier cotización: `/quotes/{id}`
2. Hacer clic en botón **"Enviar por Email"** (ícono de sobre verde)
3. Verificar/modificar email del destinatario
4. Hacer clic en **"Enviar"**
5. Esperar confirmación con SweetAlert2

### Email Enviado Incluye
- Número de cotización
- Fecha de emisión
- Fecha de validez
- Cliente
- Lista completa de productos con precios
- Subtotal, impuestos, descuento y total
- Notas de la cotización
- Información de contacto del negocio
- Enlace para ver la cotización completa

## Seguridad

### Configuración SMTP
- ✅ Contraseñas SMTP almacenadas en base de datos (considerar encriptación futura)
- ✅ Configuración por empresa (multi-tenancy)
- ✅ Validación de configuración antes de enviar
- ✅ Logging de errores sin exponer credenciales

### Middleware de Protección
- ✅ Ruta protegida con `auth` middleware
- ✅ EmpresaScope automático en Quote model
- ✅ Validación de email con Laravel Validator

## Solución de Problemas

### Error: "Por favor configure el servidor SMTP"
- Verificar que `smtp_host` y `smtp_from_address` estén configurados
- Ir a Configuración del Negocio y llenar todos los campos SMTP

### Error: "Authentication failed"
- Para Gmail: Verificar que se esté usando App Password, no la contraseña normal
- Verificar username y password correctos
- Verificar que 2FA esté activado (Gmail)

### Error: "Could not connect to SMTP host"
- Verificar `smtp_host` correcto
- Verificar `smtp_port` correcto (587 para TLS, 465 para SSL)
- Verificar conexión a internet
- Verificar firewall no bloqueando puerto

### Email no llega
- Revisar carpeta de spam
- Verificar `smtp_from_address` válido
- Verificar logs: `storage/logs/laravel.log`

## Archivos Modificados/Creados

### Migración
- `database/migrations/2025_11_10_173601_add_smtp_config_to_business_settings_table.php`

### Modelos
- `app/Models/BusinessSetting.php` - Agregados campos fillable

### Mailable
- `app/Mail/QuoteMail.php` - Clase nueva

### Controladores
- `app/Http/Controllers/QuoteController.php` - Método `sendEmail()` agregado

### Vistas
- `resources/views/emails/quote.blade.php` - Template nuevo
- `resources/views/quotes/show.blade.php` - Botón y modal agregados

### Rutas
- `routes/web.php` - Ruta POST agregada

## Próximas Mejoras Sugeridas

### Seguridad
- [ ] Encriptar `smtp_password` en base de datos
- [ ] Rate limiting para prevenir spam
- [ ] Validación adicional de emails

### Funcionalidad
- [ ] Envío masivo de cotizaciones
- [ ] CC y BCC opcionales
- [ ] Adjuntar PDF de la cotización
- [ ] Templates de email personalizables
- [ ] Historial de emails enviados
- [ ] Programar envíos

### UI/UX
- [ ] Preview del email antes de enviar
- [ ] Múltiples destinatarios
- [ ] Personalizar mensaje del email
- [ ] Test de conexión SMTP en configuración

## Commits Relacionados
- `c3a18ea` - feat: Infraestructura SMTP para emails
- `c2a1fcb` - feat: Complete email system with modal UI for quotes

---
**Fecha de Creación**: 2025-11-10  
**Versión**: 1.0  
**Estado**: ✅ Completado y Funcional
