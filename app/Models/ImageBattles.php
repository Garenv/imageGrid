<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageBattles extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserID',
        'asset_id',
        'image_url',
        'prompt',
        'time_stamp',
        'total_vote_count',
        'prompt_count'
    ];

}
