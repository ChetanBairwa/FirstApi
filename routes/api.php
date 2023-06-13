<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


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

    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/loggeduser', [ApiController::class, 'loggeduser']);



});
Route::resource('blog', ApiController::class);
Route::get('/user', [ApiController::class, 'adda']);
Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
Route::get('/list/{id?}', [ApiController::class, 'list']);
Route::post('/add', [ApiController::class, 'add']);
Route::put('/update', [ApiController::class, 'update']);
Route::delete('/delete/{id}', [ApiController::class, 'delete']);
Route::get('/search/{name}', [ApiController::class, 'search']);
Route::post('/save', [ApiController::class, 'testdata']);


