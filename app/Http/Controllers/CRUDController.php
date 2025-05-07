<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CRUDController extends Controller
{
    public function deleteItem(Request $request){
       try {
            $val = $request->data;
            $item = [
                'deleted_at' => $val['time'],
                'updated_by' => $val['name']
            ];
            $data = \DB::table($val['table'])
            ->where($val["column"],$val["date"])
            ->where('unit',$val["unit"])
            ->update($item);
            return response()->json(['message' => 'Success'], 200);
       } catch (\Exception $e) {
        return response()->json(['error' => 'Failed:' . $e->getMessage()],500);
       }
    }
}
