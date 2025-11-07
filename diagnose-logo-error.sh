#!/bin/bash

# Script para diagnosticar y corregir el error 500 en configuración de negocio

echo "🔍 Diagnóstico del Error 500 - Configuración de Negocio"
echo "========================================================="
echo ""

# 1. Ver los últimos errores de Laravel
echo "📋 Últimos errores en laravel.log:"
echo "-----------------------------------"
tail -100 storage/logs/laravel.log | grep -i "error\|exception" | tail -20

echo ""
echo ""

# 2. Verificar permisos de storage
echo "🔐 Verificando permisos de storage:"
echo "-----------------------------------"
ls -la storage/app/public/logos/
echo ""
chmod -R 775 storage/app/public/
chmod -R 775 storage/logs/
echo "✅ Permisos actualizados"

echo ""
echo ""

# 3. Verificar que el directorio logos existe
echo "📁 Verificando directorio logos:"
echo "--------------------------------"
if [ -d "storage/app/public/logos" ]; then
    echo "✅ Directorio logos existe"
    ls -lh storage/app/public/logos/ | head -10
else
    echo "❌ Directorio logos NO existe. Creando..."
    mkdir -p storage/app/public/logos
    chmod 775 storage/app/public/logos
    echo "✅ Directorio creado"
fi

echo ""
echo ""

# 4. Verificar extensión GD de PHP
echo "🎨 Verificando extensión GD de PHP:"
echo "------------------------------------"
php -m | grep -i gd
if [ $? -eq 0 ]; then
    echo "✅ Extensión GD está instalada"
else
    echo "❌ Extensión GD NO está instalada"
    echo "⚠️  Contactar a Hostinger para activarla"
fi

echo ""
echo ""

# 5. Limpiar cachés
echo "🧹 Limpiando cachés de Laravel:"
echo "--------------------------------"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Cachés limpiados"

echo ""
echo ""

# 6. Reconstruir cachés
echo "🔨 Reconstruyendo cachés optimizados:"
echo "--------------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Cachés reconstruidos"

echo ""
echo ""

# 7. Verificar configuración de filesystems
echo "💾 Verificando configuración de filesystems:"
echo "--------------------------------------------"
php artisan tinker --execute="echo 'Public disk path: ' . config('filesystems.disks.public.root');"
php artisan tinker --execute="echo 'Storage path: ' . storage_path('app/public');"

echo ""
echo ""

# 8. Test de guardado de archivo
echo "🧪 Test de guardado de archivo:"
echo "--------------------------------"
php artisan tinker --execute="
\$path = 'test_' . uniqid() . '.txt';
\$result = Storage::disk('public')->put('logos/' . \$path, 'TEST');
echo \$result ? '✅ Escritura OK' : '❌ Error de escritura';
if (\$result) {
    Storage::disk('public')->delete('logos/' . \$path);
    echo ' - Archivo de prueba eliminado';
}
"

echo ""
echo ""

echo "========================================================="
echo "✅ Diagnóstico completado"
echo ""
echo "📝 Próximos pasos:"
echo "1. Revisar los errores mostrados arriba"
echo "2. Si hay errores de permisos, ejecutar: chmod -R 775 storage/"
echo "3. Si GD no está instalada, contactar a Hostinger"
echo "4. Intentar subir logo nuevamente"
echo ""
echo "📧 Si el problema persiste, revisar:"
echo "   tail -f storage/logs/laravel.log"
echo ""
