<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IImageBattlesRepository;
use Illuminate\Support\Facades\DB;

class ImageBattlesRepository implements IImageBattlesRepository
{
    public function storeUserImage($imageBattlesData)
    {
        return DB::table('image_battles')->insert($imageBattlesData);
    }
}
