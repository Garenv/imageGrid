<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WinnersService;

class WeeklyWinnersCronJob extends Command
{
    /**
     * @var WinnersService
     */
    protected $weeklyWinnersService;

    /**
     * @param WinnersService $weeklyWinnersService
     */
    public function __construct(WinnersService $weeklyWinnersService) {
        parent::__construct();

        $this->weeklyWinnersService = $weeklyWinnersService;
    }

    /**
     * @var string
     */
    protected $signature = 'cron:weekly-winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store winners in the winners table and legacy_winners table as well as truncates the uploads table to make way for new winners the coming week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->weeklyWinnersService->weeklyWinners();
    }
}
