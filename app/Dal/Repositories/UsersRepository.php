<?php

namespace App\Dal\Repositories;


use App\Dal\Interfaces\IUsersRepository;
use App\Models\LegacyUploads;
use App\Models\Uploads;
use App\Models\User;
use App\Models\UserLikes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersRepository implements IUsersRepository
{
    public function incrementDecrementLike($userId, $userLikes, $likeCount)
    {
        $data = ['likes' => $likeCount > 1 ? $userLikes - 1 : $userLikes + 1];
        DB::table('uploads')
            ->where('UserID', $userId)
            ->update($data);
        return $data['likes'];
    }

    public function getUserLikes($userId)
    {
        return DB::table('uploads')
            ->select('likes', 'photo_id')
            ->where('UserID', $userId)
            ->get();
    }

    public function getUserUploadsForThisWeek($loggedInUserId, $sortByLikes)
    {
        return DB::table('uploads')
            ->select('uploads.url', 'uploads.likes', 'users.name', 'users.UserID', 'uploads.photo_id', 'user_likes.is_liked')
            ->leftJoin('users', 'users.UserID', '=', 'uploads.UserID')
            ->leftJoin('user_likes', function ($q) use ($loggedInUserId) {
                $q->where('user_likes.user_id', '=', $loggedInUserId)
                    ->on('user_likes.photo_id', '=', 'uploads.photo_id');
            })
            // assigns a lower value (0) when the UserID of the upload matches the loggedInUserId, making these rows appear first in the results
            // after sorting by user priority, the results are further sorted by timeStamp in descending order
            // the logged in user ID is passed as a parameter to the raw query to prevent SQL injection
            ->orderByRaw("CASE WHEN uploads.UserID = ? THEN 0 ELSE 1 END", [$loggedInUserId])
            ->orderBy('uploads.likes', $sortByLikes)
            ->orderBy('uploads.timeStamp', 'DESC')
            ->get();
    }

    public function deleteUserUpload($userId)
    {
        return Uploads::where('UserID', $userId)->delete();
    }

    public function getLoggedInUserLikedPhotoData($loggedInUserId)
    {
        return UserLikes::select('user_id', 'photo_id', 'is_liked')->where('user_id', '=', $loggedInUserId)->get();
    }

    public function handleLike($likedUserId)
    {
        Uploads::where(['UserID' => $likedUserId])->update(['likes' => DB::raw('likes + 1')]);
    }

    public function handleDislike($likedUserId)
    {
        Uploads::where(['UserID' => $likedUserId])->update(['likes' => DB::raw('likes - 1')]);
    }

    public function createUpdateUserLikesData($loggedInUserId, $likedPhotoId)
    {
        return UserLikes::updateOrCreate(['user_id' => $loggedInUserId, 'photo_id' => $likedPhotoId], ['is_liked' => 1]);
    }

    public function updateDisklikesData($loggedInUserId, $dislikedPhotoId)
    {
        return UserLikes::updateOrCreate(['user_id' => $loggedInUserId, 'photo_id' => $dislikedPhotoId, 'is_liked' => 1], ['is_liked' => 0]);
    }

    public function getDataFromUserLikesTable($loggedInUserId)
    {
        return UserLikes::where(['user_id' => $loggedInUserId])->get();
    }

    public function getUsersPastUploads($loggedInUserId)
    {
        return LegacyUploads::select('url', 'likes')->where('UserID', '=', $loggedInUserId)->get();
    }

    public function getIpAddresses($ip)
    {
        return User::where('ip', $ip)->first();
    }

    public function hardDeleteProfile() {
        return User::where('UserID', Auth::user()['UserID'])->delete();
    }

}



