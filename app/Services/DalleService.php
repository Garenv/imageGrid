<?php

namespace App\Services;

use App\Dal\Interfaces\IImageBattlesRepository;
use App\Dal\Repositories\ImageBattlesRepository;
use App\Events\ImageBattlesAssetGeneratedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function PHPUnit\Framework\once;

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

    public function generateImage($prompt, $userId)
    {

        try {

            $getPromptCount = $this->__imageBattlesRepository->getPromptCount($userId);

            // if the prompt is empty or if the prompt only contains whitespaces as a result of
            // the user pressing the spacebar, then throw an error stating so
            if(!$prompt || trim($prompt) === '')  {
                return response()->json(['message' => 'The Prompt field must not be empty!'], 400);
            }

            if(is_null($getPromptCount) || $getPromptCount->prompt_count < 2) {

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
                $assetId = 'a-' . Str::uuid()->toString();

                $imageContentResponse = Http::get($imageUrl);

                if($imageContentResponse->failed()) {
                    return response()->json(['message' => "Something went wrong with image creation, we've been notified and are looking into it!"], $imageContentResponse->status());
                }

                $imageContent = $imageContentResponse->body();

                $fileName = Str::slug(substr($prompt, 0, 20)) . '_' . time(); // generating a unique file name based on the prompt

                $path = getS3PathForEnv() . '/imageBattleUploads/' . $fileName;

                Storage::disk('s3')->put($path, $imageContent); // store the image in S3

                /*
                 get the image from S3 and store in Redis and MySQL
                 as we're going to use S3 URLs to display assets as opposed
                 to using Dalle's URL since they expire after a short period of time
                */
                $s3Url = Storage::disk('s3')->url($path);

                event(new ImageBattlesAssetGeneratedEvent($imageUrl));

                $imageBattlesDataForNewDbInseration = [
                    'UserID' => $userId,
                    'asset_id' => $assetId,
                    'image_url' => $s3Url,
                    'prompt' => $prompt,
                    'time_stamp' => $timeStamp,
                    'total_vote_count' => 0,
                    'prompt_count' => 1
                ];

                $imageBattlesDataToUpdate = [
                    'asset_id' => $assetId,
                    'prompt' => $prompt,
                    'time_stamp' => $timeStamp,
                    'image_url' => $s3Url,
                    'prompt_count' => DB::raw('prompt_count + 1')
                ];

                $checkIfUserHasImageBattlesData = $this->__imageBattlesRepository->checkIfUserHasImageBattlesData($userId);

                if($checkIfUserHasImageBattlesData) {
                    $updateUserImageBattlesData = $this->__imageBattlesRepository->updateUserImageBattlesData($userId, $imageBattlesDataToUpdate);

                    if($updateUserImageBattlesData) {
                        return response()->json(['message' => "Successfully updated your prompt."]);
                    }
                }

                try {
                    $this->__imageBattlesRepository->insertUserImageBattlesData($imageBattlesDataForNewDbInseration);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

                return response()->json($imageBattlesDataForNewDbInseration);

            }

            return response()->json(['message' => 'You can only enter two prompts every 24hrs!'], 400);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
