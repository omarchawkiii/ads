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
