<?php

namespace App\Services;

use App\Dal\Interfaces\IWinnersRepository;
use App\Mail\WeeklyWinners;
use App\Models\LegacyWinners;
use App\Models\Uploads;
use App\Models\Winners;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WinnersService
{

    protected $__winnersRepository;

    /**
     * @param IWinnersRepository $winnersRepository
     */
    public function __construct(IWinnersRepository $winnersRepository)
    {
        $this->__winnersRepository = $winnersRepository;
    }

    public function weeklyWinners()
    {
        try {
            // Get top three winners by joining uploads and users tables
            $topThreeWinners = $this->__winnersRepository->getTopThreeWinnersFromUploadsTable();

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
                'email' => $firstPlaceEmail,
                'place' => "1st Place",
                'likes' => $firstPlaceLikes,
                'winnerId' => $firstPlaceWinnerId,
                'url' => $firstPlaceUrl,
                'prizeId' => $firstPlacePrizeId,
                'timeStamp' => $timeStamp,
                'name' => $firstPlaceName
            ];

            $winnersDataSecondPlace = [
                'UserID' => $secondPlaceUserId,
                'email' => $secondPlaceEmail,
                'place' => "2nd Place",
                'likes' => $secondPlaceLikes,
                'winnerId' => $secondPlaceWinnerId,
                'url' => $secondPlaceUrl,
                'prizeId' => $secondPlacePrizeId,
                'timeStamp' => $timeStamp,
                'name' => $secondPlaceName
            ];

            $winnersDataThirdPlace = [
                'UserID' => $thirdPlaceUserId,
                'email' => $thirdPlaceEmail,
                'place' => "3rd Place",
                'likes' => $thirdPlaceLikes,
                'winnerId' => $thirdPlaceWinnerId,
                'url' => $thirdPlaceUrl,
                'prizeId' => $thirdPlacePrizeId,
                'timeStamp' => $timeStamp,
                'name' => $thirdPlaceName
            ];

            // store them in winners table
            Winners::create($winnersDataFirstPlace);
            Winners::create($winnersDataSecondPlace);
            Winners::create($winnersDataThirdPlace);

            // store in legacy winners table
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
