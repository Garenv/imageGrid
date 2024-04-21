<?php

namespace App\Dal\Interfaces;

interface IWinnersRepository
{
    public function getPrizeData();

    public function getTopThreeWinnersFromUploadsTable();

    public function getAllWinnersFromLegacyWinnersTable();

    public function getThisWeeksWinners($loggedInUserId);

    public function getLastWeeksWinners();

    public function insertIntoLegacyWinnersTable($legacyWinnersInsertionData);
}
