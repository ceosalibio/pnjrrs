<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\PersonnelAdminController;
use App\Http\Controllers\TrainingAdminController;
use App\Http\Controllers\EquipmentAdminController;
use App\Http\Controllers\FacilityAdminController;
use App\Http\Controllers\ReportAdminController;


use App\Models\User;

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/viewUser',[UserAdminController::class,'getUser']);
    Route::post('/searchUser',[UserAdminController::class,'searchUser']);
    Route::post('/editUser',[UserAdminController::class,'editUser']);
    Route::post('/updateRights',[UserAdminController::class,'updateRights']);

    // personnel
    Route::post('/uploadTO',[PersonnelAdminController::class,'uploadTO']);
    Route::post('/insertAfpos',[PersonnelAdminController::class,'insertAfpos']);
    Route::post('/getTable',[PersonnelAdminController::class,'getTable']);
    Route::post('/deletePositon',[PersonnelAdminController::class,'deletePositon']);
    Route::post('/insertManualUnit',[PersonnelAdminController::class,'insertManualUnit']);
    Route::post('/registerAfpos',[PersonnelAdminController::class,'registerAfpos']);
    Route::get('/viewAfpos',[PersonnelAdminController::class,'viewAfpos']);
    // training
    Route::post('/trainingDetails',[TrainingAdminController::class,'trainingDetails']);
    Route::post('/requiredTraining',[TrainingAdminController::class,'requiredTraining']);

    // equipment
    Route::post('/equipmentDetails',[EquipmentAdminController::class,'equipmentDetails']);
    Route::post('/requiredEquipment',[EquipmentAdminController::class,'requiredEquipment']);
    Route::post('/deleteEquipment',[EquipmentAdminController::class,'deleteItem']);

    // facility
    Route::post('/facilityDetails',[FacilityAdminController::class,'facilityDetails']);
    Route::post('/requiredFacilities',[FacilityAdminController::class,'requiredFacilities']);
    Route::post('/deleteFacilities',[FacilityAdminController::class,'deleteItem']);

    // check report

    Route::post('/checkReport',[ReportAdminController::class,'checkReport']);
    Route::post('/unlockedReport',[ReportAdminController::class,'unlockedReport']);
    Route::post('/deleteReport',[ReportAdminController::class,'deleteReport']);

    
});






















?>