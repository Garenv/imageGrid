<?php

namespace App\Services;

use App\Dal\Interfaces\IUploadsRepository;
use App\Dal\Interfaces\IWinnersRepository;
use App\Enums\Activity;
use App\Mail\WeeklyWinners;
use App\Models\LastWeeksWinners;
use App\Models\LegacyWinners;
use App\Models\Uploads;
use App\Models\Winners;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WinnersService
{

    /**
     * @var IWinnersRepository
     */
    protected $__winnersRepository;

    /**
     * @var IUploadsRepository
     */
    protected $__uploadsRepository;

    /**
     * @param IWinnersRepository $winnersRepository
     */
    public function __construct(IWinnersRepository $winnersRepository, IUploadsRepository $uploadsRepository)
    {
        $this->__winnersRepository = $winnersRepository;
        $this->__uploadsRepository = $uploadsRepository;
    }

    public function weeklyWinners()
    {
        try {
            // Get top three winners by joining uploads and users tables
            $topThreeWinners = $this->__winnersRepository->getTopThreeWinnersFromUploadsTable();

            // Get all likes from the uploads table
            $getAllTotalLikes = $this->__uploadsRepository->getAllLikes();

            if($topThreeWinners->isEmpty()) {
                Log::channel('grid')->error('No uploads this week');

                return false;
            }

            // check if all uploads have 0 likes
            $getAllZeroCount = $getAllTotalLikes->every(function ($item) {
                return $item->likes === 0;
            });

            if($getAllZeroCount) {
                Log::channel('grid')->error('All assets have 0 likes... no winner will be chosen this week.Truncating the uploads table to make way for the next batch of user generated assets.');

                Uploads::truncate();

                return false;
            }

            // Get prize data
            $prizesData = $this->__winnersRepository->getPrizeData();

            // Get timestamp of when the winner was chosen
            $time = Carbon::now();
            $timeStamp = $time->toDateTimeString();

            // First place data
            $firstPlaceUserId = $topThreeWinners[0]->UserID;
            $firstPlaceLikes = $topThreeWinners[0]->likes;
            $firstPlaceWinnerId = 'w-' . Str::uuid()->toString();
            $firstPlaceUrl = $topThreeWinners[0]->url;
            $firstPlacePrizeId = $prizesData[0]->prizeId;
            $firstPlaceName = $topThreeWinners[0]->name;
            $firstPlaceEmail = $topThreeWinners[0]->email;

            // Second place data
            $secondPlaceUserId = $topThreeWinners[1]->UserID;
            $secondPlaceLikes = $topThreeWinners[1]->likes;
            $secondPlaceWinnerId = 'w-' . Str::uuid()->toString();
            $secondPlaceUrl = $topThreeWinners[1]->url;
            $secondPlacePrizeId = $prizesData[1]->prizeId;
            $secondPlaceName = $topThreeWinners[1]->name;
            $secondPlaceEmail = $topThreeWinners[1]->email;

            // Third place data
            $thirdPlaceUserId = $topThreeWinners[2]->UserID;
            $thirdPlaceLikes = $topThreeWinners[2]->likes;
            $thirdPlaceWinnerId = 'w-' . Str::uuid()->toString();
            $thirdPlaceUrl = $topThreeWinners[2]->url;
            $thirdPlacePrizeId = $prizesData[2]->prizeId;
            $thirdPlaceName = $topThreeWinners[2]->name;
            $thirdPlaceEmail = $topThreeWinners[2]->email;

            $emailDataFirstPlace = [
                'to' => $firstPlaceEmail,
                'from' => 'noreply@phopixel.com',
                'place' => "1st Place",
                'winnerName' => $firstPlaceName,
                'subject' => "You're the 1st Place Winner!"
            ];

            $emailDataSecondPlace = [
                'to' => $secondPlaceEmail,
                'from' => 'noreply@phopixel.com',
                'place' => "2nd Place",
                'winnerName' => $secondPlaceName,
                'subject' => "You're the 2nd Place Winner!"
            ];

            $emailDataThirdPlace = [
                'to' => $thirdPlaceEmail,
                'from' => 'noreply@phopixel.com',
                'place' => "3rd Place",
                'winnerName' => $thirdPlaceName,
                'subject' => "You're the 3rd Place Winner!"
            ];

            Mail::to($firstPlaceEmail)->send(new WeeklyWinners($emailDataFirstPlace));
            Mail::to($secondPlaceEmail)->send(new WeeklyWinners($emailDataSecondPlace));
            Mail::to($thirdPlaceEmail)->send(new WeeklyWinners($emailDataThirdPlace));

            $winnersDataFirstPlace = [
                'UserID' => $firstPlaceUserId,
                'name' => $firstPlaceName,
                'email' => $firstPlaceEmail,
                'activity' => Activity::Grid->value,
                'place' => "1st Place",
                'likes' => $firstPlaceLikes,
                'winnerId' => $firstPlaceWinnerId,
                'url' => $firstPlaceUrl,
                'prizeId' => $firstPlacePrizeId,
                'timeStamp' => $timeStamp,
            ];

            $winnersDataSecondPlace = [
                'UserID' => $secondPlaceUserId,
                'name' => $secondPlaceName,
                'email' => $secondPlaceEmail,
                'activity' => Activity::Grid->value,
                'place' => "2nd Place",
                'likes' => $secondPlaceLikes,
                'winnerId' => $secondPlaceWinnerId,
                'url' => $secondPlaceUrl,
                'prizeId' => $secondPlacePrizeId,
                'timeStamp' => $timeStamp,
            ];

            $winnersDataThirdPlace = [
                'UserID' => $thirdPlaceUserId,
                'name' => $thirdPlaceName,
                'email' => $thirdPlaceEmail,
                'activity' => Activity::Grid->value,
                'place' => "3rd Place",
                'likes' => $thirdPlaceLikes,
                'winnerId' => $thirdPlaceWinnerId,
                'url' => $thirdPlaceUrl,
                'prizeId' => $thirdPlacePrizeId,
                'timeStamp' => $timeStamp,
            ];

            // store them in winners table
            Winners::create($winnersDataFirstPlace);
            Winners::create($winnersDataSecondPlace);
            Winners::create($winnersDataThirdPlace);

            // store winners in the legacy_winners table
            LegacyWinners::create($winnersDataFirstPlace);
            LegacyWinners::create($winnersDataSecondPlace);
            LegacyWinners::create($winnersDataThirdPlace);

            // Truncate the data in the uploads table to make way for the coming week's uploads
            if (Uploads::count() > 0) {
                Uploads::truncate();
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function truncateWeeklyWinners() {
        Winners::truncate();
    }

}
