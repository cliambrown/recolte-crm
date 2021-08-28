<?php

use App\Http\Controllers\OrgController;
use App\Http\Controllers\PersonController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function() {
    
    Route::post('/people/search', [PersonController::class, 'api_search'])->name('people.api_search');
    Route::post('/orgs/search', [OrgController::class, 'api_search'])->name('orgs.api_search');
    
});