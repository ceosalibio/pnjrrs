<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\EquipmentController;


    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/getEquipment',[EquipmentController::class,'getEquipment']);
        Route::post('/updateEquip',[EquipmentController::class,'updateEquip']);
    });
    
    
?>