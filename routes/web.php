<?php

use App\Http\Controllers\API\LookbookController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//login admin
Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/login', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
});


Route::prefix('v1')->controller(AuthController::class)->group(function () {
    Route::post('cms/register', 'register');
    Route::get('cms/verify-mail/{email}', 'verifyMail');
    Route::post('cms/login', 'login');
});

//lookbook
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/lookbook', function () {
        return view('pages.lookbook');
    });
    

    Route::get('/cms/lookbook', [LookbookController::class, 'getAllData']);
    Route::post('/cms/lookbook/create', [LookbookController::class, 'createData']);
    Route::get('/cms/lookbook/getByUser/', [LookbookController::class , 'getDataByUser']);
});