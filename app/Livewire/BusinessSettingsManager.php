<?php

namespace App\Livewire;

use App\Models\BusinessSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BusinessSettingsManager extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $business_name;
    public $business_logo;
    public $logo_preview;
    public $business_address;
    public $business_phone;
    public $business_email;
    public $business_tax_id;
    public $receipt_footer;
    public $primary_color;
    public $secondary_color;
    public $timezone;
    public $currency;

    // Propiedades tributarias
    public $tax_enabled = false;
    public $tax_name = 'IVA';
    public $tax_rate = 19.00;
    public $tax_included_in_price = false;
    public $retention_enabled = false;
    public $retention_rate = 3.5;
    public $applies_retention_from = 800000;
    public $tax_id_required = false;
    public $business_tax_regime = 'simplified';

    // Propiedades de facturación/documentos
    public $billing_type = 'simple_receipt';
    public $receipt_prefix = 'RV';
    public $receipt_counter = 1;
    public $receipt_header;
    public $show_tax_disclaimer = true;

    // Propiedades para facturación con DIAN
    public $invoice_prefix = 'FV';
    public $dian_resolution;
    public $resolution_date;
    public $range_from;
    public $range_to;
    public $resolution_expiry;

    // Propiedades de descuentos
    public $max_discount_cashier = 15;
    public $max_discount_seller = 10;
    public $max_discount_admin = 100;
    public $require_discount_reason = true;
    public $require_reason_from = 5;

    // Control
    public $settings_id;
    public $existing_logo;

    protected $rules = [
        'business_name' => 'required|string|max:255',
        'business_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // max 2MB, formatos permitidos
        'business_address' => 'nullable|string|max:500',
        'business_phone' => 'nullable|string|max:50',
        'business_email' => 'nullable|email|max:255',
        'business_tax_id' => 'nullable|string|max:100',
        'receipt_footer' => 'nullable|string|max:500',
        'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        'secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        'timezone' => 'required|string|max:50',
        'currency' => 'required|string|size:3',
    ];

    public function mount()
    {
        $settings = BusinessSetting::where('user_id', auth()->id())->first();

        if ($settings) {
            $this->settings_id = $settings->id;
            $this->business_name = $settings->business_name;
            $this->existing_logo = $settings->business_logo;
            $this->business_address = $settings->business_address;
            $this->business_phone = $settings->business_phone;
            $this->business_email = $settings->business_email;
            $this->business_tax_id = $settings->business_tax_id;
            $this->receipt_footer = $settings->receipt_footer;
            $this->primary_color = $settings->primary_color;
            $this->secondary_color = $settings->secondary_color;
            $this->timezone = $settings->timezone;
            $this->currency = $settings->currency;

            // Cargar configuración tributaria
            $this->tax_enabled = $settings->tax_enabled ?? false;
            $this->tax_name = $settings->tax_name ?? 'IVA';
            $this->tax_rate = $settings->tax_rate ?? 19.00;
            $this->tax_included_in_price = $settings->tax_included_in_price ?? false;
            $this->retention_enabled = $settings->retention_enabled ?? false;
            $this->retention_rate = $settings->retention_rate ?? 3.5;
            $this->applies_retention_from = $settings->applies_retention_from ?? 800000;
            $this->tax_id_required = $settings->tax_id_required ?? false;
            $this->business_tax_regime = $settings->business_tax_regime ?? 'simplified';

            // Cargar configuración de facturación/documentos
            $this->billing_type = $settings->billing_type ?? 'simple_receipt';
            $this->receipt_prefix = $settings->receipt_prefix ?? 'RV';
            $this->receipt_counter = $settings->receipt_counter ?? 1;
            $this->receipt_header = $settings->receipt_header;
            $this->show_tax_disclaimer = $settings->show_tax_disclaimer ?? true;
            $this->invoice_prefix = $settings->invoice_prefix ?? 'FV';
            $this->dian_resolution = $settings->dian_resolution;
            $this->resolution_date = $settings->resolution_date;
            $this->range_from = $settings->range_from;
            $this->range_to = $settings->range_to;
            $this->resolution_expiry = $settings->resolution_expiry;
            
            // Cargar configuración de descuentos
            $this->max_discount_cashier = $settings->max_discount_cashier ?? 15;
            $this->max_discount_seller = $settings->max_discount_seller ?? 10;
            $this->max_discount_admin = $settings->max_discount_admin ?? 100;
            $this->require_discount_reason = $settings->require_discount_reason ?? true;
            $this->require_reason_from = $settings->require_reason_from ?? 5;
        } else {
            // Valores por defecto
            $this->business_name = 'Mi Tienda';
            $this->receipt_footer = '¡Gracias por su compra!';
            $this->primary_color = '#3B82F6';
            $this->secondary_color = '#10B981';
            $this->timezone = 'America/Mexico_City';
            $this->currency = 'MXN';
        }
    }

    public function updatedBusinessLogo()
    {
        $this->validate([
            'business_logo' => 'image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB max, formatos seguros
        ]);
    }

    public function removeLogo()
    {
        if ($this->existing_logo) {
            Storage::disk('public')->delete($this->existing_logo);
        }
        $this->existing_logo = null;
        $this->business_logo = null;
        
        // Actualizar en la base de datos
        $settings = BusinessSetting::where('user_id', auth()->id())->first();
        if ($settings) {
            $settings->update(['business_logo' => null]);
        }
        
        $this->dispatch('notify', 
            type: 'success',
            message: 'Logo eliminado exitosamente'
        );
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'business_name' => $this->business_name,
            'business_address' => $this->business_address,
            'business_phone' => $this->business_phone,
            'business_email' => $this->business_email,
            'business_tax_id' => $this->business_tax_id,
            'receipt_footer' => $this->receipt_footer,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'timezone' => $this->timezone,
            'currency' => $this->currency,

            // Datos tributarios
            'tax_enabled' => $this->tax_enabled,
            'tax_name' => $this->tax_name,
            'tax_rate' => $this->tax_rate,
            'tax_included_in_price' => $this->tax_included_in_price,
            'retention_enabled' => $this->retention_enabled,
            'retention_rate' => $this->retention_rate,
            'applies_retention_from' => $this->applies_retention_from,

            // Datos de facturación/documentos
            'billing_type' => $this->billing_type,
            'receipt_prefix' => $this->receipt_prefix,
            'receipt_counter' => $this->receipt_counter,
            'receipt_header' => $this->receipt_header,
            'show_tax_disclaimer' => $this->show_tax_disclaimer,
            'invoice_prefix' => $this->invoice_prefix,
            'dian_resolution' => $this->dian_resolution,
            'resolution_date' => $this->resolution_date,
            'range_from' => $this->range_from,
            'range_to' => $this->range_to,
            'resolution_expiry' => $this->resolution_expiry,
            'tax_id_required' => $this->tax_id_required,
            'business_tax_regime' => $this->business_tax_regime,
            
            // Datos de descuentos
            'max_discount_cashier' => $this->max_discount_cashier,
            'max_discount_seller' => $this->max_discount_seller,
            'max_discount_admin' => $this->max_discount_admin,
            'require_discount_reason' => $this->require_discount_reason,
            'require_reason_from' => $this->require_reason_from,
        ];

        // Manejar el logo
        if ($this->business_logo) {
            // Eliminar logo anterior si existe
            if ($this->existing_logo) {
                Storage::disk('public')->delete($this->existing_logo);
            }
            
            // Guardar nuevo logo con compresión
            try {
                // Usar helper de compresión de imágenes
                $logoPath = process_and_save_image($this->business_logo, 'logos', 400, 90);
                $data['business_logo'] = $logoPath;
            } catch (\Exception $e) {
                Log::error('Error saving business logo: ' . $e->getMessage());
                // Fallback: guardar sin compresión
                $data['business_logo'] = $this->business_logo->store('logos', 'public');
            }
        } else {
            $data['business_logo'] = $this->existing_logo;
        }

        // Crear o actualizar configuración
        BusinessSetting::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        // Despachar evento de notificación usando dispatch browser
        $this->dispatch('notify', 
            type: 'success',
            message: '✅ Configuración guardada exitosamente'
        );

        // Recargar configuración
        $this->mount();

        // Limpiar el archivo temporal
        $this->business_logo = null;
    }

    public function render()
    {
        return view('livewire.business-settings-manager', [
            'timezones' => $this->getTimezones(),
            'currencies' => $this->getCurrencies(),
        ]);
    }

    private function getTimezones()
    {
        return [
            'America/Mexico_City' => 'México (CDMX)',
            'America/Tijuana' => 'México (Tijuana)',
            'America/Cancun' => 'México (Cancún)',
            'America/New_York' => 'Estados Unidos (Este)',
            'America/Chicago' => 'Estados Unidos (Central)',
            'America/Los_Angeles' => 'Estados Unidos (Pacífico)',
            'America/Bogota' => 'Colombia',
            'America/Lima' => 'Perú',
            'America/Santiago' => 'Chile',
            'America/Argentina/Buenos_Aires' => 'Argentina',
            'America/Sao_Paulo' => 'Brasil',
            'Europe/Madrid' => 'España',
        ];
    }

    private function getCurrencies()
    {
        return [
            'MXN' => 'MXN - Peso Mexicano',
            'USD' => 'USD - Dólar Estadounidense',
            'EUR' => 'EUR - Euro',
            'COP' => 'COP - Peso Colombiano',
            'PEN' => 'PEN - Sol Peruano',
            'CLP' => 'CLP - Peso Chileno',
            'ARS' => 'ARS - Peso Argentino',
            'BRL' => 'BRL - Real Brasileño',
            'GBP' => 'GBP - Libra Esterlina',
        ];
    }
}
