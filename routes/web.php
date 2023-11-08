<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;


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

// ships関連のルート設定
Route::middleware(['auth', 'verified'])
->group(function () {
    Route::resource('ships', ShipController::class);
    Route::post('/ships/{id}/upload/', [ShipController::class,'upload'])->name('ship.upload');
    Route::get('/ships/{id}/downloadFile/', [ShipController::class,'downloadFile'])->name('ship.downloadFile');
    Route::get('/ships/{id}/getFileName/', [ShipController::class,'getFileName'])->name('ship.getFileName');
    Route::delete('/ships/{id}/deleteFile/', [ShipController::class,'deleteFile'])->name('ship.deleteFile');
    
});    
Route::get('getship/shipfilter', [ShipController::class,'shipfilter'])->name('ships.shipfilter')->middleware(['auth', 'verified']);

// prpjects関連のルート設定
Route::middleware(['auth', 'verified'])   
->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{id}/upload/', [ProjectController::class,'upload'])->name('project.upload');
    Route::get('/projects/{id}/downloadFile/', [ProjectController::class,'downloadFile'])->name('project.downloadFile');
    Route::get('/projects/{id}/getFileName/', [ProjectController::class,'getFileName'])->name('project.getFileName');
    Route::delete('/projects/{id}/deleteFile/', [ProjectController::class,'deleteFile'])->name('project.deleteFile');

});    
Route::post('/projects/indexfilter/', [ProjectController::class,'indexfilter'])->name('project.indexfilter')->middleware(['auth', 'verified']);
Route::get('getindex/indexfilter', [ProjectController::class,'indexfilter'])->name('project.indexfilter')->middleware(['auth', 'verified']);


// tasks関連のルート設定
Route::resource('tasks', TaskController::class)
    ->middleware(['auth', 'verified']);
        
//ガントチャートのルート設定
Route::middleware(['auth', 'verified'])
->group(function () {
Route::inertia('/ganttChart','Schedules/GanttChart');
Route::inertia('/ganttw','Schedules/GantTaiw');
Route::inertia('/test12','Projects/test');
Route::inertia('/test22','Projects/test2');
});    


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => auth()->user() && auth()->user()->hasRole('admin'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('register', [RegisteredUserController::class, 'create'])->name('user.register')->middleware('checkRole:admin');
Route::post('admin/register', [RegisteredUserController::class, 'store'])->name('register')->middleware('checkRole:admin');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth','admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->group(function(){
    require __DIR__.'/admin.php';
});

// Route::get('/', function () {
//     Route::inertia('welcome');
//     $user = Auth::loginUsingId(8);
// });
Route::post('/', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
