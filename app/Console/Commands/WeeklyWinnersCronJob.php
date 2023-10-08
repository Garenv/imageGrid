<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeeklyWinnersService;

class WeeklyWinnersCronJob extends Command
{
    /**
     * @var WeeklyWinnersService
     */
    protected $weeklyWinnersService;

    /**
     * @param WeeklyWinnersService $weeklyWinnersService
     */
    public function __construct(WeeklyWinnersService $weeklyWinnersService) {
        parent::__construct();

        $this->weeklyWinnersService = $weeklyWinnersService;
    }

    /**
     * @var string
     */
    protected $signature = 'weekly:winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store weekly winners in db';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->weeklyWinnersService->weeklyWinners();
    }
}
