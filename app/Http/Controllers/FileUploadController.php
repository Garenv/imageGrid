<?php

namespace App\Http\Controllers;

use App\Dal\Interfaces\IUploadsRepository;
use App\Models\LegacyUploads;
use Aws\Exception\AwsException;
use Aws\Rekognition\Exception\RekognitionException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{

    /**
     * @var IUploadsRepository
     */
    protected $__uploadsRepository;

    public function __construct(IUploadsRepository $uploadsRepository)
    {
        $this->__uploadsRepository = $uploadsRepository;
    }

    public function fileUpload(Request $request) {

        try {
            $path                       = config('app.aws_s3_path');
            $file                       = $request->file('image');
            $imgName                    = $file->getClientOriginalName();
            $bucket                     = config('app.aws_bucket');
            $region                     = config('app.aws_default_region');
            $url                        = "https://{$bucket}.s3.{$region}.amazonaws.com{$path}{$imgName}";
            $userId                     = Auth::user()['UserID'];
            $time                       = Carbon::now();
            $timeStamp                  = $time->toDateTimeString();
            $photoId                    = 'p-' . Str::uuid()->toString();
            $checkIfUserHasUploaded     = $this->__uploadsRepository->checkIfUserHasUploaded($userId);
            $isImageUploadedAppropriate = $this->isImageUploadedAppropriate(file_get_contents($file->path()));

            $data = [
                'url'                   => $url,
                'UserID'                => $userId,
                'isUploaded'            => true,
                'timeStamp'             => $timeStamp,
                'likes'                 => 0,
                'photo_id'              => $photoId
            ];

            if ($isImageUploadedAppropriate->getStatusCode() == 400) {
                return $isImageUploadedAppropriate;
            }

            if (count($checkIfUserHasUploaded) === 0) {
                $this->insertSetStoreAsset($file, $path, $imgName, $data);
                return $isImageUploadedAppropriate;
            }

            $existingUploadedTimestamp = $checkIfUserHasUploaded[0]->timeStamp;
            $weekday = date('l', strtotime($existingUploadedTimestamp));
            $currentWeekNumber = date('W');
            $uploadedWeekNumber = date('W', strtotime($existingUploadedTimestamp));

            if ($weekday !== 'Sunday' && ($currentWeekNumber !== $uploadedWeekNumber)) {
                $this->insertSetStoreAsset($file, $path, $imgName, $data);
                return $isImageUploadedAppropriate;
            }


        } catch (RekognitionException $e) {
            Log::error($e->getMessage());

            // grab the previous Guzzle exception since the Rekognition API
            // disallows access to the appropriate status codes
            $previousException = $e->getPrevious();

            if($previousException->getCode() !== 200) {
                return response()->json(['status' => 'failed', 'message' => "Something's wrong with your upload!"], $previousException->getCode());
            }

        }

    }

    public function insertSetStoreAsset($file, $path, $imgName, $data) {
        $file->storeAs(
            $path, // Folder
            $imgName, // Name of image
            's3' // Disk Name
        );

        DB::table('uploads')->insertGetId($data);
        LegacyUploads::create($data);
//        Redis::set("uploadId:$insertUploadDataAndGetUploadId", json_encode($data));
    }

    public function isImageUploadedAppropriate($file) {

        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => config('app.aws_default_region')
        ]);

        $response = $rekognition->detectModerationLabels([
            'Image' => [
                'Bytes' => $file,
            ],
            'MinConfidence' => 50,
        ]);

        foreach ($response['ModerationLabels'] as $label) {
            switch ($label['Name']) {
                case 'Explicit Nudity':
                case 'Nudity':
                case 'Revealing Clothes':
                case 'Violence':
                case 'Hate Symbols':
                case 'Visually Disturbing':
                case 'Drugs':

                if($label['Name'] === "Visually Disturbing") {
                    $message = "You may not upload an image that's {$label['Name']}.";
                } else {
                    $message = "You may not upload an image that contains {$label['Name']}.";
                }

                return Response::json([
                    'message' => $message,
                    'isInappropriate' => true
                ], 400);
            }
        }

        return Response::json([
            'status' => 200,
            'message' => 'Image uploaded!',
            'isInappropriate' => false
        ]);
    }

    public function uploadAvatarImage(Request $request) {
        try {
            $path                       = config('app.aws_s3_path_phopixel');
            $file                       = $request->file('avatar');
            $imgName                    = $file->getClientOriginalName();
            $bucket                     = config('app.aws_bucket_phopixel');
            $avatarImageUrl             = "https://{$bucket}.s3.amazonaws.com{$path}{$imgName}";
            $userId                     = Auth::user()['UserID'];

            $avatarImage = $this->isImageUploadedAppropriate(file_get_contents($file->path()));

            $pathWithImageName = $path.$imgName;

            if($avatarImage->getStatusCode() === 200) {
                Storage::disk('s3_phopixel')->put($pathWithImageName, file_get_contents($file));
                $this->__uploadsRepository->updateUserAvatarImage($userId, $avatarImageUrl);

                return response()->json(['message' => 'Avatar image uploaded!']);
            }

            return response()->json(['message' => "Something's wrong with your upload!"], 400);

        } catch (RekognitionException $e) {
            Log::error($e->getMessage());

            // grab the previous Guzzle exception since the Rekognition API
            // disallows access to the appropriate status codes
            $previousException = $e->getPrevious();

            if($previousException->getCode() !== 200) {
                return response()->json(['status' => 'failed', 'message' => "Something's wrong with your upload!"], $previousException->getCode());
            }
        }
    }

    public function getAvatarImage() {
        $userId = Auth::user()['UserID'];
        return $this->__uploadsRepository->getAvatarImage($userId);
    }
}
