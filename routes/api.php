<?php

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
//Route::resource('Orders', [OrderController::class]);
Route::get('orders', [App\Http\Controllers\API\OrderController::class, 'index'])->name('get.order');
Route::POST('orders', [App\Http\Controllers\API\OrderController::class, 'store'])->name('get.store');
Route::PUT('orders/{id}', [App\Http\Controllers\API\OrderController::class, 'update'])->name('update');
Route::POST('orders/{id}/add', [App\Http\Controllers\API\OrderController::class, 'order_product'])->name('order.product');
Route::POST('orders/{id}/pay', [App\Http\Controllers\API\OrderController::class, 'payment'])->name('order.payment');
Route::DELETE('orders/{id}', [App\Http\Controllers\API\OrderController::class, 'destroy'])->name('destroy');
