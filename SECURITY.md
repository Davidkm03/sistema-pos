# 🔒 Guía de Seguridad - Sistema POS

## 📋 Índice

- [Configuración para Producción](#configuración-para-producción)
- [Características de Seguridad Implementadas](#características-de-seguridad-implementadas)
- [Mejores Prácticas](#mejores-prácticas)
- [Reportar Vulnerabilidades](#reportar-vulnerabilidades)

---

## 🚀 Configuración para Producción

### Variables de Entorno Críticas

Antes de deployar a producción, **DEBE** actualizar las siguientes variables en tu archivo `.env`:

```env
# ❌ NUNCA en producción
APP_DEBUG=false

# ✅ Seguridad de sesiones
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_LIFETIME=30
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# ✅ Hashing de contraseñas más fuerte
BCRYPT_ROUNDS=14

# ✅ HTTPS en producción
APP_URL=https://tu-dominio.com

# ✅ Base de datos segura
DB_CONNECTION=mysql  # Cambiar de SQLite a MySQL/PostgreSQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=usuario_bd
DB_PASSWORD=contraseña_segura_aquí

# ✅ Logging apropiado
LOG_CHANNEL=stack
LOG_LEVEL=warning  # En producción usar 'warning' o 'error'
```

### Checklist Pre-Producción

- [ ] `APP_DEBUG=false` configurado
- [ ] `APP_ENV=production` configurado
- [ ] `APP_KEY` generado (ejecutar `php artisan key:generate`)
- [ ] Variables de sesión seguras configuradas
- [ ] Base de datos en servidor dedicado (no SQLite)
- [ ] HTTPS configurado en el servidor
- [ ] Certificado SSL válido instalado
- [ ] Firewall configurado
- [ ] Backups automáticos configurados
- [ ] Archivo `.env` con permisos 600 (`chmod 600 .env`)

---

## 🛡️ Características de Seguridad Implementadas

### 1. Autenticación y Autorización

#### Sistema de Roles y Permisos
```php
Roles disponibles:
- Super Admin: Acceso completo al sistema
- Admin: Gestión completa excepto roles
- Supervisor: Gestión operativa
- Cajero: Solo punto de venta
```

#### Permisos Granulares
- `access-pos`: Acceso al punto de venta
- `process-sales`: Procesar ventas
- `view-sales` / `view-all-sales`: Ver ventas
- `cancel-own-sales` / `cancel-any-sales`: Anular ventas
- `view-products` / `create-products` / `edit-products` / `delete-products`
- `view-inventory`: Ver inventario
- `view-reports`: Ver reportes
- `view-goals`: Ver metas
- `view-audit-log`: Ver auditoría
- `manage-settings`: Gestionar configuración

### 2. Protección CSRF

✅ **Implementado automáticamente** en todas las rutas web.

```blade
<!-- En formularios Blade -->
<form method="POST">
    @csrf
    ...
</form>
```

```javascript
// En peticiones AJAX
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

### 3. Rate Limiting

**Rutas protegidas contra ataques de fuerza bruta:**

| Ruta | Límite | Ventana |
|------|--------|---------|
| `POST /login` | 5 intentos | 1 minuto |
| `POST /register` | 5 intentos | 1 minuto |
| `POST /forgot-password` | 3 intentos | 1 minuto |
| `POST /pos/search` | 60 búsquedas | 1 minuto |
| `POST /pos/procesar-venta` | 30 ventas | 1 minuto |

### 4. Security Headers

Headers de seguridad implementados automáticamente:

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains (solo producción)
Content-Security-Policy: [política configurada]
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

### 5. Validación de Archivos

**Uploads permitidos:**
- Formatos: JPEG, JPG, PNG, WebP
- Tamaño máximo: 2MB
- Validación de tipo MIME
- Compresión automática de imágenes

```php
// Ejemplo de validación
'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
```

### 6. Logging de Seguridad

**Eventos registrados:**
- ✅ Intentos fallidos de login (con IP y user agent)
- ✅ Logins exitosos
- ✅ Bloqueos por rate limiting
- ✅ Cambios en ventas (auditoría completa)
- ✅ Anulaciones de ventas con razón

**Ubicación de logs:** `storage/logs/laravel.log`

### 7. Protección de Sesiones

- Sesiones cifradas en producción
- Cookies HTTP-only (no accesibles vía JavaScript)
- Cookies seguras (solo HTTPS en producción)
- SameSite policy para prevenir CSRF
- Regeneración de token en login/logout

### 8. Transacciones de Base de Datos

Operaciones críticas protegidas con transacciones:

```php
DB::beginTransaction();
try {
    // Operaciones críticas
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // Manejo de error
}
```

---

## 🔐 Mejores Prácticas

### Para Administradores

1. **Contraseñas Fuertes**
   - Mínimo 12 caracteres
   - Combinar mayúsculas, minúsculas, números y símbolos
   - No reutilizar contraseñas
   - Cambiar cada 90 días

2. **Gestión de Usuarios**
   - Crear usuarios solo con permisos necesarios (principio de menor privilegio)
   - Revisar regularmente usuarios activos
   - Eliminar usuarios inactivos
   - Auditar cambios en roles y permisos

3. **Monitoreo**
   - Revisar logs regularmente: `storage/logs/laravel.log`
   - Configurar alertas para intentos de login fallidos
   - Monitorear log de auditoría de ventas
   - Verificar espacio en disco

4. **Backups**
   - Realizar backups diarios de la base de datos
   - Almacenar backups fuera del servidor
   - Probar restauración mensualmente
   - Cifrar backups sensibles

### Para Desarrolladores

1. **Nunca Commitear Secretos**
   ```bash
   # Verificar .gitignore incluye:
   .env
   .env.*
   /storage/*.key
   ```

2. **Validar Siempre Inputs**
   ```php
   $request->validate([
       'campo' => 'required|string|max:255',
   ]);
   ```

3. **Usar Eloquent ORM**
   - Previene SQL injection automáticamente
   - Nunca usar DB::raw() con datos de usuario sin sanitizar

4. **Mantener Dependencias Actualizadas**
   ```bash
   composer update
   npm audit fix
   ```

### Para Usuarios

1. **No Compartir Credenciales**
   - Cada usuario debe tener su propia cuenta
   - No compartir contraseñas

2. **Cerrar Sesión**
   - Siempre cerrar sesión al terminar
   - Especialmente en computadoras compartidas

3. **Reportar Actividad Sospechosa**
   - Ventas no realizadas
   - Cambios no autorizados
   - Accesos desde ubicaciones desconocidas

---

## 🔧 Configuración del Servidor

### Nginx (Recomendado)

```nginx
server {
    listen 443 ssl http2;
    server_name tu-dominio.com;

    ssl_certificate /ruta/al/certificado.crt;
    ssl_certificate_key /ruta/a/llave.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    root /var/www/html/public;
    index index.php;

    # Ocultar versión de Nginx
    server_tokens off;

    # Protección adicional
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### PHP Configuration

```ini
; php.ini configuración segura
expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
memory_limit = 256M
upload_max_filesize = 2M
post_max_size = 8M
max_execution_time = 30
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
```

### Permisos de Archivos

```bash
# Permisos correctos
chown -R www-data:www-data /var/www/html
find /var/www/html -type f -exec chmod 644 {} \;
find /var/www/html -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
chmod 600 .env
```

---

## 🐛 Reportar Vulnerabilidades

Si descubres una vulnerabilidad de seguridad:

1. **NO** crear un issue público en GitHub
2. Enviar email a: security@tu-dominio.com
3. Incluir:
   - Descripción detallada
   - Pasos para reproducir
   - Impacto potencial
   - Sugerencias de mitigación (opcional)

**Tiempo de respuesta:** 48 horas
**Tiempo de resolución:** 7-14 días (según severidad)

### Política de Divulgación Responsable

- Daremos crédito al reporter (si lo desea)
- No tomaremos acciones legales contra researchers éticos
- Proporcionaremos actualizaciones sobre el progreso
- Notificaremos cuando esté parcheado

---

## 📚 Recursos Adicionales

- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [Nginx Security Tips](https://www.nginx.com/blog/mitigating-ddos-attacks-with-nginx-and-nginx-plus/)

---

## 📝 Changelog de Seguridad

### [1.0.0] - 2025-11-08

#### Agregado
- Sistema completo de roles y permisos (Spatie)
- Rate limiting en login, registro y endpoints críticos
- Security headers middleware
- Logging de intentos de login fallidos
- Validación estricta de archivos (2MB, formatos permitidos)
- Cifrado de sesiones
- Auditoría completa de ventas

#### Configurado
- APP_DEBUG=false para producción
- SESSION_ENCRYPT=true
- SESSION_SECURE_COOKIE=true
- BCRYPT_ROUNDS=14
- SESSION_LIFETIME=30 minutos
- CSRF protection en todas las rutas
- Protección contra clickjacking, XSS, MIME sniffing

---

**Última actualización:** 8 de Noviembre, 2025  
**Versión:** 1.0.0  
**Mantenedor:** Sistema POS Team
