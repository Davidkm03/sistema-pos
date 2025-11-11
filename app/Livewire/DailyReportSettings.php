<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BusinessSetting;
use App\Services\DailyReportService;
use Illuminate\Support\Facades\Artisan;

class DailyReportSettings extends Component
{
    public bool $whatsapp_daily_report_enabled = false;
    public string $whatsapp_report_time = '19:00';
    public string $owner_whatsapp = '';
    public bool $whatsapp_report_include_combos = true;

    public function mount()
    {
        $settings = BusinessSetting::first();
        
        if ($settings) {
            $this->whatsapp_daily_report_enabled = (bool) $settings->whatsapp_daily_report_enabled;
            $this->whatsapp_report_time = substr($settings->whatsapp_report_time ?? '19:00:00', 0, 5);
            $this->owner_whatsapp = $settings->owner_whatsapp ?? '';
            $this->whatsapp_report_include_combos = (bool) $settings->whatsapp_report_include_combos;
        }
    }

    public function save()
    {
        $this->validate([
            'whatsapp_report_time' => 'required',
            'owner_whatsapp' => 'required_if:whatsapp_daily_report_enabled,true|nullable|string|max:20',
        ], [
            'owner_whatsapp.required_if' => 'Debes ingresar un número de WhatsApp para activar el reporte',
            'whatsapp_report_time.required' => 'Debes seleccionar una hora'
        ]);

        $settings = BusinessSetting::first();
        
        if ($settings) {
            $settings->update([
                'whatsapp_daily_report_enabled' => $this->whatsapp_daily_report_enabled,
                'whatsapp_report_time' => $this->whatsapp_report_time . ':00',
                'owner_whatsapp' => $this->owner_whatsapp,
                'whatsapp_report_include_combos' => $this->whatsapp_report_include_combos,
            ]);

            $this->dispatch('settings-saved', 
                message: 'Configuración de reporte diario guardada exitosamente'
            );
        }
    }

    public function testReport()
    {
        $this->validate([
            'owner_whatsapp' => 'required|string|max:20',
        ], [
            'owner_whatsapp.required' => 'Debes ingresar un número de WhatsApp para probar'
        ]);

        try {
            // Run the command with --force flag
            Artisan::call('whatsapp:daily-report', ['--force' => true]);
            
            $this->dispatch('report-generated', 
                message: '¡Reporte generado! Revisa la consola del servidor para ver la URL de WhatsApp.'
            );
            
        } catch (\Exception $e) {
            $this->dispatch('report-error', 
                message: 'Error generando reporte: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.daily-report-settings')
            ->layout('layouts.app');
    }
}

