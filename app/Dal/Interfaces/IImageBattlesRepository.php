<?php

namespace App\Dal\Interfaces;

interface IImageBattlesRepository
{
    public function insertUserImageBattlesData($imageBattlesData);

    public function getAllUsersImageBattlesData($loggedInUserId);

    public function upVote($loggedInUserId, $assetId);

    public function incrementTotalVoteCount($upvotedUserId);

    public function selectDailyWinners();

    public function getPromptCount($loggedInUserId);

    public function checkIfUserHasImageBattlesData($loggedInUserId);

    public function updateUserImageBattlesData($loggedInUserId, $imageBattlesDataToUpdate);

}
