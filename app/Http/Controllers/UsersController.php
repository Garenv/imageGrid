<?php

namespace App\Http\Controllers;

use App\Dal\Interfaces\IUsersRepository;
use App\Dal\Interfaces\IWinnersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * @var IUsersRepository
     */
    protected $__usersRepository;

    /**
     * @var IWinnersRepository
     */
    protected $__winnersRepository;

    public function __construct(IUsersRepository $usersRepository, IWinnersRepository $winnersRepository)
    {
        $this->__usersRepository = $usersRepository;
        $this->__winnersRepository = $winnersRepository;
    }

    public function handleLike(Request $request)
    {
        try {
            $loggedInUserId                          = Auth::user()['UserID'];
            $likedUserId                             = $request->get('UserID');
            $likedPhotoId                            = $request->get('likedPhotoId');
            $createUpdateUserLikesData               = $this->__usersRepository->createUpdateUserLikesData($loggedInUserId, $likedPhotoId);

            $this->__usersRepository->handleLike($likedUserId);
            $getUserLikes                            = $this->__usersRepository->getUserLikes($likedUserId);
            $userLikes                               = $getUserLikes[0]->likes;

            return [
                'UserID'                             => $likedUserId,
                'loggedInUserId'                     => $loggedInUserId,
                'likedPhotoId'                       => $likedPhotoId,
                'userLikes'                          => $userLikes,
                'createUpdateUserLikesData'          => $createUpdateUserLikesData,
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            if($e->getCode()) {
                return response()->json(['message' => 'Something went wrong!'], 500);
            }
        }

    }

    public function handleDislike(Request $request)
    {
        try {
            $loggedInUserId                          = Auth::user()['UserID'];
            $likedUserId                             = $request->get('UserID');
            $dislikedPhotoId                         = $request->get('dislikedPhotoId');
            $updateDisklikesData                     = $this->__usersRepository->updateDisklikesData($loggedInUserId, $dislikedPhotoId);

            $this->__usersRepository->handleDislike($likedUserId);
            $getUserLikes                            = $this->__usersRepository->getUserLikes($likedUserId);
            $userLikes                               = $getUserLikes[0]->likes;

            return [
                'UserID'                             => $likedUserId,
                'loggedInUserId'                     => $loggedInUserId,
                'dislikedPhotoId'                    => $dislikedPhotoId,
                'userLikes'                          => $userLikes,
                'updateDisklikesData'                => $updateDisklikesData,
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            if($e->getCode()) {
                return response()->json(['message' => 'Something went wrong!'], 500);
            }
        }

    }

    public function deleteUserUpload(Request $request)
    {
        try {
            $userId              = $request->get('UserID');
            $deleteUserUpload    = $this->__usersRepository->deleteUserUpload($userId);

            if(!$deleteUserUpload) {
                return response()->json(["status" => "Failed to delete!", 'UserID' => $userId]);
            }

            return response()->json(["message" => "You've deleted your photo", 'UserID' => $userId]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function getUserUploadsForThisWeek(Request $request)
    {

        try {
            $loggedInUserId = Auth::user()['UserID'];
            $sortByLikes = $request->query('sortByLikes') ?? null;
            $getUserUploadsForThisWeek = $this->__usersRepository->getUserUploadsForThisWeek($loggedInUserId, $sortByLikes);

            $sortOrderMessage = "";

            if($getUserUploadsForThisWeek->isEmpty()) {
                return response()->json([
                    'message' => "There are no uploads available.",
                    'gridData' => []
                ]);
            }

            $sorted = false;

            if($sortByLikes === "desc") {
                $sortOrderMessage = "Successfully sorted from High to low!";
                $sorted = true;
            } else if($sortByLikes === "asc") {
                $sortOrderMessage = "Successfully sorted from Low to high!";
                $sorted = true;
            }

            return response()->json([
                'message' => $sortOrderMessage,
                'sorted' => $sorted,
                'gridData' => $getUserUploadsForThisWeek
            ]);

        } catch(\Exception $e) {
            Log::error($e->getMessage());

            if($e->getCode()) {
                return response()->json(['message' => "Something's wrong with grid!  We've been notified and looking into it!"], 500);
            }
        }

    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => [
                'required',
                'min:8', // password must be at least 8 characters
                'regex:/^.*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_=+{};:,<.>]).*$/', // password must be alphanumeric and contain a special character
            ],
        ]);

        if($validator->fails()) {
            $failedRules = $validator->failed();

            if(isset($failedRules['newPassword']['Min'])) {
                return response()->json(['status' => 'failed', 'message' => 'Password must contain 8 characters!'], 400);
            }

            if(isset($failedRules['newPassword']['Regex'])) {
                return response()->json(['status' => 'failed', 'message' => 'Password must be alphanumeric and contain a special character!'], 400);
            }
        }

        $user = Auth::user();

        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function updateEmail(Request $request)
    {
        try {
            $request->validate([
                'updateEmail'=>'required|email|string|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e ) {
            Log::error($e->getMessage());
        }

        $user = Auth::user();

        if(!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }

        $user->email = $request->updateEmail;
        $user->save();

        // refresh the authenticated user data
        Auth::setUser($user);

        return response()->json(['message' => 'Email updated successfully'], 200);
    }

    public function updateName(Request $request)
    {

        try {
            $request->validate([
                'updateName'=>'required|string|max:20'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e ) {
            Log::error($e->getMessage());
        }

        $user = Auth::user();

        if(!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }

        // Check the name for profanity using PurgoMalum API.
        $response = Http::get(config('app.purgo_malum_profanity_filter'), [
            'text' => $request->get('updateName')
        ]);

        if ($response->body() === 'true') {
            return response()->json(['message' => 'Name cannot contain profanity'], 422);
        }

        $user->name = $request->updateName;
        $user->save();

        // refresh the authenticated user data
        Auth::setUser($user);

        return response()->json(['message' => 'Name updated successfully'], 200);
    }

    public function getUsersPastUploads() {
        $loggedInUserId = Auth::user()['UserID'];
        return $this->__usersRepository->getUsersPastUploads($loggedInUserId);
    }

    public function getProfileData() {
        return Auth::user();
    }

    public function hardDeleteProfile()
    {
        return $this->performHardDeletion(function () {
            return $this->__usersRepository->hardDeleteProfile();
        });
    }

    public function deleteUser($email)
    {
        return $this->performHardDeletion(function () use (&$email) {
            return $this->__usersRepository->deleteUser($email);
        });
    }

    public function deleteAllUsers()
    {
        return $this->performHardDeletion(function () {
            return $this->__usersRepository->deleteAllUsers();
        });
    }

    private function performHardDeletion(callable $repositoryMethod)
    {
        try {
            $hardDeletion = $repositoryMethod();

            if ($hardDeletion) {
                return response()->json(['message' => "Successfully deleted."]);
            }

            return response()->json(['message' => 'Deletion failed.'], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    public function getUserDataForChatBox()
    {
        return $this->__usersRepository->getUserDataForChatBox();
    }
}
