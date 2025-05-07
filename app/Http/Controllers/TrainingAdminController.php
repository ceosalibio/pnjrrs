<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\requiredtraining;
use Illuminate\Support\Facades\Crypt;

class TrainingAdminController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }

    public function trainingDetails(Request $request){
        try {
            $details = json_decode($this->crypter->decryptCrypto($request->details),true);
            $activity = json_decode($this->crypter->decryptCrypto($request->activity),true);
            $data = requiredtraining::select('unit')
                ->where([
                        ['unit',$request->unit],
                        ['year',$request->year]
                    ])
                ->count();
            if(empty($data)){
                $insert = new requiredtraining;
                $insert->unit = $request->unit;
                $insert->details = encrypt($details);
                $insert->activity = encrypt($activity);
                $insert->required = $request->required;
                $insert->year = $request->year;
                $insert->updated_by = $request->updated_by;
                $insert->save();
            }
            
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function requiredTraining(Request $request){
        try {
            $data = requiredtraining::where([
                        ['unit',$request->unit],
                        ['year',$request->year]
                    ])
                ->first();
                if (isset($data->activity)) {
                    $data->activity = decrypt($data->activity);
                }
                if (isset($data->details)) {
                    $data->details = decrypt($data->details);
                }
            return base64_encode(json_encode($data));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
}
