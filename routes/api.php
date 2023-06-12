<?php

    use App\Http\Controllers\Api\AuthController;
    use App\Http\Controllers\Api\PlantController;
    use App\Http\Controllers\ApplicationController;
    use App\Http\Controllers\CompanyController;
    use App\Http\Controllers\RoleController;
    use App\Http\Controllers\SensorController;
    use App\Http\Controllers\UserController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

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

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/sensors/store-data', [SensorController::class, 'storeDates']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/{id}/get-users', [CompanyController::class, 'getUsers']);
        Route::get('/companies/{id}/show', [CompanyController::class, 'show']);
        Route::get('/companies/{id}/show-plants', [CompanyController::class, 'showPlants']);
        Route::post('/companies/store', [CompanyController::class, 'store']);
        Route::put('/companies/{id}/update', [CompanyController::class, 'update']);
        Route::delete('/companies/destroy/{id}', [CompanyController::class, 'destroy']);

        Route::get('/plants', [PlantController::class, 'index']);
        Route::get('/plants/{id}/edit', [PlantController::class, 'edit']);
        Route::post('/plants/update', [PlantController::class, 'update']);
        Route::post('/plants/store', [PlantController::class, 'store']);
        Route::delete('/plants/destroy/{id}', [PlantController::class, 'destroy']);

        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles/store', [RoleController::class, 'store']);
        Route::get('/roles/{id}/show', [RoleController::class, 'getRole']);
        Route::put('/roles/{id}/update', [RoleController::class, 'update']);
        Route::delete('/roles/destroy/{id}', [RoleController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}/show', [UserController::class, 'getUser']);
        Route::put('/users/{id}/update', [UserController::class, 'update']);
        Route::get('/users/get-users', [UserController::class, 'getUsersExceptCompany']);
        Route::post('/users/{userId}/company/{companyId}/add', [UserController::class, 'addToCompany']);
        Route::delete('/users/destroy/{id}', [UserController::class, 'destroy']);
        Route::put('/users/{userId}/company/remove', [UserController::class, 'removeFromCompany']);

        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::get('/applications/{id}/show', [ApplicationController::class, 'show']);
        Route::post('/applications/store', [ApplicationController::class, 'store']);

        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });


