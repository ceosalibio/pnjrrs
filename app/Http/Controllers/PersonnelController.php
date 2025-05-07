<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\requiredpersonnel;
use App\Models\Personnel;
use App\Models\Serial;
use Illuminate\Support\Facades\Crypt;

class PersonnelController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }

   
//******check if there existing previous data
    public function getPersonnel(Request $request){
        try {
            $data = Personnel::where([
                ['month',$request->month],
                ['unit',$request->unit],
            ])
            ->get();
            $count = count($data);
            if($count){
                $data[0]->details = decrypt($data[0]->details);
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
            $prevData = Personnel::where([
                ['unit',$val->unit],
            ])
            ->orderBy('month','desc')
            ->first();

        // checking the date
            if(isset($prevData->month) && date('Y-m', strtotime($prevData->month)) < date('Y-m', strtotime($val->month))){

                
                $insert = new Personnel;
                $insert->unit = $val->unit;
                $insert->details = $prevData->details;
                $insert->assessment = $this->other->remarksTemplate();
                $insert->declined = json_encode(array());
                $insert->noted = $this->other->notedTemplate();
                $insert->grade = $prevData->grade;
                $insert->afpos = $prevData->afpos;
                $insert->required = $prevData->required;
                $insert->actual = $prevData->actual;
                $insert->result = $prevData->result;
                $insert->month = $val->month;
                $insert->status = '0';
                $insert->updated_by = $val->name;
                $insert->ipaddress = $this->other->ipaddress();
                $insert->save();
                // insert serial
                foreach (decrypt($prevData->details) as $key => $value) {
                    if(isset($value['Name']) && $value['Name'] != ""){
                        $this->insertSerial($value,$val);
                    }
                }
                
            }else{
        // insert details from required personnels
                $data = [];
                $required =requiredpersonnel::select('details')->where('unit',$val->unit)->first();
        
                if (!isset($required) || $required === false) {
                    return 'no'; // or any other value you want to return
                } else {
                    $requiredMp = 0;
                    $details = $this->crypter->decryptCrypto($required->details);
                        foreach (json_decode($details,true) as $key => $value) {
                            // echo json_encode($value);
                            if (isset($value["RequiredMp"]) && is_numeric($value["RequiredMp"])) {
                                // echo (int) $value["RequiredMp"].'ceoo';
                                $requiredMp += (int) $value["RequiredMp"];
                                $mp = (int) $value["RequiredMp"];
                                if((int) $value["RequiredMp"] > 0){
                                    for ($i=1; $i <= $mp ; $i++) { 
                                        if($value["PositionName"]){
                                            $value["no"] = 'D'.$i;
                                            $value["Serial"] = '';
                                            $value["Serivce"] = '';
                                            $value["Name"] = '';
                                            $value["Rank"] = '';
                                            $value["GradeActual"] = '';
                                            $value["GradePoint"] = '';
                                            $value["AfposActual"] = '';
                                            $value["AfposPoint"] = '';
                                            $value["RequiredMp"] = 1;
                                        }
                                        $data[] = $value;
                                    }
                                }
                            }else{
                                if($value["PositionName"]){
                                    $value["no"] = 'S'.$key;
                                    $value["Serial"] = '';
                                    $value["Serivce"] = '';
                                    $value["Name"] = '';
                                    $value["Rank"] = '';
                                    $value["GradeActual"] = '';
                                    $value["GradePoint"] = '';
                                    $value["AfposActual"] = '';
                                    $value["AfposPoint"] = '';
                                    $value["RequiredMp"] = $value["RequiredMp"];
                                }
                                $data[] = $value;
                            }
                            
                        }
                        $insert = new Personnel;
                        $insert->unit = $val->unit;
                        $insert->details = encrypt($data);
                        $insert->assessment = $this->other->remarksTemplate();
                        $insert->declined = json_encode(array());
                        $insert->noted = $this->other->notedTemplate();
                        $insert->grade = 0;
                        $insert->afpos = 0;
                        $insert->required = $requiredMp;
                        $insert->actual = 0;
                        $insert->result = encrypt(json_encode(array()));
                        $insert->month = $val->month;
                        $insert->status = '0';
                        $insert->updated_by = $val->name;
                        $insert->ipaddress = $this->other->ipaddress();
                        $insert->save();
                }
            }
            return $this->getPersonnel($val);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }


    public function updateRow(Request $request){
        try {
            $details = json_decode($this->crypter->decryptCrypto($request->row),true);
            $data = Personnel::select('details','actual','result','required')->where('id',$request->id)->first();
            $required = $data->required; //required mp
            $data = decrypt($data->details);
            // $data = json_decode($data,true);
            
            $count = 0; // Initialize a counter variable
            $grade = 0; // Initialize a counter variable
            $afpos = 0; // Initialize a counter variable
            $result = array();
           
     
                foreach ($data as $key => $value) {
                    if ($value['id'] == $details['id'] && $value['no'] == $details['no']) {
                        $data[$key] = $details;
                   
                        if($value['Serial'] != $details['Serial']){
                            
                            $checkSerial = $this->checkSerial($details, $request, 'single');
                            if ( $checkSerial == '1') {
                                return 'serial exist'; // or throw an exception, or log an error, depending on your requirements
                            }else{
                                $this->insertSerial($details,$request);  //insert serial in serial table
                            }
                        }
                        break;
                    }
                }
                // count actual
                foreach ($data as $key => $value) {
                    if(isset($value['Name']) && $value['Name'] != ""){
                        $count++;
                        if($value['GradePoint']){
                            $grade++;
                        }
                        if($value['AfposPoint']){
                            $afpos++;
                        }
                        $result['psgRating'] = ($count / $required) * 100;
                        $result['gradeRating'] = ($grade / $count) * 100;
                        $result['gradeRating04'] = $result['gradeRating'] * 0.4;
                        $result['afposRating'] = ($afpos / $count) * 100;
                        $result['afposRating06'] = $result['afposRating'] * 0.6;
                        $result['psgRating05'] = isset($result['psgRating']) ? $result['psgRating'] * 0.5 : 0;
                        $result['pqrRatingTotal'] = $result['gradeRating04'] + $result['afposRating06'];
                        $result['pqrRating05'] = $result['pqrRatingTotal'] * 0.5;
                        $result['readiness'] = $result['psgRating05'] + $result['pqrRating05'];
                    }
                }
                
                
                $encrypted_data = encrypt($data);
                
                // update
                Personnel::where('id', $request->id)
                ->update([
                    'details' => $encrypted_data,
                    'actual' => $count,
                    'grade' => $grade,
                    'afpos' => $afpos,
                    'result' => encrypt($result)
                ]);
           
            

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function insertSerial($value,$req){
            $insert = new Serial();
            $insert->unit = $req->unit;
            $insert->ser = encrypt($value["Serial"]);
            $insert->name = encrypt($value);
            $insert->month = $req->month;
            $insert->save();
    }

    public function checkSerial($val,$req,$stats){
        // try {
            
            $data = Serial::select('unit','ser')->where('month',$req->month)->get();
            foreach ($data as  $value) {
                $serial = decrypt($value->ser);
                
                if($serial == $val["Serial"]){
                    if($stats == 'single'){
      
                        return '1';
                    }else{

                        continue;
                    }
                }
                // else{
                //     echo 'ceo';
                //    $this->insertSerial($val,$req);  //insert serial in serial table
                // }
            }
        // } catch (\Exception $e) {
        //     return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        // }
    }

}
