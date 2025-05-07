<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\MaintenanceController;
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/getMaintenance',[MaintenanceController::class,'getMaintenance']);
        Route::post('/updateMaintenance',[MaintenanceController::class,'updateMaintenance']);
    }); 
?>