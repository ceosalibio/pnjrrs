<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainingController;

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/getTraining',[TrainingController::class,'getTraining']);
    Route::post('/updateTrainingTable',[TrainingController::class,'updateTrainingTable']);
    Route::post('/updateOrac',[TrainingController::class,'updateOrac']);
});








?>