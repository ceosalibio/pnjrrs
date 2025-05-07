<?php
include 'ceo.php';
include 'admin.php';
include 'personnel.php';
include 'training.php';
include 'equipment.php';
include 'maintenance.php';
include 'facility.php';
include 'report.php';
include 'inventory.php';
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\CRUDController;
use App\Http\Controllers\SubmissionController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/users', [UserController::class, 'index']);
// Route::post('/getUser', function(Request $request){
//     // return $request;
//     return User::find($request->id);
// });

Route::post('/getDepartment',[UnitController::class,'getDepartment']);
Route::post('/getLogin',[AuthController::class,'getLogin']);
Route::post('/validateCaptcha',[AuthController::class,'validateCaptcha']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/getCategory',[UnitController::class,'getCategory']);
    Route::post('/getSection',[UnitController::class,'getSection']);
    Route::post('/getTeam',[UnitController::class,'getTeam']);
    Route::post('/unitInformation',[UnitController::class,'unitInformation']);
    Route::get('/getRank',[UnitController::class,'getRank']);
    Route::get('/getAfpos',[UnitController::class,'getAfpos']);
    Route::get('/gradeRequired',[UnitController::class,'gradeRequired']);

    Route::post('/insertUser',[AuthController::class,'register']);

    Route::post('/insertLog',[AuthController::class,'insertLog']);
    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/deleteItem',[CRUDController::class,'deleteItem']);


    Route::post('/submitReport',[SubmissionController::class,'submitReport']);
    Route::post('/delinedReport',[SubmissionController::class,'delinedReport']);
    Route::post('/approvedReport',[SubmissionController::class,'approvedReport']);
});
























