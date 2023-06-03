<?php

    use App\Http\Controllers\Api\AuthController;
    use App\Http\Controllers\CompanyController;
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

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::post('/companies/store', [CompanyController::class, 'store']);
        Route::put('/companies/update/{id}', [CompanyController::class, 'update']);
        Route::delete('/companies/destroy/{id}', [CompanyController::class, 'destroy']);

        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::delete('/user/destroy/{id}', [UserController::class, 'destroyUser']);
    });


