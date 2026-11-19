<?php

namespace App\Console\Commands;

use App\Models\Compaign;
use App\Services\NocAdsPlayedService;
use Illuminate\Console\Command;

class RefreshCompaignPlayouts extends Command
{
    protected $signature = 'compaigns:refresh-playouts
                            {--campaign= : Rafraîchir une seule campagne (id)}
                            {--grace=3 : Jours après end_date pendant lesquels une campagne terminée est encore rafraîchie}';

    protected $description = 'Récupère les diffusions confirmées (NOC ads_played) des campagnes approuvées en cours';

    public function handle(NocAdsPlayedService $noc): int
    {
        $query = Compaign::query()->where('status', 2);

        if ($id = $this->option('campaign')) {
            $query->whereKey($id);
        } else {
            $query->whereDate('start_date', '<=', now())
                  ->whereDate('end_date', '>=', now()->subDays((int) $this->option('grace')));
        }

        $failures = 0;

        $query->each(function (Compaign $compaign) use ($noc, &$failures) {
            $result = $noc->refresh($compaign);

            foreach ($result['chains'] as $chain) {
                $line = "#{$compaign->id} {$compaign->name} / {$chain['cinema_chain']}: "
                      . ($chain['ok'] ? 'ok' : 'ERROR ' . $chain['message']);
                $chain['ok'] ? $this->info($line) : $this->error($line);
                $failures += $chain['ok'] ? 0 : 1;
            }
        });

        return $failures ? self::FAILURE : self::SUCCESS;
    }
}
