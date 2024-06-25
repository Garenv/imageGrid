<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegacyWinners extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'UserID',
        'email',
        'place',
        'likes',
        'votes',
        'activity',
        'prompt',
        'winnerId',
        'url',
        'prizeId',
        'timeStamp',
        'name'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}
