<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\FacilityController;

   
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/getFacility',[FacilityController::class,'getFacility']);
        Route::post('/updateFacility',[FacilityController::class,'updateFacility']);
    });
?>