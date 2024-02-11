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
    public function insertUserImageBattlesData($imageBattlesData)
    {
        return DB::table('image_battles')->insert($imageBattlesData);
    }
}
