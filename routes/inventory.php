<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\InventoryController;
    
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/getInventory',[InventoryController::class,'getInventory']);
    });
    
    
?>