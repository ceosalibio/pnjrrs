<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public $other;
    public function __construct()
    {
        $this->other = new DetailsController;
    }
// 
    public function submitReport(Request $request){
        try {
            $updateData = [
                'assessment' => $request->assessment,
                'noted' => $request->noted,
                'status' => $request->rest == '10'?'4':($request->hpn != '1'?'3':'1'),
                'ipaddress' =>  $this->other->ipaddress(),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if($request->tab == '6' && $request->table == 'reports'){
                $updateData['recommend'] = $request->recommend;
            }

            \DB::table("$request->table")->where('id',$request->id)
            ->update($updateData);
            
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function delinedReport(Request $request){
        try {
            \DB::table("$request->table")->where('id',$request->id)
            ->update([
                'declined' => $request->data,
                'status' => '0',
                'ipaddress' =>  $this->other->ipaddress(),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
    
// 
    public function approvedReport(Request $request){
        try {
            \DB::table("$request->table")->where('id',$request->id)
            ->update([
                'noted' => $request->noted,
                'status' => $request->status,
                'ipaddress' =>  $this->other->ipaddress(),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }


}
