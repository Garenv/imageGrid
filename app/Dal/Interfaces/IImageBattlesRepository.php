<?php

namespace App\Dal\Interfaces;

interface IImageBattlesRepository
{
    public function insertUserImageBattlesData($imageBattlesDataForNewDbInsertion);

    public function getAllUsersImageBattlesData($loggedInUserId);

    public function upVote($loggedInUserId, $assetId);

    public function incrementTotalVoteCount($upvotedUserId);

    public function selectDailyWinners();

    public function getPromptCount($loggedInUserId);

    public function checkIfUserHasImageBattlesData($loggedInUserId);

    public function updateUserImageBattlesData($loggedInUserId, $imageBattlesDataToUpdate);

    public function getAllTotalVoteCounts();

    public function truncateImageBattlesTable();

    public function getHallOfFameInductees();

    public function getYourPastImages($loggedInUserId);
}
