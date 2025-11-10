<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\Empresa;

class FixProductsEmpresaId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:products-empresa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asignar empresa_id a productos y categorías que no lo tienen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Iniciando corrección de empresa_id...');
        
        // Obtener la primera empresa disponible como fallback
        $defaultEmpresa = Empresa::first();
        
        if (!$defaultEmpresa) {
            $this->error('❌ No hay empresas en el sistema. Por favor crea una empresa primero.');
            return 1;
        }
        
        // Corregir productos sin empresa_id
        $productsFixed = 0;
        $products = Product::withoutGlobalScopes()->whereNull('empresa_id')->get();
        
        foreach ($products as $product) {
            $empresaId = $defaultEmpresa->id;
            
            // Si el producto tiene una categoría con empresa_id, usar esa
            if ($product->category && $product->category->empresa_id) {
                $empresaId = $product->category->empresa_id;
            }
            
            $product->empresa_id = $empresaId;
            $product->save();
            $productsFixed++;
            
            $this->line("✅ Producto '{$product->name}' asignado a empresa ID: {$empresaId}");
        }
        
        // Corregir categorías sin empresa_id
        $categoriesFixed = 0;
        $categories = Category::withoutGlobalScopes()->whereNull('empresa_id')->get();
        
        foreach ($categories as $category) {
            $category->empresa_id = $defaultEmpresa->id;
            $category->save();
            $categoriesFixed++;
            
            $this->line("✅ Categoría '{$category->name}' asignada a empresa ID: {$defaultEmpresa->id}");
        }
        
        $this->newLine();
        $this->info("✨ Corrección completada:");
        $this->info("   - Productos corregidos: {$productsFixed}");
        $this->info("   - Categorías corregidas: {$categoriesFixed}");
        
        return 0;
    }
}
