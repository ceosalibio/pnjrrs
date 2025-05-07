<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unitrelation;
use Illuminate\Support\Facades\Crypt;

class ReportAdminController extends Controller
{
    public function checkReport(Request $request){
        try {
            $units = $this->getUnit($request);
            $data = [];
            foreach ($units as $val) {
                $data[] = $this->withRecord($val,$request);
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
        
    }

    // getting department unit
    // getting all unit
    public function getUnit($val){
        try {
            $data = Unitrelation::select(
                'unitrelations.code_rel',
                'unitrelations.hpn',
                'unitsections.code as sectioncode',
                'unitsections.name as section',
                'unitsections.abr as sectionabr',
                'unitteams.code as teamcode',
                'unitteams.name as team',
                'unitteams.abr as teamabr'
            )
            ->leftjoin('unitdepartments','unitdepartments.code','unitrelations.department')
            ->leftjoin('unitsections','unitsections.code','unitrelations.section')
            ->leftjoin('unitteams','unitteams.code','unitrelations.team')
            ->where('unitrelations.department',$val->unit)
            ->get();
            return $data;

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

     // if record in every table
     private function withRecord($val, $req)
     {
    
        $result = [];
        $data = \DB::table($req->table)
                ->whereNull('deleted_at')
                ->where('month',$req->month)
                ->where('unit',$val->code_rel);
                if($req->status == '1'){
                    $data = $data->whereNotNull('status');
                }
                 if($req->status == '2'){
                    $data = $data->where('status','5');
                }
                if($req->status == '3'){
                    $data = $data->where('status','!=','5');
                }
                $data = $data->first();

                // return $data;
        if(!empty($data)){
            $noted =  json_decode($data->noted,true);

            $result = [
                'id' => $data->id,
                'unit' => $val->hpn?$val->team:$val->section,
                'code' => $val->code_rel,
                'month' => $req->month,
                'table' => $req->table,
                'prepared_by' => $val->status != '0'?$noted[0]["prepared_by"]:'',
                'approved_by' => $val->status == '5'?$noted[0]["noted_by"]:'',
                'status' => $data->status == '5'?'1':($data->status != '0'? '2':'3') //1- submitted, 2-Ongoing, 3-Not submitted
            ];
        }else{
            $result = [
                'unit' => $val->hpn?$val->team:$val->section,
                'code' => $val->code_rel,
                'month' => $req->month,
                'table' => $req->table,
                'prepared_by' => '',
                'approved_by' => '',
                'status' => '3' //1- submitted, 2-Ongoing, 3-Not submitted
            ];
        }

        return $result;
     }

     public function unlockedReport(Request $request){
        try {
            \DB::table($request->table)
            ->where('id',$request->id)
            ->update(['status'=>'0']);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
     }

     public function deleteReport(Request $request){
        try {
            \DB::table($request->table)
            ->where('id',$request->id)
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
     }

 
}
