<?php

namespace App\Http\Controllers;

use App\Dal\Repositories\ImageBattlesRepository;
use App\Services\DalleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImagesBattlesController extends Controller
{
    /**
     * @var DalleService
     */
    protected $dalleService;

    /**
     * @var ImageBattlesRepository
     */
    protected $__imageBattlesRepository;

    public function __construct(DalleService $dalleService, ImageBattlesRepository $imageBattlesRepository)
    {
        $this->dalleService = $dalleService;
        $this->__imageBattlesRepository = $imageBattlesRepository;
    }

    // api to generate image
    public function generateImage(Request $request)
    {
        $prompt = $request->get('prompt');
        $userId = $request->get('UserID');

        return $this->dalleService->generateImage($prompt, $userId);
    }

    public function getAllUsersImageBattlesData()
    {
        return $this->__imageBattlesRepository->getAllUsersImageBattlesData(getAuthenticatedUser()['UserID']);
    }

    // how to grab all data from Redis... use with caution in production

//    public function getUsersImageBattlesData()
//    {
//
//        $images_battles_data = [];
//        $pattern = 'image_battles_data:*';
//
//        $client = getRedisClient();
//
//        $cursor = 0; // initial cursor value
//
//        do {
//            $result = $client->scan($cursor, 'MATCH', $pattern, 'COUNT', 1000);
//            $cursor = $result[0]; // update cursor position
//            $keys = $result[1]; // retrieved keys
//
//            foreach ($keys as $key) {
//                $data = getAllDataFromRedisHash($key);
//
//                if ($data) {
//                    $images_battles_data[] = [
//                        "image_url" => $data['image_url'],
//                        "UserID" => $data['UserID'],
//                        "name" => $data['name'],
//                        "prompt" => $data['prompt'],
//                        "asset_id" => $data['asset_id']
//                    ];
//                }
//            }
//
//        } while($cursor);
//
//        return response()->json(['image_battles_data' => $images_battles_data]);
//    }

    public function upvote(Request $request)
    {

        try {
            $upvotedUserId = $request->get('UserID');
            $loggedInUserId = getAuthenticatedUser()['UserID'];
            $assetId = $request->get('asset_id');

            $upvote = $this->__imageBattlesRepository->upVote($loggedInUserId, $assetId);

            // defensive code - in a highly unlikely scenario - since the user won't be able to vote twice for the same asset
            // this is here to prevent the user from voting twice for the same asset if they somehow infiltrated the system
            if(!$upvote->wasRecentlyCreated) {
                return response()->json(['message' => 'You cannot vote for same image twice!'], 400);
            }

            $incrementTotalVoteCount = $this->__imageBattlesRepository->incrementTotalVoteCount($upvotedUserId);

            if($incrementTotalVoteCount) {
                return response()->json(['message' => 'Your vote has been casted!']);
            }

            return response()->json(['message' => 'Your vote has been casted!'], 400);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }


    }

}
