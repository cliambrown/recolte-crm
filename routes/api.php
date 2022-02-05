<?php

use App\Http\Controllers\MeetingParticipantController;
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
    
    Route::post('/meeting-participants', [MeetingParticipantController::class, 'api_store'])->name('meeting_participants.api_store');
    Route::post('/meeting-participants/add-org', [MeetingParticipantController::class, 'api_add_org'])->name('meeting_participants.api_add_org');
    Route::post('/meeting-participants/remove-org', [MeetingParticipantController::class, 'api_remove_org'])->name('meeting_participants.api_remove_org');
    
});