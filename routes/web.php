<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\OrgRelationshipController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectOrgController;
use App\Http\Controllers\ProjectPersonController;
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

Route::middleware(['auth'])->group(function () {
   
    Route::get('/', [PersonController::class, 'index'])->name('home');
    Route::resource('people', PersonController::class)->except(['index']);
    
    Route::resource('orgs', OrgController::class);
    Route::resource('org_relationships', OrgRelationshipController::class)->except(['index','show']);
    
    Route::resource('positions', PositionController::class)->except(['index','show']);
    Route::get('/people/{person}/confirm-current-position', [PositionController::class, 'confirm_current'])->name('positions.confirm_current');
    Route::put('/people/{person}/confirm-current-position', [PositionController::class, 'update_current'])->name('positions.update_current');
    
    Route::resource('projects', ProjectController::class);
    
    Route::get('projects/{project}/people/create', [ProjectPersonController::class, 'create'])->name('projects.people.create');
    Route::post('projects/{project}/people', [ProjectPersonController::class, 'store'])->name('projects.people.store');
    Route::get('projects/{project}/people/{project_person}/edit', [ProjectPersonController::class, 'edit'])->name('projects.people.edit');
    Route::put('projects/{project}/people/{project_person}', [ProjectPersonController::class, 'update'])->name('projects.people.update');
    Route::delete('projects/{project}/people/{project_person}', [ProjectPersonController::class, 'destroy'])->name('projects.people.destroy');
    
    Route::get('projects/{project}/orgs/create', [ProjectOrgController::class, 'create'])->name('projects.orgs.create');
    Route::post('projects/{project}/orgs', [ProjectOrgController::class, 'store'])->name('projects.orgs.store');
    Route::get('projects/{project}/orgs/{project_org}/edit', [ProjectOrgController::class, 'edit'])->name('projects.orgs.edit');
    Route::put('projects/{project}/orgs/{project_org}', [ProjectOrgController::class, 'update'])->name('projects.orgs.update');
    Route::delete('projects/{project}/orgs/{project_org}', [ProjectOrgController::class, 'destroy'])->name('projects.orgs.destroy');
    
    Route::resource('meetings', MeetingController::class);
    
});

require __DIR__.'/auth.php';
