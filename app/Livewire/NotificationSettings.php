<?php

namespace App\Livewire;

use App\Models\BusinessSetting;
use Livewire\Component;

class NotificationSettings extends Component
{
    public $settings = [];

    protected $rules = [
        'settings.enable_stock_notifications' => 'boolean',
        'settings.enable_sale_notifications' => 'boolean',
        'settings.enable_large_sale_notifications' => 'boolean',
        'settings.enable_system_error_notifications' => 'boolean',
        'settings.enable_quote_notifications' => 'boolean',
        'settings.enable_goal_notifications' => 'boolean',
        'settings.large_sale_threshold' => 'required|integer|min:1000',
        'settings.enable_email_notifications' => 'boolean',
        'settings.enable_push_notifications' => 'boolean',
    ];

    public function mount()
    {
        $businessSettings = BusinessSetting::where('user_id', auth()->id())->first();

        if ($businessSettings) {
            $this->settings = $businessSettings->only([
                'enable_stock_notifications',
                'enable_sale_notifications',
                'enable_large_sale_notifications',
                'enable_system_error_notifications',
                'enable_quote_notifications',
                'enable_goal_notifications',
                'large_sale_threshold',
                'enable_email_notifications',
                'enable_push_notifications',
            ]);
        } else {
            // Valores por defecto
            $this->settings = [
                'enable_stock_notifications' => true,
                'enable_sale_notifications' => true,
                'enable_large_sale_notifications' => true,
                'enable_system_error_notifications' => true,
                'enable_quote_notifications' => true,
                'enable_goal_notifications' => true,
                'large_sale_threshold' => 100000,
                'enable_email_notifications' => false,
                'enable_push_notifications' => true,
            ];
        }
    }

    public function saveSettings()
    {
        $this->validate();

        $businessSettings = BusinessSetting::firstOrCreate(
            ['user_id' => auth()->id()],
            ['empresa_id' => auth()->user()->empresa_id]
        );

        $businessSettings->update($this->settings);

        session()->flash('success', 'Configuración de notificaciones guardada exitosamente.');
    }

    public function resetToDefaults()
    {
        $this->settings = [
            'enable_stock_notifications' => true,
            'enable_sale_notifications' => true,
            'enable_large_sale_notifications' => true,
            'enable_system_error_notifications' => true,
            'enable_quote_notifications' => true,
            'enable_goal_notifications' => true,
            'large_sale_threshold' => 100000,
            'enable_email_notifications' => false,
            'enable_push_notifications' => true,
        ];
    }

    public function render()
    {
        return view('livewire.notification-settings');
    }
}
