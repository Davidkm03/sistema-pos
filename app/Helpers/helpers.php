<?php

use App\Models\BusinessSetting;

if (!function_exists('setting')) {
    /**
     * Obtener un valor de configuración del negocio
     * 
     * @param string|null $key Clave de la configuración (ej: 'business_name')
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        $settings = BusinessSetting::current();

        if (is_null($key)) {
            return $settings;
        }

        if (is_object($settings) && isset($settings->$key)) {
            return $settings->$key;
        }

        return $default;
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Obtener el símbolo de la moneda configurada
     * 
     * @return string
     */
    function currency_symbol()
    {
        $currency = setting('currency', 'MXN');

        $symbols = [
            'USD' => '$',
            'MXN' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'COP' => '$',
            'ARS' => '$',
            'CLP' => '$',
            'PEN' => 'S/',
            'BRL' => 'R$',
        ];

        return $symbols[$currency] ?? '$';
    }
}

if (!function_exists('format_currency')) {
    /**
     * Formatear un valor como moneda
     * 
     * @param float $amount
     * @return string
     */
    function format_currency($amount)
    {
        $symbol = currency_symbol();
        $currency = setting('currency', 'MXN');

        return $symbol . number_format($amount, 2);
}
}

// ==========================================
// FUNCIONES TRIBUTARIAS (COLOMBIA)
// ==========================================

if (!function_exists('tax_enabled')) {
    /**
     * Verificar si el sistema de impuestos está habilitado
     * 
     * @return bool
     */
    function tax_enabled()
    {
        return (bool) setting('tax_enabled', false);
    }
}

if (!function_exists('get_tax_rate')) {
    /**
     * Obtener la tasa general de IVA
     * 
     * @return float
     */
    function get_tax_rate()
    {
        return (float) setting('tax_rate', 19.00);
    }
}

if (!function_exists('calculate_tax')) {
    /**
     * Calcular el IVA de un monto
     * 
     * @param float $amount Monto base
     * @param float|null $taxRate Tasa de IVA (si es null usa la tasa general)
     * @return float
     */
    function calculate_tax($amount, $taxRate = null)
    {
        if (!tax_enabled()) {
            return 0;
        }

        $rate = $taxRate ?? get_tax_rate();
        return round($amount * ($rate / 100), 2);
    }
}

if (!function_exists('calculate_price_with_tax')) {
    /**
     * Calcular precio con IVA incluido
     * 
     * @param float $basePrice Precio sin IVA
     * @param float|null $taxRate Tasa de IVA
     * @return float
     */
    function calculate_price_with_tax($basePrice, $taxRate = null)
    {
        if (!tax_enabled()) {
            return $basePrice;
        }

        $tax = calculate_tax($basePrice, $taxRate);
        return round($basePrice + $tax, 2);
    }
}

if (!function_exists('calculate_price_without_tax')) {
    /**
     * Extraer el precio sin IVA de un precio que ya incluye IVA
     * 
     * @param float $priceWithTax Precio con IVA incluido
     * @param float|null $taxRate Tasa de IVA
     * @return float
     */
    function calculate_price_without_tax($priceWithTax, $taxRate = null)
    {
        if (!tax_enabled()) {
            return $priceWithTax;
        }

        $rate = $taxRate ?? get_tax_rate();
        return round($priceWithTax / (1 + ($rate / 100)), 2);
    }
}

if (!function_exists('tax_included_in_price')) {
    /**
     * Verificar si los precios incluyen IVA
     * 
     * @return bool
     */
    function tax_included_in_price()
    {
        return (bool) setting('tax_included_in_price', false);
    }
}

if (!function_exists('retention_enabled')) {
    /**
     * Verificar si las retenciones están habilitadas
     * 
     * @return bool
     */
    function retention_enabled()
    {
        return (bool) setting('retention_enabled', false);
    }
}

if (!function_exists('calculate_retention')) {
    /**
     * Calcular retención en la fuente
     * 
     * @param float $amount Monto base para retención
     * @param object|null $customer Cliente (para verificar si aplica)
     * @return float
     */
    function calculate_retention($amount, $customer = null)
    {
        if (!retention_enabled()) {
            return 0;
        }

        // Verificar monto mínimo
        $minAmount = (float) setting('applies_retention_from', 800000);
        if ($amount < $minAmount) {
            return 0;
        }

        // Verificar si el cliente es agente de retención o régimen común
        if ($customer) {
            if (!$customer->is_retention_agent && $customer->tax_regime !== 'common') {
                return 0;
            }
        }

        $retentionRate = (float) setting('retention_rate', 3.5);
        return round($amount * ($retentionRate / 100), 2);
    }
}

