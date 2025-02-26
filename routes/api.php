<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProjectAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Controllers\API\ManagerAPIController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CadViewerAPIController;

Route::post('/city', [Controller::class, 'city'])->name('city');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:api')->group(function () {
    Route::get('getAllProjects', [ProjectAPIController::class, 'getAllProjects']);
    Route::get('getProjectDetails', [ProjectAPIController::class, 'getProjectDetails']);
    Route::post('getAllTask', [TaskAPIController::class, 'getAllTask']);
    Route::get('getAllUser', [ManagerAPIController::class, 'getAllUser']);
    Route::get('getTaskByFilters', [TaskAPIController::class, 'getTaskByFilters'])
	->name('getTaskByFilters');
	Route::get('/cadeditor-app', [CadViewerAPIController::class, 'indexApp'])->name("cadeditorApp");
    Route::post('/updateAll', [TaskAPIController::class, 'updateAll'])->name('.updateAll');
    Route::post('/updateById', [TaskAPIController::class, 'updateById'])->name('updateById');	

});
Route::get('/cadeditor', [CadViewerAPIController::class, 'index'])->name("cadeditor");
Route::post('register', [RegisterController::class, 'registered']);
Route::post('login', [RegisterController::class, 'loginUser']);