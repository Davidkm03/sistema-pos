<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DailyReportService;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Log;

class SendDailyWhatsAppReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:daily-report {--force : Force send even if disabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily business report via WhatsApp to owner';

    protected DailyReportService $reportService;

    public function __construct(DailyReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generando reporte diario...');

        // Get business settings
        $settings = BusinessSetting::first();

        if (!$settings) {
            $this->error('❌ No hay configuración de negocio');
            return 1;
        }

        // Check if daily report is enabled
        if (!$settings->whatsapp_daily_report_enabled && !$this->option('force')) {
            $this->warn('⚠️  Reporte diario está desactivado. Usa --force para enviar de todos modos.');
            return 0;
        }

        // Validate WhatsApp number
        if (!$settings->owner_whatsapp) {
            $this->error('❌ No hay número de WhatsApp configurado');
            return 1;
        }

        try {
            // Generate report data
            $this->info('📊 Analizando ventas...');
            $salesData = $this->reportService->getSalesToday();
            
            $this->info('💰 Calculando ganancias...');
            $profitData = $this->reportService->getProfitToday();
            
            $this->info('📦 Revisando inventario...');
            $lowStock = $this->reportService->getLowStockProducts(10);
            $atRisk = $this->reportService->getProductsRiskTomorrow();
            
            $combos = collect([]);
            if ($settings->whatsapp_report_include_combos) {
                $this->info('🎯 Detectando combos...');
                $combos = $this->reportService->getFrequentCombos(3);
            }
            
            $this->info('✨ Generando recomendación IA...');
            $aiRecommendation = $this->reportService->generateAIRecommendation(
                $salesData,
                $profitData,
                $lowStock,
                $atRisk
            );

            // Format WhatsApp message
            $message = $this->reportService->formatWhatsAppMessage(
                $salesData,
                $profitData,
                $lowStock,
                $atRisk,
                $combos,
                $aiRecommendation
            );

            // Generate WhatsApp URL
            $whatsappUrl = $this->reportService->getWhatsAppWebUrl(
                $settings->owner_whatsapp,
                $message
            );

            // Log the report
            Log::channel('daily')->info('Daily WhatsApp Report Generated', [
                'phone' => $settings->owner_whatsapp,
                'sales' => $salesData['total_sales'],
                'revenue' => $salesData['total_revenue'],
                'profit' => $profitData['profit']
            ]);

            $this->newLine();
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('✅ REPORTE GENERADO EXITOSAMENTE');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->newLine();
            $this->line($message);
            $this->newLine();
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('📱 WhatsApp URL:');
            $this->line($whatsappUrl);
            $this->newLine();
            $this->comment('💡 Copia esta URL en el navegador para abrir WhatsApp Web con el mensaje prellenado');
            $this->newLine();

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error generando reporte: ' . $e->getMessage());
            Log::error('Daily WhatsApp Report Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}

