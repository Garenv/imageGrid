<?php

namespace App\Dal\Repositories;

use App\Dal\Interfaces\IUploadsRepository;
use App\Models\LegacyUploads;
use App\Models\Uploads;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UploadsRepository implements IUploadsRepository
{
    public function checkIfUserHasUploaded($userId) {
        return DB::table('uploads')
            ->select('UserId', 'isUploaded', 'timeStamp')
            ->where('UserID', $userId)
            ->first();
    }

    public function getAllUploadsData() {
        return Uploads::all();
    }

    public function updateUserAvatarImage($userId, $avatarImageUrl) {
        User::where('UserID', $userId)->update(['avatarImage' => $avatarImageUrl]);
    }

    public function getAvatarImage($userId) {
        return User::select('avatarImage')->where('UserID', $userId)->first();
    }

    public function insertUserUploadedAsset($data) {
        if(Uploads::count() < 200) {
            Uploads::create($data);
            LegacyUploads::create($data);

            return ['status' => 200];
        }

        return ['status' => 400];
    }
}
