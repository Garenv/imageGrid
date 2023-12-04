<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IWinnersRepository;
use App\Models\Prizes;
use App\Models\Winners;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class WinnersRepository implements IWinnersRepository
{

    public function getPrizeData()
    {
        return Prizes::all();
    }

    public function getTopThreeWinnersFromUploadsTable()
    {
        // Assuming you are using Carbon for date manipulation
        $startOfSunday = Carbon::now('America/New_York')->startOfWeek()->subDay(); // This gets the date for Sunday this week at 12:00 AM EST
        $endOfSunday = $startOfSunday->copy()->addWeek()->subSecond(); // This gets the date for the following Sunday at 11:59 PM EST

        return DB::table('uploads')
            ->select('users.name', 'uploads.likes', 'uploads.url', 'uploads.UserID', 'users.email', 'uploads.timestamp')
            ->join('users', 'users.UserID', '=', 'uploads.UserID')
            ->whereBetween('uploads.timestamp', [$startOfSunday, $endOfSunday]) // Adjusted to the new time range
            ->orderBy('uploads.likes', 'desc')
            ->limit(3)
            ->get();
    }

    public function getLastWeeksWinners()
    {
        return DB::table('winners')
            ->select('users.name', 'winners.likes', 'winners.url', 'winners.place', 'users.UserID')
            ->join('users', 'users.UserID', '=', 'winners.UserID')
            ->orderBy('likes', 'desc')
            ->get();
    }

    public function getAllWinnersFromLegacyWinnersTable()
    {
        return DB::table('legacy_winners')
            ->select('legacy_winners.UserID', 'legacy_winners.likes', 'legacy_winners.place', 'legacy_winners.name', 'legacy_winners.url', 'prizes.prizeName')
            ->join('prizes', 'legacy_winners.prizeId', '=', 'prizes.prizeId')
            ->get();
    }

    public function getThisWeeksWinners($loggedInUserId) {
        return Winners::select('place', 'url')->where('UserID', $loggedInUserId)->first();
    }

}
