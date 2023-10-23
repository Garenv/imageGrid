<?php

namespace App\Dal\Interfaces;

interface IUploadsRepository
{
    public function checkIfUserHasUploaded($userId);

    public function getAllUploadsData();

    public function updateUserAvatarImage($userId, $avatarImageUrl);

    public function getAvatarImage($userId);

    public function insertUserUploadedAsset();
}

