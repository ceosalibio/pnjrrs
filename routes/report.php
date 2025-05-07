<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\ReportController;

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/getReport',[ReportController::class,'getReport']);
        Route::post('/reportRestriction',[ReportController::class,'reportRestriction']);
    });
    
?>