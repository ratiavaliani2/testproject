<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;


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

/* Start Register */
    Route::post('/users/register', [UserController::class, 'register']);
/* End Register */

/* Start Login */
    Route::post('/login', [AuthController::class, 'login']);
/* End Login */

/* Start Transfer Callback */
    Route::post('/paysera/transfer/callback', [AuthController::class, 'login']);
/* End Transfer Callback */

Route::middleware(['jwt.auth'])->group(function () {

    /* Start Cart */

        Route::post('/cart',  [CartController::class, 'items']);
        Route::post('/cart/create', [CartController::class, 'create']);
        Route::post('/cart/remove', [CartController::class, 'remove']);
        Route::post('/cart/add_amount',  [CartController::class, 'add_amount']);
        Route::post('/cart/reduce_amount', [CartController::class, 'reduce_amount']);

        Route::post('/cart/order',  [CartController::class, 'order']);

    /* End Cart */

    /* Start Checkout */

        Route::post('/order/checkout',  [OrderController::class, 'checkout']);

    /* End checkout*/


    /* Start Update */

        Route::post('/user/update/{id}',  [UserController::class, 'update']);

    /* End Update */

    /* Start Logout */

        Route::post('/user/logout',  [AuthController::class, 'logout']);

    /* End Logout */
});
