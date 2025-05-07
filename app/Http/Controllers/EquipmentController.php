<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Equipments;
use App\Models\Equipmentitem;
use App\Models\requiredequipment;

class EquipmentController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }

    //******check if there existing previous data
    public function getEquipment(Request $request){
        try {
            $data = Equipments::where([
                ['month',$request->month],
                ['unit',$request->unit],
            ])
            ->get();
            $count = count($data);
            if($count){
                $data[0]->details = decrypt($data[0]->details);
                $data[0]->master = decrypt($data[0]->master);
                $data[0]->result = decrypt($data[0]->result);
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
            $prevData = Equipments::where([
                ['unit',$val->unit],
            ])
            ->orderBy('month','desc')
            ->first();

        // checking the date
            if(isset($prevData->month) && date('Y-m', strtotime($prevData->month)) < date('Y-m', strtotime($val->month))){

                $this->copyPrevEquipmentInventory($val);
                $insert = new Equipments;
                $insert->unit = $val->unit;
                $insert->details = $prevData->details;
                $insert->master = $prevData->master;
                $insert->assessment = $this->other->remarksTemplate();
                $insert->declined = json_encode(array());
                $insert->noted = $this->other->notedTemplate();
                $insert->required1 = $prevData->required1;
                $insert->actual1 = $prevData->actual1;
                $insert->required2 = $prevData->required2;
                $insert->actual2 = $prevData->actual2;
                $insert->result = $prevData->result;
                $insert->month = $val->month;
                $insert->status = '0';
                $insert->updated_by = $val->name;
                $insert->ipaddress = $this->other->ipaddress();
                $insert->save();

                

                
            }else{
        // insert details from required personnels
                $required =requiredequipment::where('unit',$val->unit)->first();
        
                if (!isset($required) || $required === false) {
                    return 'no'; // or any other value you want to return
                } else {

                        $insert = new Equipments;
                        $insert->unit = $val->unit;
                        $insert->details = $required->details;
                        $insert->assessment = $this->other->remarksTemplate();
                        $insert->declined = json_encode(array());
                        $insert->noted = $this->other->notedTemplate();
                        $insert->required1 = $required->required1;
                        $insert->actual1 = 0;
                        $insert->required2 = $required->required2;
                        $insert->actual2 = 0;
                        $insert->master = encrypt(array());
                        $insert->result = encrypt(array());
                        $insert->month = $val->month;
                        $insert->status = '0';
                        $insert->updated_by = $val->name;
                        $insert->ipaddress = $this->other->ipaddress();
                        $insert->save();
                }
            }
            return $this->getEquipment($val);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }


    public function updateEquip(Request $request){
        try {
            // return $this->crypter->decryptCrypto($request->details);
            $master = $this->masterTable($request);
            $result = $this->resultData($master);
            $details = encrypt(json_decode($this->crypter->decryptCrypto($request->details),true));
            $val = [
                'details' => $details,
                'master' => encrypt($master),
                'result' => encrypt($result)
            ];
            $update = Equipments::where('id',$request->id)->update($val);
            $this->equipmentInventory($request); // insertion/update of every items in equipment
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function masterTable($request){
        
       $details = json_decode($this->crypter->decryptCrypto($request->details),true);
        $key = array(
            'MOBILITY',
            'Ground',
            'Sea',
            'Air',
            'FIREPOWER',
            'Individual',
            'Crew Served Weapon',
            'COMMUNICATIONS',
            'Total1',
            'MEE',
            'MEDICAL',
            'DENTAL',
            'QUARTERMASTER',
            'Total2',
            'SEE'
        );
        $result = array();
        foreach ($key as $val) {
            $matchedObject = null;
            foreach ($details as $current) {
                if ($current['sub'] === $val || $current['category'] === $val) {
                    $matchedObject = $current;
                    break;
                }
            }
            if ($matchedObject) {
                $category = $matchedObject['category']?? $val;
                $sub = $matchedObject['sub']?? $val;
    
                $required = 0;
                $actual = 0;
                foreach ($details as $current) {
                

                    if (($current['sub'] === $val || $current['category'] === $val) && empty($current['Title'])) {
                        $required += (int) $current['required'];
                        $actual += (int) ($current['actual'] ?? 0);
                    }
                }
    
                $percent = $actual > $required? 100 : ($actual!== 0? ($actual / $required) * 100 : 0);
    
                $result[] = array(
                    'category' => $category,
                    'sub' => $sub,
                    'required' => $required,
                    'actual' => $actual,
                    'percent' => $percent,
                    'title' => in_array($val, array('MOBILITY', 'FIREPOWER'))? 1 : 0,
                    'info' => in_array($category, array('MOBILITY', 'FIREPOWER', 'COMMUNICATIONS'))? '1' : '2',
                    // 'break' => in_array($val, array('COMMUNICATIONS', 'QUARTERMASTER'))? 1 : 0
                );
            } else {
                $required1 = 0;
                $required2 = 0;
                $actual1 = 0;
                $actual2 = 0;
                foreach ($details as $current) {
                    if (empty($current['na'])) {
                        if($current['Info'] === 'primary'){
                            $required1 += (int) $current['required'];
                            $actual1 += (int) ($current['actual'] ?? 0);
                        }else{
                            $required2 += (int) $current['required'];
                            $actual2 += (int) ($current['actual'] ?? 0);
                        }
                    }
                }
                    $result[] = array(
                        'category' => in_array($val, array('Ground', 'Sea', 'Air', 'Individual'))? 'MOBILITY' : 
                                    (in_array($val, array('Crew Served Weapon'))? 'FIREPOWER' : 
                                    (in_array($val, array('Total1', 'MEE','Total2','SEE'))?'':$val)),
                        'sub' => $val,
                        'required' => $val == 'Total1'?$required1:($val == 'Total2'?$required2:''),
                        'actual' => $val == 'Total1'?$actual1:($val == 'Total2'?$actual2:''),
                        'percent' => 0,
                        'title' => in_array($val, array('MOBILITY', 'FIREPOWER'))? 1 : 0,
                        'info' => in_array($val, array('Ground', 'Sea', 'Air', 'Individual', 'Crew Served Weapon', 'COMMUNICATIONS'))? '1' : '2',
                        'break' => in_array($val, array('Total1', 'MEE','Total2','SEE'))? 1 : 0
                    );
                
            }
        }
        $this->resultData($result);
        return $result;
    }

    public function resultData($data){
        // return $data;
        $percent1 = 0;
        $count1 = 0;
        $percent2 = 0;
        $count2 = 0;
        $val = [];
        foreach ($data as $key => $value) {
            if($value["info"] == '1' && $value["title"] == 0 && $value["percent"] != 0){
                $percent1 += $value["percent"];
                $count1 ++;
                
            }
            if($value["info"] == '2' && $value["title"] == 0 && $value["percent"] != 0){
                $percent2 += $value["percent"];
                $count2 ++;
            }
        }
        $val["avePrimary"] = $count1?$percent1/$count1:0;
        $val["mee"] = $val["avePrimary"]?$val["avePrimary"] * 0.8:0;
        $val["aveSecondary"] = $count2?$percent2/$count2:0;
        $val["see"] = $val["aveSecondary"]?$val["aveSecondary"] * 0.2:0;
        $val["readiness"] = $val["mee"] + $val["see"];

        return $val;
        
    }

    public function equipmentInventory($req){
        $data = Equipmentitem::select('code')->where([
            ['unit',$req->unit],
            ['code',$req->item["id"]],
            ['month',$req->month],
        ])->count();

        
        if(!$data){
            $insert = new Equipmentitem;
            $insert->unit = $req->unit;
            $insert->code = $req->item["id"];
            $insert->cat = encrypt($req->item["category"]);
            $insert->sub = encrypt($req->item["sub"]);
            $insert->des = encrypt($req->item["description"]);
            $insert->req = encrypt($req->item["required"]);
            $insert->act = encrypt($req->item["actual"]);
            $insert->month = $req->month;
            $insert->save();
        }else{
            $val = [
                'act' => encrypt($req->item["actual"]),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            Equipmentitem::where([
                ['unit',$req->unit],
                ['code',$req->item["id"]],
                ['month',$req->month],
            ])
            ->update($val);
        }
    }
    // insert the previous month equipment inventory
    public function copyPrevEquipmentInventory($req){

        $previousMonth = date('Y-m', strtotime($req->month . ' -1 month'));
        $data = Equipmentitem::where([
            ['unit',$req->unit],
            ['month',$previousMonth],
        ])->get();

            foreach($data as $key => $item){
                
                $insert = new Equipmentitem;
                $insert->unit = $req->unit;
                $insert->code = $item->code;
                $insert->cat = $item->cat;
                $insert->sub = $item->sub;
                $insert->des = $item->des;
                $insert->req = $item->req;
                $insert->act = $item->act;
                $insert->month = $req->month;
                $insert->save();
            }
            
        
    }
}
