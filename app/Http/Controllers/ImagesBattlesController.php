<?php

namespace App\Http\Controllers;

use App\Dal\Repositories\ImageBattlesRepository;
use App\Services\DalleService;
use Illuminate\Http\Request;

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

    public function generateImage(Request $request)
    {
        $prompt = $request->get('prompt');
        $userId = $request->get('UserID');
        $name = $request->get('name');

        return $this->dalleService->generateImage($prompt, $userId, $name);
    }

    public function getUsersImageBattlesData()
    {
        $images_battles_data = [];
        $pattern = 'image_battles_data:*';

        $client = getRedisClient();

        $cursor = 0; // initial cursor value


        do {
            $result = $client->scan($cursor, 'MATCH', $pattern, 'COUNT', 1000);
            $cursor = $result[0]; // update cursor position
            $keys = $result[1]; // retrieved keys

            foreach ($keys as $key) {
                $data = getRedisKey($key);

                if ($data) {
                    $dataObject = json_decode($data);

                    if (isset($dataObject)) {
                        $images_battles_data[] = [
                            "image_url" => $dataObject->image_url,
                            "UserID" => $dataObject->UserID,
                            "name" => $dataObject->name,
                            'prompt' => $dataObject->prompt
                        ];
                    }
                }
            }

        } while($cursor);

        return response()->json(['image_battles_data' => $images_battles_data]);
    }

}
