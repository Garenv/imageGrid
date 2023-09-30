<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/check-session', [HomeController::class, 'checkSession']);


Route::get('/home', [HomeController::class, 'index'])->name('home');



// At the end of your web.php
//Route::get('/{any}', function () {
//    return view('welcome');  // assuming 'welcome' is where your React app is initialized
//})->where('any', '.*');
