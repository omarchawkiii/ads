<?php

namespace App\Console\Commands;

use App\Models\Compaign;
use App\Services\InvoiceGenerator;
use App\Services\NocAdsPlayedService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateCompaignInvoices extends Command
{
    protected $signature = 'invoices:generate
                            {--campaign= : Limiter à une campagne (id)}
                            {--wait=7 : Jours d\'attente maximum après end_date pour que le NOC ait tout synchronisé}';

    protected $description = 'Génère la facture (diffusions × prix location + slot) des campagnes terminées sans facture';

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
                // 1. Diffusions à jour avant de calculer.
                $noc->refresh($compaign);
                $summary = $noc->summary($compaign);

                if (!$summary['fetched']) {
                    $this->warn("$label: no data from any NOC, retry tomorrow.");
                    return;
                }

                // 2. Le NOC doit avoir traité les logs jusqu'à la fin de la campagne,
                //    sinon la facture serait sous-évaluée (on n'attend que --wait jours).
                $endOfCampaign = Carbon::parse($compaign->end_date)->endOfDay();
                $waitedTooLong = now()->gt($endOfCampaign->copy()->addDays((int) $this->option('wait')));
                $synced        = $summary['synced_until'] ? Carbon::parse($summary['synced_until']) : null;

                if ((!$synced || $synced->lt($endOfCampaign)) && !$waitedTooLong) {
                    $this->warn("$label: NOC synced until " . ($synced?->toDateTimeString() ?? 'never') . ", waiting.");
                    return;
                }

                // 3. Facture.
                $invoice = $generator->generate($compaign);

                if (!$invoice) {
                    $this->warn("$label: nothing to invoice (no confirmed play or zero amount).");
                    return;
                }
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
