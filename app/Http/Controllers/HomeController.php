<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BusinessSetting;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener productos destacados (máximo 6)
        $featuredProducts = Product::featured()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Obtener productos en promoción (máximo 8)
        $saleProducts = Product::onSale()
            ->orderBy('discount_percentage', 'desc')
            ->limit(8)
            ->get();

        // Obtener configuración del negocio para el nombre y logo
        $businessSettings = BusinessSetting::current();

        return view('welcome', compact('featuredProducts', 'saleProducts', 'businessSettings'));
    }
}
