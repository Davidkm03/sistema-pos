<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Console\Command;

class MigrateTaxData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar datos existentes al nuevo sistema tributario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando migración de datos tributarios...');
        $this->newLine();

        // Actualizar productos sin tipo de IVA
        $this->info('📦 Actualizando productos...');
        $products = Product::whereNull('tax_type')->get();

        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $product) {
            $product->update([
                'tax_type' => 'standard',
                'tax_rate' => null, // Usará la tasa general
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Productos actualizados: {$products->count()}");
        $this->newLine();

        // Actualizar ventas antiguas sin información tributaria
        $this->info('💰 Actualizando ventas antiguas...');
        $sales = Sale::where(function($query) {
            $query->whereNull('subtotal')
                  ->orWhere('subtotal', 0);
        })->get();

        $salesBar = $this->output->createProgressBar(count($sales));
        $salesBar->start();

        foreach ($sales as $sale) {
            $sale->load('saleItems.product');

            $subtotal = 0;
            $taxAmount = 0;

            // Recalcular cada item con IVA
            foreach ($sale->saleItems as $item) {
                if ($item->product) {
                    $unitPrice = $item->product->getPriceWithoutTax();
                    $taxRate = $item->product->getEffectiveTaxRate();
                    $itemTaxAmount = calculate_tax($unitPrice * $item->quantity, $taxRate);
                    $itemSubtotal = $unitPrice * $item->quantity;

                    // Actualizar el item
                    $item->update([
                        'unit_price' => $unitPrice,
                        'tax_rate' => $taxRate,
                        'tax_amount' => $itemTaxAmount,
                        'subtotal' => $itemSubtotal,
                        'total' => $itemSubtotal + $itemTaxAmount,
                    ]);

                    $subtotal += $itemSubtotal;
                    $taxAmount += $itemTaxAmount;
                }
            }

            // Actualizar la venta con los nuevos totales
            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'retention_amount' => 0, // Las ventas antiguas no tienen retención
            ]);

            $salesBar->advance();
        }

        $salesBar->finish();
        $this->newLine();
        $this->info("✅ Ventas actualizadas: {$sales->count()}");
        $this->newLine(2);

        $this->info('🎉 ¡Migración completada exitosamente!');
        $this->newLine();

        return Command::SUCCESS;
    }
}
