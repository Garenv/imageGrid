<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactUsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('/check-session', [HomeController::class, 'checkSession']);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/grid');
    }
    return view('welcome');
});

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
});

Route::get('/contact-us', function () {
    return view('contact-us');
});

Route::get('/faq', function () {
   return view('faq');
});

Route::get('/get-faq', [ContactUsController::class, function() {
    return DB::table('faq')->get();
}]);

Route::post('/submit-contact-form', [ContactUsController::class, 'submitContactUs']);

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/grid',                                       [HomeController::class, 'index'])->middleware(['verified']);


Route::middleware(['auth'])->group(function () {
    Route::get( '/get-user-uploads-data',                 [UsersController::class,      'getUserUploadsData']);
    Route::post('/like',                                  [UsersController::class,      'handleLike']);
    Route::post('/dislike',                               [UsersController::class,      'handleDislike']);
    Route::get('/get-users-past-uploads',                 [UsersController::class,      'getUsersPastUploads']);
    Route::get('/get-this-weeks-winners',                 [UsersController::class,      'getThisWeeksWinners']);
    Route::get('/get-profile-data',                       [UsersController::class,      'getProfileData']);
    Route::get('/get-avatar-image',                       [FileUploadController::class, 'getAvatarImage']);


    Route::get('/{any?}', function () {
        return view('home');
    })->where('any', '.*');

    Route::post('/file-upload',                           [FileUploadController::class, 'fileUpload']);
    Route::delete('/delete-user-upload',                  [UsersController::class,      'deleteUserUpload']);
    Route::post('/support',                               [SupportController::class,    'support']);
    Route::post('/update-password',                       [UsersController::class,      'updatePassword']);
    Route::post('/update-email',                          [UsersController::class,      'updateEmail']);
    Route::post('/update-name',                           [UsersController::class,      'updateName']);
    Route::post('/upload-avatar-image',                   [FileUploadController::class, 'uploadAvatarImage']);

});
//Route::get('/get-user-like',                         [UsersController::class,      'getUserLikes']);
//Route::get('/choose-winners',                        [WinnersController::class,    'getTopThreeWinnersFromUploadsTable']);
//Route::get('/get-winners',                           [WinnersController::class,    'getTopThreeWinnersFromWinnersTable']);
//Route::post('/dislike',                              [UsersController::class,      'handleDislike']);
//Route::get('/get-data-from-userlikes-table',         [UsersController::class,      'getDataFromUserLikesTable']);
//Route::get('/get-user-data',                         [UsersController::class,      'getUserData']);
//Route::post('/update-password',                      [UsersController::class,      'changePassword']);
//Route::post('/update-email',                         [UsersController::class,      'updateEmail']);
//Route::post('/update-name',                          [UsersController::class,      'updateName']);
