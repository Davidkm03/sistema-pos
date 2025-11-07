# 🚀 Pasos para Actualizar en Hostinger

## 📋 Proceso de Deployment

### 1️⃣ Conectarse al Servidor por SSH

```bash
ssh u301792158@paginaswebscolombia.com
```

### 2️⃣ Navegar al Directorio del Proyecto

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos
```

### 3️⃣ Verificar Estado Actual (IMPORTANTE)

```bash
# Ver si hay cambios sin guardar
git status

# Ver qué branch estás usando
git branch
```

### 4️⃣ Guardar Cambios Locales (si los hay)

Si `git status` muestra cambios:

```bash
# Opción A: Guardar cambios temporalmente
git stash

# Opción B: Hacer commit de los cambios
git add .
git commit -m "Cambios locales del servidor"
```

### 5️⃣ Hacer Pull de los Nuevos Cambios

```bash
# Traer los últimos cambios del repositorio
git pull origin main
```

### 6️⃣ Instalar/Actualizar Dependencias (si es necesario)

```bash
# Solo si hay cambios en composer.json
composer install --no-dev --optimize-autoloader

# Solo si hay cambios en package.json
npm install
npm run build
```

### 7️⃣ Ejecutar Migraciones (si hay nuevas)

```bash
# Revisar si hay migraciones pendientes
php artisan migrate:status

# Ejecutar migraciones
php artisan migrate --force
```

### 8️⃣ Limpiar Cachés

```bash
# Limpiar cache de configuración
php artisan config:cache

# Limpiar cache de rutas
php artisan route:cache

# Limpiar cache de vistas
php artisan view:cache

# Limpiar cache de permisos (IMPORTANTE para roles)
php artisan permission:cache-reset
```

### 9️⃣ Verificar Permisos de Archivos

```bash
# Dar permisos a storage y cache
chmod -R 775 storage bootstrap/cache
```

### 🔟 Verificar en el Navegador

Abrir: https://sistemapos.paginaswebscolombia.com

---

## ⚠️ IMPORTANTE para este Update Específico

Este update incluye el **Sistema de Gestión de Roles**, así que:

### 1. Limpiar Cache de Permisos (OBLIGATORIO)

```bash
php artisan permission:cache-reset
```

### 2. Verificar que el Super Admin Existe

```bash
php artisan tinker
>>> User::role('super-admin')->first();
>>> exit
```

Si no existe, ejecutar el seeder:

```bash
php artisan db:seed --class=SuperAdminSeeder
```

### 3. Probar el Acceso

- Login con: `superadmin@sistema-pos.com` / `SuperAdmin123!`
- Ir a la navegación y buscar el dropdown "Admin"
- Click en "Gestión de Roles"
- Deberías ver la lista de roles

---

## 🐛 Troubleshooting

### Error: "Permission denied"
```bash
# Verificar permisos del usuario
ls -la
# Contactar a Hostinger si no tienes permisos
```

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:cache
```

### Error: "Route not found"
```bash
php artisan route:cache
php artisan config:cache
```

### Error: "Role super-admin does not exist"
```bash
php artisan db:seed --class=SuperAdminSeeder
php artisan permission:cache-reset
```

### Los cambios no se reflejan
```bash
# Limpiar TODOS los cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset
```

---

## 📝 Comando Rápido (Todo en Uno)

```bash
cd domains/paginaswebscolombia.com/public_html/sistemapos && \
git pull origin main && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan permission:cache-reset && \
echo "✅ Deployment completado!"
```

---

## 🔍 Verificación Post-Deployment

1. ✅ Sitio carga sin errores
2. ✅ Login funciona correctamente
3. ✅ Super admin puede ver el menú "Admin"
4. ✅ Puede acceder a /admin/roles
5. ✅ Puede editar permisos de roles
6. ✅ Usuarios normales NO ven el menú Admin

---

**Última Actualización**: 7 de Noviembre, 2025  
**Commit**: c2f1abc - Sistema de Gestión de Roles y Permisos
