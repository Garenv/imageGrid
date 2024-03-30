<?php

namespace App\Services;

use App\Dal\Repositories\ImageBattlesRepository;
use App\Mail\ImageBattlesWinners;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ImageBattlesService
{

    /**
     * @var ImageBattlesRepository
     */
    protected $__imageBattlesRepository;

    public function __construct(ImageBattlesRepository $imageBattlesRepository)
    {
        $this->__imageBattlesRepository = $imageBattlesRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function selectDailyWinners()
    {
        try {
            $selectDailyWinners = $this->__imageBattlesRepository->selectDailyWinners();

            $firstPlaceWinner = [
                "email" => $selectDailyWinners->email,
                "from" => 'noreply@phopixel.com',
                "subject" => "You've won today's Image Battles!",
                "name" => $selectDailyWinners->name,
                "prompt" => $selectDailyWinners->prompt,
                "UserID" => $selectDailyWinners->UserID,
                "asset_id" => $selectDailyWinners->asset_id,
                "image_url" => $selectDailyWinners->image_url,
                "total_vote_count" => $selectDailyWinners->total_vote_count
            ];

            try {
                Mail::to($firstPlaceWinner['email'])->send(new ImageBattlesWinners($firstPlaceWinner));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
