<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IImageBattlesRepository;
use App\Models\UserVotes;
use Illuminate\Support\Facades\DB;

class ImageBattlesRepository implements IImageBattlesRepository
{
    /**
     * @param $imageBattlesData
     * @return bool
     */
    public function insertUserImageBattlesData($imageBattlesData): bool
    {
        return DB::table('image_battles')->insert($imageBattlesData);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllUsersImageBattlesData($loggedInUserId)
    {
        $latestAssets = DB::table('image_battles')
            ->select('UserID', DB::raw('MAX(time_stamp) as latest'))
            ->groupBy('UserID');

        return DB::table('users as u')
            ->joinSub($latestAssets, 'latest_assets', function ($join) {
                $join->on('u.UserID', '=', 'latest_assets.UserID');
            })
            ->join('image_battles as ib', function ($join) {
                $join->on('ib.UserID', '=', 'latest_assets.UserID')
                    ->on('ib.time_stamp', '=', 'latest_assets.latest');
            })
            ->leftJoin('votes as v', function ($join) use ($loggedInUserId) {
                $join->on('v.asset_id', '=', 'ib.asset_id')
                    ->where('v.user_id', '=', $loggedInUserId);
            })
            ->select('u.UserID', 'u.name', 'ib.prompt', 'ib.image_url', 'ib.asset_id', 'ib.asset_id', 'v.upvoted')
            ->get();
    }

    public function upVote($loggedInUserId, $assetId)
    {
        return UserVotes::updateOrCreate(['user_id' => $loggedInUserId, 'asset_id' => $assetId], ['upvoted' => 1]);
    }

    public function incrementTotalVoteCount($upvotedUserId)
    {
        return DB::table('image_battles')
            ->where('UserID', $upvotedUserId)
            ->update(['total_vote_count' => DB::raw('total_vote_count + 1'),]);
    }

    public function selectDailyWinners()
    {
        return DB::table('image_battles as ib')
            ->join('users as u', 'u.UserID', '=', 'ib.UserID')
            ->select('u.email', 'u.name', 'ib.prompt', 'ib.UserID', 'ib.asset_id', 'ib.image_url', 'ib.total_vote_count')
            ->orderBy('ib.total_vote_count', 'desc')
//            ->limit(3) // uncomment if we want to have more than one winner in the future (top 3, for example)
            ->first();
    }

    public function getPromptCount($loggedInUserId)
    {
        return DB::table('image_battles')
            ->select('prompt_count')
            ->where('UserID', $loggedInUserId)
            ->first();
    }

    public function checkIfUserHasImageBattlesData($loggedInUserId)
    {
        return DB::table('image_battles')
            ->select('image_url')
            ->where('UserID', $loggedInUserId)
            ->first();
    }

    public function updateUserImageBattlesData($loggedInUserId, $imageBattlesDataToUpdate)
    {
        return DB::table('image_battles')->where('UserID', $loggedInUserId)->update($imageBattlesDataToUpdate);
    }

}
