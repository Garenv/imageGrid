<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVotes extends Model
{
    use HasFactory;

    public $table = "votes";

    public $timestamps = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'asset_id',
        'upvoted',
        'total_vote_count'
    ];
}
