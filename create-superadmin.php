<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Crear o actualizar Super Admin principal
$user = \App\Models\User::updateOrCreate(
    ['email' => 'superadmin@sistema.com'],
    [
        'name' => 'Super Administrador del Sistema',
        'password' => bcrypt('SuperAdmin2024!'),
        'empresa_id' => 1
    ]
);

// Limpiar roles anteriores y asignar super-admin
$user->syncRoles(['super-admin']);

echo "✅ Super Admin creado/actualizado exitosamente!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Email:      superadmin@sistema.com\n";
echo "Contraseña: SuperAdmin2024!\n";
echo "Nombre:     {$user->name}\n";
echo "Empresa:    {$user->empresa_id}\n";
echo "Roles:      " . $user->getRoleNames()->implode(', ') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n🔐 Ya puedes iniciar sesión en: http://127.0.0.1:8000/login\n";
