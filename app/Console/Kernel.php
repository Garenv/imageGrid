<?php

namespace App\Console;

use App\Console\Commands\ImageBattlesCronJob;
use App\Console\Commands\TruncateWeeklyWinnersCronJob;
use App\Console\Commands\WeeklyWinnersCronJob;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        WeeklyWinnersCronJob::class,
        TruncateWeeklyWinnersCronJob::class,
        ImageBattlesCronJob::class
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Truncate the winners table every other Sunday at 12:00am EST
        $schedule->command('cron:truncate-weekly-winners')
            ->timezone('America/New_York')
            ->weeklyOn(0)
            ->weekly()
            ->when(function () {
                return Carbon::now()->weekOfYear % 2 == 1; // or == 0 depending on the week you want to start
            });
        $schedule->command('cron:weekly-winners')->timezone('America/New_York')->weekly()->appendOutputTo('storage/logs/scheduler.log');
        $schedule->command('cron:image-battles-daily-winners')->timezone('America/New_York')->daily()->appendOutputTo('storage/logs/imageBattles.log');
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
