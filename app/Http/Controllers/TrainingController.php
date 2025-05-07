<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\requiredtraining;
use App\Models\Training;
use App\Models\Personnel;
use Illuminate\Support\Facades\Crypt;

class TrainingController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }

    //******check if there existing previous data
    public function getTraining(Request $request){
        try {
            $data = Training::select('trainings.*', 'personnels.required as personnelRequired', 'personnels.actual as personnelActual')
                ->leftJoin('personnels', 'trainings.unit', '=', 'personnels.unit')
                ->where([
                    ['trainings.month', $request->month],
                    ['trainings.unit', $request->unit],
                ])
            ->get();       
            $count = count($data);
            if($count){
                $data->each(function ($training) {
                    $training->details = decrypt($training->details);
                    $training->activity = decrypt($training->activity);
                    $training->result = decrypt($training->result);
                });
                return base64_encode(json_encode($data));
            }else{
                return $this->getPrevious($request);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

//******get the previous data
    public function getPrevious($val){
        try {
            $prevData = Training::where([
                ['unit',$val->unit],
            ])
            ->orderBy('month','desc')
            ->first();

        // checking the date
            if(isset($prevData->month) && date('Y-m', strtotime($prevData->month)) < date('Y-m', strtotime($val->month))){
                 $detailsData = decrypt($prevData->details);
                 
                 $details = [];
                foreach ($detailsData as $key => $value) {
                    for ($i=1; $i <= 4 ; $i++) { 
                       if($value["q{$i}stats"] == '1'){
                            $value["q{$i}stats"] = '2';
                       }
                    }
                    $details[] = $value;
                }                
                $insert = new Training;
                $insert->unit = $val->unit;
                $insert->details = encrypt($details);
                $insert->activity = $prevData->activity;
                $insert->assessment = $this->other->remarksTemplate();
                $insert->declined = json_encode(array());
                $insert->noted = $this->other->notedTemplate();
                $insert->required = $prevData->required;
                $insert->actual = $prevData->actual;
                $insert->orac = $prevData->orac;
                $insert->result = $prevData->result;
                $insert->month = $val->month;
                $insert->status = '0';
                $insert->updated_by = $val->name;
                $insert->ipaddress = $this->other->ipaddress();
                $insert->save();
            }else{
        // insert details from required training
                $data = [];
                $required =requiredtraining::where('unit',$val->unit)->first();
        
                if (!isset($required) || $required === false) {
                    return 'no'; // or any other value you want to return
                } else {
                    $insert = new Training;
                    $insert->unit = $val->unit;
                    $insert->details = $required->details;
                    $insert->activity = $required->activity;
                    $insert->assessment = $this->other->remarksTemplate();
                    $insert->declined = json_encode(array());
                    $insert->noted = $this->other->notedTemplate();
                    $insert->required = $required->required;
                    $insert->actual = 0;
                    $insert->orac = 0;
                    $insert->result = encrypt(array());
                    $insert->month = $val->month;
                    $insert->status = '0';
                    $insert->updated_by = $val->name;
                    $insert->ipaddress = $this->other->ipaddress();
                    $insert->save();
                }
            }
            return $this->getTraining($val);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function updateTrainingTable(Request $request){
        try {
            $details = encrypt(json_decode($this->crypter->decryptCrypto($request->details),true));
            $val = [
                'details' => $details,
                'actual' => $request->actual,
                'result' => $this->resultData($request)
            ];
            $update = Training::where('id',$request->id)->update($val);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function updateOrac(Request $request){
        try {
            $val = [
                'orac' => $request->orac,
                'reason' => $request->reason,
                'result' => $this->resultData($request)
            ];
            $update = Training::where('id',$request->id)->update($val);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function resultData($request){
        $result = Training::select('result','actual','required','orac')->where('id',$request->id)->first();
        $data = decrypt($result->result);
        if ($request->personnelactual > 0) {
            $data['oracValue'] = 0;
            $data['oracPercentage'] = ($request->orac / $request->personnelactual) * 100;
            $data['oracValue'] = $data['oracPercentage'] * 0.40;
        }else{
            $data['oracValue'] =  $data["oracValue"]?$data["oracValue"]:0;
        }
    
        if ($result->required > 0) {
            $data['metValue'] = 0;
            $data['metPercentage'] = ($request->actual / $result->required) * 100;
            $data['metValue'] = $data['metPercentage'] * 0.60;
        }else{
            $data['metValue'] =  $data["metValue"]?$data["metValue"]:0;
        }
        // if($data['oracValue'] && $data['metValue']){
            $data['readiness'] = $data['oracValue'] + $data['metValue'];
        // }
        
        return  encrypt($data);
    }
}
