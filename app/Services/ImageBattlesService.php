<?php

namespace App\Services;

use App\Dal\Interfaces\IWinnersRepository;
use App\Dal\Repositories\ImageBattlesRepository;
use App\Enums\Activity;
use App\Mail\ImageBattlesWinners;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ImageBattlesService
{

    /**
     * @var ImageBattlesRepository
     */
    protected $__imageBattlesRepository;

    /**
     * @var IWinnersRepository
     */
    protected $__winnersRepository;

    /**
     * @param ImageBattlesRepository $imageBattlesRepository
     * @param IWinnersRepository $winnersRepository
     */
    public function __construct(ImageBattlesRepository $imageBattlesRepository, IWinnersRepository $winnersRepository)
    {
        $this->__imageBattlesRepository = $imageBattlesRepository;
        $this->__winnersRepository = $winnersRepository;
    }

    public function selectDailyWinners()
    {
        try {
            $selectDailyWinners = $this->__imageBattlesRepository->selectDailyWinners();

            $getAllTotalVoteCount = $this->__imageBattlesRepository->getAllTotalVoteCounts();

            $getAllZeroCount = $getAllTotalVoteCount->every(function ($item) {
                return $item->total_vote_count === 0;
            });

            if($getAllZeroCount) {
                Log::channel('image_battles')->error('All assets have 0 votes... no winner will be chosen today.Truncating the image_battles table to make way for the next batch of user generated assets.');

                // truncate the image_battles table to make way for the next batch of user generated assets
                $this->__imageBattlesRepository->truncateImageBattlesTable();

                return false;
            }

            $firstPlaceWinnerEmail = [
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

            $legacyWinnersInsertionData = [
                "email" => $selectDailyWinners->email,
                "UserID" => $selectDailyWinners->UserID,
                "name" => $selectDailyWinners->name,
                "activity" => Activity::ImageBattles->value,
                "prompt" => $selectDailyWinners->prompt,
                "place" => '1st Place',
                "votes" => $selectDailyWinners->total_vote_count,
                "winnerId" => 'w-' . Str::uuid()->toString(),
                "url" => $selectDailyWinners->image_url,
                "prizeId" => '2',
                'timeStamp' => Carbon::now()->toDateTimeString()
            ];

            try {

                Mail::to($firstPlaceWinnerEmail['email'])->send(new ImageBattlesWinners($firstPlaceWinnerEmail));

                // insert winner in legacy_winners table
                $this->__winnersRepository->insertIntoLegacyWinnersTable($legacyWinnersInsertionData);

                // truncate the image_battles table to make way for the next batch of user generated assets
                $this->__imageBattlesRepository->truncateImageBattlesTable();

            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
