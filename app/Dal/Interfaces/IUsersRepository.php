<?php

namespace App\Dal\Interfaces;

interface IUsersRepository
{
    public function incrementDecrementLike($userId, $userLikes, $likeCount);
    public function getUserLikes($userId);
    public function deleteUserUpload($userId);
    public function handleLike($likedUserId);
    public function handleDislike($likedUserId);
    public function getLoggedInUserLikedPhotoData($loggedInUserId);
    public function createUpdateUserLikesData($loggedInUserId, $likedPhotoId);
    public function updateDisklikesData($loggedInUserId, $dislikedPhotoId);
    public function getDataFromUserLikesTable($loggedInUserId);
    public function getUsersPastUploads($loggedInUserId);
    public function hardDeleteProfile();
    public function deleteUser($email);
    public function deleteUserByName($name);
    public function getIpAddresses($ip);
    public function getUserDataForChatBox();
    public function getUserData($email);
}
