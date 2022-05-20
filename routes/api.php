<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use App\Models\Agency;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Client;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/clients', function (){
    return Client::all();
});

Route::get('/agencies', function (){
    return Agency::all();
});

//Protected routes
Route::group(['middleware'=>['auth:sanctum']],function () {
    // User
    Route::get('/user', [AuthController::class, 'user']);
    Route::patch('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/image', [AuthController::class, 'uploadImage']);
    Route::get('/image', [AuthController::class, 'userimage']);
    Route::delete('/user/image/delete', [AuthController::class, 'deleteImage']);


    // Offer
    Route::apiResource('/offer', OfferController::class);
    Route::get('/get/id', [OfferController::class, 'getOfferId']);
    Route::get('/agencyPhone/{id}', [OfferController::class, 'agencyPhone']);

    //Photos
    Route::apiResource('/photo', PhotoController::class);

    // Like
    Route::post('/offer/{id}/likes', [LikeController::class, 'likeOrUnlike']);
    Route::post('/offer/{id}/unlikes', [LikeController::class, 'Unlike']);

    // List Favorite
    Route::get('/likes', [LikeController::class, 'index']);

    //search
    Route::get('/offer/search/{name}', [OfferController::class, 'search']);

    // Agency offers
    Route::get('/agency', [OfferController::class, 'agencyOffers']);
});

