<?php

namespace App\Console\Commands;

use App\Models\Compaign;
use App\Services\InvoiceGenerator;
use App\Services\NocAdsPlayedService;
use Illuminate\Console\Command;

class GenerateCompaignInvoices extends Command
{
    protected $signature = 'invoices:generate
                            {--campaign= : Limiter à une campagne (id)}';

    protected $description = 'Génère la facture (diffusions × prix location + slot, 0 si aucune diffusion) de toute campagne terminée sans facture';

    public function handle(InvoiceGenerator $generator, NocAdsPlayedService $noc): int
    {
        $query = Compaign::query()
            ->where('status', 2)
            ->whereDate('end_date', '<', now()->toDateString())
            ->whereDoesntHave('invoices');

        if ($id = $this->option('campaign')) {
            $query->whereKey($id);
        }

        $failed = 0;

        $query->each(function (Compaign $compaign) use ($generator, $noc, &$failed) {
            $label = "#{$compaign->id} {$compaign->name}";

            try {
                // Diffusions à jour si le NOC répond ; sinon on facture avec ce qui est stocké
                // (éventuellement rien : facture à 0).
                try {
                    $noc->refresh($compaign);
                } catch (\Throwable $e) {
                    $this->warn("$label: NOC refresh failed ({$e->getMessage()}), using stored data.");
                }

                $invoice = $generator->generate($compaign);
                $this->info("$label: {$invoice->number} ({$invoice->total_ttc})");
            } catch (\Throwable $e) {
                $failed++;
                report($e);
                $this->error("$label: {$e->getMessage()}");
            }
        });

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
