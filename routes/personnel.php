<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonnelController;

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/getPersonnel',[PersonnelController::class,'getPersonnel']);
    Route::post('/updateRow',[PersonnelController::class,'updateRow']);
    Route::post('/checkSerial',[PersonnelController::class,'checkSerial']);
});




?>