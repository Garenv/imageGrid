<?php

namespace App\Services;

use App\Dal\Interfaces\IImageBattlesRepository;
use App\Dal\Repositories\ImageBattlesRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DalleService
{
    protected $apiKey;

    /**
     * @var ImageBattlesRepository
     */
    protected $__imageBattlesRepository;

    /**
     * @param IImageBattlesRepository $imageBattlesRepository
     */
    public function __construct(IImageBattlesRepository $imageBattlesRepository)
    {
        $this->__imageBattlesRepository = $imageBattlesRepository;
    }

    public function generateImage($prompt, $userId, $name)
    {
        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('app.dalle_api_key'),
                'Content-Type' => 'application/json',
            ])->post(config('app.dalle_api'), [
                'prompt' => $prompt
            ]);

            if($response->failed()) {
                return response()->json(['message' => 'Something went wrong!'], $response->status());
            }

            $jsonResponse = $response->json();
            $imageUrl = $jsonResponse['data'][0]['url'];
            $timeStamp = $jsonResponse['created'];

            $imageContentResponse = Http::get($imageUrl);

            if($imageContentResponse->failed()) {
                return response()->json(['message' => "Something went wrong with image creation, we've been notified and are looking into it!"], $imageContentResponse->status());
            }

            $imageContent = $imageContentResponse->body();

            $fileName = Str::slug(substr($prompt, 0, 20)) . '_' . time(); // generating a unique file name based on the prompt

            $path = getS3PathForEnv() . '/imageBattleUploads/' . $fileName;

            Storage::disk('s3')->put($path, $imageContent); // store the image in S3

            $imageBattlesDataDb = [
                'prompt' => $prompt,
                'image_url' => $imageUrl,
                'time_stamp' => $timeStamp,
                'UserID' => $userId,
            ];

            $imageBattlesDataRedis = [
                'prompt' => $prompt,
                'image_url' => $imageUrl,
                'time_stamp' => $timeStamp,
                'UserID' => $userId,
                'name' => $name
            ];

            $storeUserImage = $this->__imageBattlesRepository->insertUserImageBattlesData($imageBattlesDataDb);

            try {
                if(!$storeUserImage) {
                    return response()->json(['message' => "Something went wrong!"], 422);
                }

                $key = 'image_battles_data:' . $userId;
                $jsonEncodedRedisData = json_encode($imageBattlesDataRedis);

                setRedisKey($key, $jsonEncodedRedisData);
            } catch (\Exception $e) {
                Log::error("Redis Error: " . $e->getMessage());
            }

            return response()->json($imageBattlesDataDb);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
