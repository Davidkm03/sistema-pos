# 🚀 Deploy: Modernización de Diseño (Welcome & Login)

## 📋 Cambios en este Deploy

- ✨ Nueva página de bienvenida profesional
- 🎨 Login modernizado con animaciones
- 🔒 Eliminación de registro público
- 🎯 Enfoque profesional para sistema POS

---

## 🔌 Conectarse al Servidor

```bash
ssh u301792158@paginaswebscolombia.com
```

**Contraseña**: [Tu contraseña de Hostinger]

---

## 📦 Script de Deployment Completo

Una vez conectado por SSH, ejecuta este comando TODO EN UNO:

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos && \
echo "📡 Trayendo cambios del repositorio..." && \
git pull origin main && \
echo "🔄 Actualizando permisos de Super Admin..." && \
php artisan db:seed --class=UpdateSuperAdminPermissionsSeeder && \
echo "🧹 Limpiando cachés..." && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan permission:cache-reset && \
echo "✅ ¡Deployment completado exitosamente!"
```

**IMPORTANTE**: Este deploy incluye la actualización de permisos del Super Admin para que tenga acceso a:
- ✅ Anulación de ventas
- ✅ Logs de auditoría
- ✅ Gestión de metas
- ✅ Todos los módulos del sistema

---

## 📝 Paso a Paso (Alternativa Manual)

Si prefieres hacerlo paso a paso:

### 1. Navegar al directorio
```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos
```

### 2. Verificar estado actual
```bash
git status
git branch
```

### 3. Hacer pull de los cambios
```bash
git pull origin main
```

Deberías ver:
```
Updating [hash]...[hash]
Fast-forward
 public/build/manifest.json              | ...
 resources/views/auth/login.blade.php    | ...
 resources/views/layouts/guest.blade.php | ...
 resources/views/welcome.blade.php       | ...
```

### 4. Instalar dependencias NPM (si es necesario)
```bash
npm install
```

### 5. Compilar assets de producción
```bash
npm run build
```

### 6. Limpiar cachés
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Verificar permisos
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🔍 Verificación

### Abrir en el navegador:
- **Home**: https://sistemapos.paginaswebscolombia.com
- **Login**: https://sistemapos.paginaswebscolombia.com/login

### Verificar que se vean:
1. ✅ Página de inicio con diseño limpio profesional
2. ✅ Login con animaciones de blobs
3. ✅ Iconos SVG en los inputs (email, password)
4. ✅ Sin opción de "Registrarse"
5. ✅ Animaciones suaves al cargar
6. ✅ Cards de "Seguro", "Rápido", "Potente" con iconos

---

## ⚠️ Troubleshooting

### Si no se ven los cambios visuales:
```bash
# Limpiar TODOS los cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Forzar recompilación de assets
npm run build

# Ctrl + Shift + R en el navegador (hard refresh)
```

### Si aparece error 500:
```bash
# Ver logs
tail -f storage/logs/laravel.log

# Verificar permisos
chmod -R 775 storage bootstrap/cache
```

### Si los assets no cargan (CSS/JS):
```bash
# Verificar que public/build existe
ls -la public/build/

# Recompilar
npm run build

# Verificar manifest
cat public/build/manifest.json
```

---

## 📊 Checklist Post-Deployment

- [ ] Home carga correctamente
- [ ] Login tiene nuevo diseño con animaciones
- [ ] No hay errores en consola del navegador
- [ ] Animaciones funcionan suavemente
- [ ] Dark mode funciona
- [ ] Responsive funciona en móvil
- [ ] No aparece opción "Registrarse"
- [ ] Los iconos SVG se ven correctamente

---

## 🎯 Comandos Útiles

### Ver logs en tiempo real:
```bash
tail -f storage/logs/laravel.log
```

### Ver último commit:
```bash
git log -1
```

### Verificar versión de Node:
```bash
node -v
npm -v
```

### Ver archivos modificados:
```bash
git diff HEAD~1 HEAD --name-only
```

---

**Fecha**: 8 de Noviembre, 2025  
**Commit**: d31864f - Modernización del diseño de bienvenida y login  
**Tiempo estimado**: 5-10 minutos

---

## 🆘 Soporte

Si algo sale mal:
1. Revisar logs: `tail -f storage/logs/laravel.log`
2. Limpiar cachés: Script del paso 6
3. Hard refresh navegador: `Ctrl + Shift + R`
4. Contactar: [tu contacto]
