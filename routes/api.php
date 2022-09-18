<?php

use App\Http\Controllers\ApiController;
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

Route::get('test',[ApiController::class,'test']);

Route::get('main_menu', [ApiController::class, 'get_main_menu']);
Route::get('side_menu/{menu_id}', [ApiController::class, 'get_side_menu']);
Route::get('footer_menu/{menu_id}', [ApiController::class, 'get_footer_menu']);
Route::get('portrait_screen_videos/{screen_id}/{lang}', [ApiController::class, 'get_portrait_screen_videos']);
Route::get('video_wall_screen_videos/{screen_id}/{lang}', [ApiController::class, 'get_video_wall_screen_videos']);
