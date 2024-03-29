<?php

namespace App\Console\Commands;

use App\Services\ImageBattlesService;
use Illuminate\Console\Command;

class ImageBattlesCronJob extends Command
{
    /**
     * @var ImageBattlesService
     */
    protected $__imageBattlesService;

    /**
     * @param ImageBattlesService $imageBattlesService
     */
    public function __construct(ImageBattlesService $imageBattlesService)
    {
        parent::__construct();

        $this->__imageBattlesService = $imageBattlesService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:image-battles-cron-job';

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
        $this->__imageBattlesService->selectDailyWinners();
    }
}
