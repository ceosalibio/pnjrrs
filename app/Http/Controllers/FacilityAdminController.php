<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\requiredfacilities;
use Illuminate\Support\Facades\Crypt;

class FacilityAdminController extends Controller
{
    public $crypter;
    public function __construct()
    {
        $this->crypter = new Crypter;
    }

    public function facilityDetails(Request $request){
        try {
            $details = json_decode($this->crypter->decryptCrypto($request->details),true); 
            $obj = new requiredfacilities();
            $obj->unit = $request->unit;
            $obj->details = encrypt($details);
            $obj->year = '2019'; //subject to change (TE year)
            $obj->updated_by = $request->updated_by;
            $obj->save();
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    public function requiredFacilities(Request $request){
        try {
            $data = requiredfacilities::where('unit',$request->unit)->first();
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
            requiredfacilities::where('id',$request->id)->update(
                [
                    'details' => encrypt($request->details)
                ]
            );
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
}
