<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\requiredequipment;
use Illuminate\Support\Facades\Crypt;
class EquipmentAdminController extends Controller
{
    public $crypter;
    public function __construct()
    {
        $this->crypter = new Crypter;
    }

    public function equipmentDetails(Request $request){
        try {
            $details = json_decode($this->crypter->decryptCrypto($request->details),true); 
            // $master = $this->crypter->decryptCrypto($request->master); 
            $obj = new requiredequipment();
            $obj->unit = $request->unit;
            $obj->details = encrypt($details);
            // $obj->master = encrypt(json_encode($master));
            $obj->required1 = $request->required1; 
            $obj->required2 = $request->required2;
            $obj->year = '2019'; //subject to change (TE year)
            $obj->updated_by = $request->updated_by;
            $obj->save();
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function requiredEquipment(Request $request){
        try {
            $data = requiredequipment::where('unit',$request->unit)->first();
                if (isset($data->details)) {
                    $data->details = decrypt($data->details);
                }
            return base64_encode(json_encode($data));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    // deleting position in json details
    public function deleteItem(Request $request){
        try {
            // return $details = encrypt($request->details);
            requiredequipment::where('id',$request->id)->update(
                [
                    'details' => encrypt($request->details),
                    'required1' => $request->required1,
                    'required2' => $request->required2
                ]
            );
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
}
