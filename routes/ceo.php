<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MiscController;

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/uploadUnits',[MiscController::class,'uploadUnits']);
});

