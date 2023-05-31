<?php

use App\Http\Controllers\API\LookbookController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->controller(AuthController::class)->group(function () {
    Route::post('cms/register', 'register');
    Route::get('cms/verify-mail/{email}', 'verifyMail');
    Route::post('cms/login', 'login');
});



Route::group(['middleware' => ['auth:sanctum']], function () {
   


    Route::get('/cms/lookbook', [LookbookController::class, 'getAllData']);
    Route::post('/cms/lookbook/create', [LookbookController::class, 'createData']);
    Route::get('/cms/lookbook/getByUser/', [LookbookController::class , 'getDataByUser']);
});
//auth
