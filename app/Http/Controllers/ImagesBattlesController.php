<?php

namespace App\Http\Controllers;

use App\Services\DalleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ImagesBattlesController extends Controller
{
    protected $dalleService;

    public function __construct(DalleService $dalleService)
    {
        $this->dalleService = $dalleService;
    }

    public function generateImage(Request $request)
    {
        $prompt = $request->get('prompt');
        $userId = $request->get('UserID');

        return $this->dalleService->generateImage($prompt, $userId);
    }

    public function getImageBattlesData()
    {
        return $this->dalleService->getImageBattlesData();
    }

}
