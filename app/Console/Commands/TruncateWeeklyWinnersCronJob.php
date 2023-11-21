<?php

namespace App\Console\Commands;

use App\Services\WinnersService;
use Illuminate\Console\Command;

class TruncateWeeklyWinnersCronJob extends Command
{
    /**
     * @var WinnersService
     */
    protected $winnersService;

    /**
     * @param WinnersService $winnersService
     */
    public function __construct(WinnersService $winnersService) {
        parent::__construct();

        $this->winnersService = $winnersService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:truncate-weekly-winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->winnersService->truncateWeeklyWinners();
    }
}
