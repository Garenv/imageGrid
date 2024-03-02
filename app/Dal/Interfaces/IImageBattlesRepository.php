<?php

namespace App\Dal\Interfaces;

interface IImageBattlesRepository
{
    public function insertUserImageBattlesData($imageBattlesData);

    public function getUserImageBattlesData();
}
