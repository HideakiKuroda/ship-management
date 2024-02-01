<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ScheduleController;
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
Route::middleware('checkRole:developer')->get('users/rolePermission', [UserController::class, 'getRolesWithPermissions']);

// ships関連のルート設定
Route::middleware(['auth', 'verified'])
->group(function () {
    Route::resource('ships', ShipController::class);
    Route::post('/ships/{id}/upload/', [ShipController::class,'upload'])->name('ship.upload');
    Route::get('/ships/{id}/downloadFile/', [ShipController::class,'downloadFile'])->name('ship.downloadFile');
    Route::get('/ships/{id}/getFileName/', [ShipController::class,'getFileName'])->name('ship.getFileName');
    Route::delete('/ships/{id}/deleteFile/', [ShipController::class,'deleteFile'])->name('ship.deleteFile');
    Route::put('/ships/{ship}/calculateInspectionDates', [ShipController::class,'calculateInspectionDates'])->name('ship.inspectionDates');

});
Route::get('getship/shipfilter', [ShipController::class,'shipfilter'])->name('ships.shipfilter')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])
->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/users/showBoard', [ UserController::class, 'showBoard'])->name('adminboard')->middleware(['auth', 'verified']);

});


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
Route::post('project', [ProjectController::class,'create'])->name('project.create')->middleware(['auth', 'verified']);


// tasks関連のルート設定
Route::middleware(['auth', 'verified'])
->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/subCreate/', [TaskController::class,'subCreate'])->name('task.subCreate');
    Route::post('/tasks/{id}/upload/', [TaskController::class,'upload'])->name('task.upload');
    Route::get('/tasks/{id}/downloadFile/', [TaskController::class,'downloadFile'])->name('task.downloadFile');
    Route::get('taskts/{id}/getFileName/', [TaskController::class,'getFileName'])->name('task.getFileName');
    Route::delete('/tasks/{id}/deleteFile/', [TaskController::class,'deleteFile'])->name('task.deleteFile');
});
Route::post('/tasks/indexfilter/', [TaskController::class,'indexfilter'])->name('task.indexfilter')->middleware(['auth', 'verified']);
Route::get('getTask/indexfilter', [TaskController::class,'indexfilter'])->name('task.indexfilter')->middleware(['auth', 'verified']);

//ガントチャートのルート設定
Route::get('/schedules/show',[ScheduleController::class,'show'])->name('schedules.dock')->middleware(['auth', 'verified']);


// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => auth()->user() && auth()->user()->hasRole('developer'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('register', [RegisteredUserController::class, 'create'])->name('user.register')
->middleware('checkRole:developer');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register')
->middleware('checkRole:developer');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth','verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::prefix('admin')->name('admin.')->group(function(){
//     require __DIR__.'/admin.php';
// });

// Route::get('/', function () {
//     Route::inertia('welcome');
//     $user = Auth::loginUsingId(8);
// });
Route::post('/', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
