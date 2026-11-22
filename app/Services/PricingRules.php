<?php

namespace App\Services;

use App\Models\Compaign;
use App\Models\Config;
use App\Models\Location;
use App\Models\PricingSeason;
use App\Models\Slot;
use Carbon\Carbon;

/**
 * Règles tarifaires inspirées du deck « AdSmart Network » :
 *
 *   prix par diffusion = prix de base × position × ciblage × durée × saison × volume   (plafonné)
 *
 * Le prix de base est le prix additif de la plateforme (location + slot + types de salle).
 * Toutes les valeurs viennent de la config (réglables par l'admin) ; à défaut, de Config::PRICING_DEFAULTS.
 */
class PricingRules
{
    private array $settings;

    public function __construct(?Config $config = null)
    {
        $config ??= Config::first();

        $this->settings = [];
        foreach (Config::PRICING_DEFAULTS as $key => $default) {
            $value = $config?->{$key};
            $this->settings[$key] = $value !== null ? (float) $value : (float) $default;
        }
    }

    public function setting(string $key): float
    {
        return $this->settings[$key];
    }

    /** Position dans la séance, portée par le slot (null / inconnue = premier segment). */
    public function position(?Slot $slot): float
    {
        return match ($slot?->position_tier) {
            'middle'  => $this->settings['position_middle_multiplier'],
            'premium' => $this->settings['position_premium_multiplier'],
            default   => $this->settings['position_first_multiplier'],
        };
    }

    /**
     * Ciblage de la campagne : un seul niveau, le plus haut applicable.
     *  audience (audience / centres d'intérêt) > film précis > région (sous-ensemble de sites) > tous les sites.
     *
     * @return array{0: float, 1: string} [multiplicateur, niveau]
     */
    public function targeting(Compaign $compaign): array
    {
        if ($compaign->targetTypes()->exists() || $compaign->interests()->exists()) {
            return [$this->settings['target_audience_multiplier'], 'audience'];
        }

        if ($compaign->masterMovies()->exists() || $compaign->movies()->exists()) {
            return [$this->settings['target_movie_multiplier'], 'movie'];
        }

        if ($this->isRegional($compaign)) {
            return [$this->settings['target_region_multiplier'], 'region'];
        }

        return [1.0, 'all'];
    }

    /** Vrai si la campagne ne couvre qu'une partie des sites du réseau. */
    private function isRegional(Compaign $compaign): bool
    {
        $total = Location::count();
        if ($total === 0) {
            return false;
        }

        $locationIds = $compaign->locations()->pluck('locations.id');
        if ($locationIds->isNotEmpty()) {
            return $locationIds->unique()->count() < $total;
        }

        $chainIds = $compaign->cinemaChains()->pluck('cinema_chains.id');
        if ($chainIds->isNotEmpty()) {
            return Location::whereIn('cinema_chain_id', $chainIds)->count() < $total;
        }

        return false;
    }

    /** Durée de la pub en secondes : tranches <= 15, <= 30, <= 45, au-delà (60 s). */
    public function length(?int $seconds): float
    {
        $s = (int) $seconds;

        return match (true) {
            $s <= 0  => $this->settings['length_30_multiplier'], // durée inconnue : tarif standard
            $s <= 15 => $this->settings['length_15_multiplier'],
            $s <= 30 => $this->settings['length_30_multiplier'],
            $s <= 45 => $this->settings['length_45_multiplier'],
            default  => $this->settings['length_60_multiplier'],
        };
    }

    /**
     * Saison : multiplicateur moyen des jours de la campagne. Chaque jour prend le multiplicateur
     * le plus haut des saisons qui le couvrent (1.0 hors saison) ; le résultat est la moyenne sur la période.
     */
    public function season(?string $start, ?string $end): float
    {
        if (!$start || !$end) {
            return 1.0;
        }

        $from = Carbon::parse($start)->startOfDay();
        $to   = Carbon::parse($end)->startOfDay();
        if ($to->lt($from)) {
            return 1.0;
        }

        $seasons = PricingSeason::where('end_date', '>=', $from->toDateString())
            ->where('start_date', '<=', $to->toDateString())
            ->get();

        if ($seasons->isEmpty()) {
            return 1.0;
        }

        $days = 0;
        $sum  = 0.0;
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $day  = $d->toDateString();
            $mult = 1.0;
            foreach ($seasons as $s) {
                if ($s->start_date->toDateString() <= $day && $s->end_date->toDateString() >= $day) {
                    $mult = max($mult, (float) $s->multiplier);
                }
            }
            $sum += $mult;
            $days++;
        }

        return round($sum / $days, 3);
    }

    /** Remise volume : campagne d'au moins N semaines. */
    public function volume(int $days): float
    {
        $minDays = (int) $this->settings['volume_min_weeks'] * 7;

        return ($minDays > 0 && $days >= $minDays) ? $this->settings['volume_multiplier'] : 1.0;
    }

    /**
     * Cumul des multiplicateurs et plafond.
     *
     * @return array{0: float, 1: float} [cumul brut, cumul appliqué (plafonné)]
     */
    public function combine(float ...$multipliers): array
    {
        $raw = round(array_product($multipliers), 3);
        $cap = $this->settings['max_multiplier'];

        return [$raw, $cap > 0 ? min($raw, $cap) : $raw];
    }

    /** Nombre de jours (inclus) de la campagne, au moins 1. */
    public function days(Compaign $compaign): int
    {
        if (!$compaign->start_date || !$compaign->end_date) {
            return 1;
        }

        $days = Carbon::parse($compaign->start_date)->startOfDay()
            ->diffInDays(Carbon::parse($compaign->end_date)->startOfDay()) + 1;

        return max(1, $days);
    }

    /** Semaines facturables (minimum par cinéma et par semaine) : jours / 7, arrondi au supérieur. */
    public function weeks(Compaign $compaign): int
    {
        return (int) ceil($this->days($compaign) / 7);
    }

    public function minimumPerOutletWeek(): float
    {
        return $this->settings['min_outlet_week_price'];
    }
}
