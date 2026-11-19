<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('refreshcontentdata')->everyFiveMinutes();
        $schedule->command('refreshlmsdata')->everyFiveMinutes();
        $schedule->command('refreshscheduledata')->everyFiveMinutes();

        $schedule->command('refreshplaybackdata')->everyFiveMinutes();
        $schedule->command('refreshsnmpdata')->everyFiveMinutes();
        $schedule->command('refreshmacrosdata')->everyFiveMinutes();

        // Diffusions confirmées des campagnes en cours (NOC ads_played), chaque nuit
        $schedule->command('compaigns:refresh-playouts')->dailyAt('03:00')->withoutOverlapping();

        // Facture des campagnes terminées, chaque nuit à minuit
        $schedule->command('invoices:generate')->dailyAt('00:00')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