if (!function_exists('format_nit')) {
    /**
     * Formatear NIT colombiano con dígito de verificación
     * 
     * @param string $nit NIT sin formato
     * @return string
     */
    function format_nit($nit)
    {
        if (empty($nit)) {
            return '';
        }

        // Remover caracteres no numéricos excepto el guión
        $nit = preg_replace('/[^0-9\-]/', '', $nit);
        
        // Si ya tiene guión, retornar
        if (strpos($nit, '-') !== false) {
            return $nit;
        }

        // Agregar separadores de miles (opcional)
        // Por ahora solo retornamos el NIT limpio
        return $nit;
    }
}

if (!function_exists('calculate_nit_check_digit')) {
    /**
     * Calcular dígito de verificación de un NIT colombiano
     * 
     * @param string $nit NIT sin dígito de verificación
     * @return int
     */
    function calculate_nit_check_digit($nit)
    {
        $nit = preg_replace('/[^0-9]/', '', $nit);
        
        $primos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];
        $sum = 0;
        $length = strlen($nit);
        
        for ($i = 0; $i < $length; $i++) {
            $sum += (int)$nit[$length - 1 - $i] * $primos[$i];
        }
        
        $remainder = $sum % 11;
        
        if ($remainder >= 2) {
            return 11 - $remainder;
        }
        
        return $remainder;
    }
}

if (!function_exists('validate_nit')) {
    /**
     * Validar NIT colombiano con dígito de verificación
     * 
     * @param string $nitWithCheck NIT completo con dígito (ej: 900123456-7)
     * @return bool
     */
    function validate_nit($nitWithCheck)
    {
        if (empty($nitWithCheck)) {
            return false;
        }

        $parts = explode('-', $nitWithCheck);
        
        if (count($parts) !== 2) {
            return false;
        }
        
        $nit = $parts[0];
        $checkDigit = (int)$parts[1];
        
        $calculatedDigit = calculate_nit_check_digit($nit);
        
        return $checkDigit === $calculatedDigit;
    }
}

if (!function_exists('get_tax_types')) {
    /**
     * Obtener los tipos de IVA disponibles
     * 
     * @return array
     */
    function get_tax_types()
    {
        return [
            'standard' => 'Estándar (' . get_tax_rate() . '%)',
            'exempt' => 'Exento (0%) - Con derecho a descontar',
            'excluded' => 'Excluido (0%) - Sin derecho a descontar',
        ];
    }
}

if (!function_exists('get_tax_type_label')) {
    /**
     * Obtener la etiqueta de un tipo de IVA
     * 
     * @param string $type
     * @return string
     */
    function get_tax_type_label($type)
    {
        $types = get_tax_types();
        return $types[$type] ?? $type;
    }
}

if (!function_exists('get_payment_methods')) {
    /**
     * Obtener métodos de pago disponibles
     * 
     * @return array
     */
    function get_payment_methods()
    {
        return [
            'efectivo' => 'Efectivo',
            'tarjeta_debito' => 'Tarjeta Débito',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'transferencia' => 'Transferencia',
        ];
    }
}

if (!function_exists('get_transfer_types')) {
    /**
     * Obtener tipos de transferencia disponibles
     * 
     * @return array
     */
    function get_transfer_types()
    {
        return [
            'nequi' => 'Nequi',
            'daviplata' => 'Daviplata',
            'bancolombia' => 'Bancolombia',
            'llave' => 'Llave (PSE)',
            'otro' => 'Otro',
        ];
    }
}

if (!function_exists('get_tax_regimes')) {
    /**
     * Obtener regímenes tributarios
     * 
     * @return array
     */
    function get_tax_regimes()
    {
        return [
            'simplified' => 'Régimen Simplificado',
            'common' => 'Régimen Común',
        ];
    }
}

if (!function_exists('get_tax_id_types')) {
    /**
     * Obtener tipos de documento tributario
     * 
     * @return array
     */
    function get_tax_id_types()
    {
        return [
            'CC' => 'Cédula de Ciudadanía',
            'NIT' => 'NIT',
            'CE' => 'Cédula de Extranjería',
            'Pasaporte' => 'Pasaporte',
        ];
    }
}
