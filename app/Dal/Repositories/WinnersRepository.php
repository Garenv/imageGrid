<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IWinnersRepository;
use App\Models\LegacyWinners;
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
        // set the start of the week to Sunday 12:01 AM
        $startOfSunday = Carbon::now('America/New_York')->startOfWeek(Carbon::SUNDAY)->addMinute();

        // set the end of the week to Saturday 11:59 PM
        $endOfSaturday = $startOfSunday->copy()->addWeek()->subMinute();

        return DB::table('uploads')
            ->select('users.name', 'uploads.likes', 'uploads.url', 'uploads.UserID', 'users.email', 'uploads.timestamp')
            ->join('users', 'users.UserID', '=', 'uploads.UserID')
            ->whereBetween('uploads.timestamp', [$startOfSunday, $endOfSaturday])
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

    public function insertIntoLegacyWinnersTable($legacyWinnersInsertionData)
    {
        LegacyWinners::create($legacyWinnersInsertionData);
    }

}
