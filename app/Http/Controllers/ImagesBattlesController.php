<?php

namespace App\Http\Controllers;

use App\Services\DalleService;
use Illuminate\Http\Request;

class ImagesBattlesController extends Controller
{
    protected $dalleService;

    public function __construct(DalleService $dalleService)
    {
        $this->dalleService = $dalleService;
    }

    public function createImage(Request $request)
    {
        $prompt = $request->get('prompt');

        return $this->dalleService->generateImage($prompt);
    }




}
