#!/bin/bash

echo "Actualizando configuración SMTP en producción..."

ssh u301792158@us-phx-web531.registrar-servers.com << 'ENDSSH'
cd sistemapos

# Actualizar configuración SMTP en la base de datos
php artisan tinker --execute="
\$super = App\Models\User::role('super-admin')->first();
if(\$super) {
    \$settings = App\Models\BusinessSetting::where('user_id', \$super->id)->first();
    if(\$settings) {
        \$settings->update([
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_port' => 465,
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'info@sistemapos.paginaswebscolombia.com',
            'smtp_password' => 'Pdnqeec#2025',
            'smtp_from_address' => 'info@sistemapos.paginaswebscolombia.com',
            'smtp_from_name' => 'Sistema POS'
        ]);
        echo '✅ Configuración SMTP actualizada correctamente';
    } else {
        echo '❌ No se encontró configuración de negocio';
    }
} else {
    echo '❌ No se encontró super-admin';
}
"

# Limpiar cachés
php artisan config:clear
php artisan cache:clear

echo "✅ Cachés limpiados"

ENDSSH

echo "✅ Configuración completada"
