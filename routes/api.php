<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\distributionController;
use App\Http\Controllers\distributionProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::prefix("/distributions")->group(function() {
Route::get("/", [distributionController::class, "index"]);
Route::post("/", [distributionController::class, "store"]); 
Route::delete("/{id}", [distributionController::class, "destroy"] );
});

Route::prefix("/distribution-products")->group(function() {
Route::get("/", [distributionProductController::class, "getTemporaryProduct"]); 
Route::get("/{distributionId}", [distributionProductController::class, "getDistributionDetail"]);
Route::post("/", [distributionProductController::class, "addTemporaryProduct"]);
Route::delete("{id}", [distributionProductController::class, "deleteTemporaryProduct"]); 
});


Route::get("/users/baristas", [UserController::class, 'getBaristas']);

Route::get("/products", [ProductController::class, 'index']);

