#!/bin/bash

echo "🧹 Limpiando todos los cachés..."

# Ejecutar en servidor SSH
ssh u301792158@us-phx-web531.registrar-servers.com << 'ENDSSH'
cd sistemapos

echo "�� Haciendo git pull..."
git pull origin main

echo "🗑️ Limpiando cachés de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "🗑️ Limpiando vistas compiladas..."
rm -rf storage/framework/views/*.php

echo "🗑️ Limpiando cache de configuración..."
rm -rf bootstrap/cache/*.php

echo "✅ Cachés limpiados!"
echo ""
echo "🔍 Verificando archivo de vista..."
grep -n "Enviar por WhatsApp" resources/views/quotes/show.blade.php | head -5

ENDSSH

echo ""
echo "✅ Proceso completado!"
echo "�� Ahora recarga la página con Ctrl+F5 o Cmd+Shift+R"
