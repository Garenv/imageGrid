<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IImageBattlesRepository;
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
    public function getUserImageBattlesData()
    {
        $latestWidgets = DB::table('image_battles')
            ->select('UserID', DB::raw('MAX(time_stamp) as latest'))
            ->groupBy('UserID');

        return DB::table('users as u')
            ->joinSub($latestWidgets, 'latest_widgets', function($join) {
                $join->on('u.UserID', '=', 'latest_widgets.UserID');
            })
            ->join('image_battles as ib', function($join) {
                $join->on('ib.UserID', '=', 'latest_widgets.UserID')
                    ->on('ib.time_stamp', '=', 'latest_widgets.latest');
            })
            ->select('u.UserID', 'u.name', 'ib.prompt')
            ->get();
    }
}
