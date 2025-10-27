<?php

namespace App\Livewire;

use App\Models\TicketSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TicketSettings extends Component
{
    public $business_name;
    public $address;
    public $phone;
    public $email;
    public $tax_id;
    public $ticket_header;
    public $ticket_footer;
    public $show_tax_id;
    public $show_address;
    public $show_phone;
    public $show_email;
    public $receipt_prefix;
    public $receipt_number;
    public $receipt_padding;

    public function mount()
    {
        $settings = TicketSetting::getSettings();
        
        $this->business_name = $settings->business_name;
        $this->address = $settings->address;
        $this->phone = $settings->phone;
        $this->email = $settings->email;
        $this->tax_id = $settings->tax_id;
        $this->ticket_header = $settings->ticket_header;
        $this->ticket_footer = $settings->ticket_footer;
        $this->show_tax_id = $settings->show_tax_id;
        $this->show_address = $settings->show_address;
        $this->show_phone = $settings->show_phone;
        $this->show_email = $settings->show_email;
        $this->receipt_prefix = $settings->receipt_prefix;
        $this->receipt_number = $settings->receipt_number;
        $this->receipt_padding = $settings->receipt_padding;
    }

    public function save()
    {
        $this->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'tax_id' => 'nullable|string|max:50',
            'ticket_header' => 'nullable|string|max:255',
            'ticket_footer' => 'nullable|string|max:255',
            'receipt_prefix' => 'required|string|max:10',
            'receipt_number' => 'required|integer|min:1',
            'receipt_padding' => 'required|integer|min:1|max:10',
        ]);

        try {
            $settings = TicketSetting::getSettings();
            $settings->update([
                'business_name' => $this->business_name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'tax_id' => $this->tax_id,
                'ticket_header' => $this->ticket_header,
                'ticket_footer' => $this->ticket_footer,
                'show_tax_id' => $this->show_tax_id ?? false,
                'show_address' => $this->show_address ?? false,
                'show_phone' => $this->show_phone ?? false,
                'show_email' => $this->show_email ?? false,
                'receipt_prefix' => $this->receipt_prefix,
                'receipt_number' => $this->receipt_number,
                'receipt_padding' => $this->receipt_padding,
            ]);

            // Disparar evento para SweetAlert2
            $this->dispatch('settings-saved');
        } catch (\Exception $e) {
            $this->dispatch('settings-error', message: 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.ticket-settings');
    }
}
