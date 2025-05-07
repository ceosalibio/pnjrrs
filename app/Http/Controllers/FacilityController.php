<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Facilities;
use App\Models\requiredfacilities;

class FacilityController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }

    //******check if there existing previous data
    public function getFacility(Request $request){
        try {
            $data = Facilities::where([
                ['month',$request->month],
                ['unit',$request->unit],
            ])
            ->get();
            $count = count($data);
            if($count){
                $data[0]->details = decrypt($data[0]->details);
                $data[0]->master = decrypt($data[0]->master);
                $data[0]->result = decrypt($data[0]->result);
                // return $data;
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
            $prevData = Facilities::where([
                ['unit',$val->unit],
            ])
            ->orderBy('month','desc')
            ->first();

        // checking the date
            if(isset($prevData->month) && date('Y-m', strtotime($prevData->month)) < date('Y-m', strtotime($val->month))){

                
                $insert = new Facilities;
                $insert->unit = $val->unit;
                $insert->details = $prevData->details;
                $insert->assessment = $this->other->remarksTemplate();
                $insert->declined = json_encode(array());
                $insert->noted = $this->other->notedTemplate();
                $insert->master = $prevData->master;
                $insert->result = $prevData->result;
                $insert->month = $val->month;
                $insert->status = '0';
                $insert->updated_by = $val->name;
                $insert->ipaddress = $this->other->ipaddress();
                $insert->save();

                
            }else{
        // insert details from requiredfacilities
                $required =requiredfacilities::where('unit',$val->unit)->first();
        
                if (!isset($required) || $required === false) {
                    return 'no'; // or any other value you want to return
                } else {

                        $insert = new Facilities;
                        $insert->unit = $val->unit;
                        $insert->details = $required->details;
                        $insert->assessment = $this->other->remarksTemplate();
                        $insert->declined = json_encode(array());
                        $insert->noted = $this->other->notedTemplate();
                        $insert->master = encrypt(array());
                        $insert->result = encrypt(array());
                        $insert->month = $val->month;
                        $insert->status = '0';
                        $insert->updated_by = $val->name;
                        $insert->ipaddress = $this->other->ipaddress();
                        $insert->save();
                }
            }
            return $this->getFacility($val);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }


    public function updateFacility(Request $request){
        try {
            $details = json_decode($this->crypter->decryptCrypto($request->details),true);
            $master = $request->master?json_decode($this->crypter->decryptCrypto($request->master),true):null;
            $result = $this->result($master);
            $val = [
                'details' => encrypt($details),
                'result' => encrypt($result)
            ];
            if($master){
                $val['master'] = encrypt(($master));
            }
            $update = Facilities::where('id',$request->id)->update($val);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function result($master){
        $readiness = 0;
        if($master){
            $data = $master;
            $facility = ['pa_','sa_','hs_','bp_'];
            foreach ($facility as $value) {
                $readiness += $data[$value.'weight'];
            }
        }
        
       $data = ['readiness' => $readiness];
       return $data;
    }
}
