<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function uploadUnits(Request $request){
        // $data = json_decode($request->data, true);
        try {
            foreach ($request->data as $key =>  $value) {
             
                    if($request->unit == 'unitrelations'){
                        $val = [
                            'category' => $value["category"],
                            'department' => $value["department"],
                            'section' => $value["section"],
                            'team' => $value["team"],
                            'code_rel' => $value["code_rel"]
                        ];
                    }else if($request->unit == 'unitdepartments'){
                        
                        $val = [
                            'code' => $value["code"],
                            'abr' => $value["abr"],
                            'name' => $value["name"],
                            'address' => $value["address"]
                        ];
                    }else{
                        $val = [
                            'code' => $value["code"],
                            'abr' => $value["abr"],
                            'name' => $value["name"]
                        ];
                    }
                    // if($request->unit != '')
                    // $val = [
                    //     'code' => $value
                    // ];
                    $data = \DB::table($request->unit)->insert($val);
                    if($key == count($request->data) -1){
                        return 'success';
                    }
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed:' . $e->getMessage()],500);
        }
      
        
    }
}
