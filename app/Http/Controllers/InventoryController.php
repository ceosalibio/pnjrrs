<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Personnel;
use App\Models\Equipmentitem;
use App\Models\Unitrelation;

class InventoryController extends Controller

{
    public $manpower = 0;

    // public function __construct()
    // {
    //     $this->manpower = 0;
    // }
    public function getInventory(Request $request){
        try {
            $units = $this->getUnit($request);
            $manpower = 0;
            $data = [];
            foreach ($units as $val) {
                $manpower = $this->getManpower($val,$request);
                $records = $this->getRecord($val,$request);
                if(count($records)){
                    foreach ($records as $key => $rec) {
                        $data[] = $rec;
                    }
                   
                }
            }
           
            // Convert the array to a Laravel collection
            $data = collect($data);
            $data->each(function ($val) {
                $val->cat = decrypt($val->cat);
                $val->sub = decrypt($val->sub);
                $val->des = decrypt($val->des);
                $val->req = (int)decrypt($val->req);
                $val->act = (int)decrypt($val->act);
            });
            // 
            $result = [];
            foreach ($data as $key => $value) {
                if($this->categoryName($request) != 'ALL'){
                    if($value->cat == $this->categoryName($request)){
                        $result[] = $value;
                    }
                }else{
                    $result[] = $value;
                }
               
            }
           

            // Define the category and subcategory order
            $categoryOrder = ['MOBILITY', 'WEAPON', 'COMMUNICATION', 'MEDICAL', 'DENTAL', 'QUARTERMASTER'];
            $subcategoryOrder = [
                'MOBILITY' => ['GROUND', 'SEA', 'AIR'],
                'WEAPON' => ['PRIMARY', 'SERVICE CREW']
            ];

            // Sort the result array based on the defined category and subcategory order
            usort($result, function($a, $b) use ($categoryOrder, $subcategoryOrder) {
                $catOrderA = array_search(strtoupper($a->cat), $categoryOrder);
                $catOrderB = array_search(strtoupper($b->cat), $categoryOrder);

                // Sort by category first
                if ($catOrderA !== $catOrderB) {
                    return $catOrderA - $catOrderB;
                }

                // If categories are the same, sort by subcategory
                if (isset($subcategoryOrder[strtoupper($a->cat)])) {
                    $subOrderA = array_search(strtoupper($a->sub), $subcategoryOrder[strtoupper($a->cat)]);
                    $subOrderB = array_search(strtoupper($b->sub), $subcategoryOrder[strtoupper($b->cat)]);

                    return $subOrderA - $subOrderB;
                }

                return 0;
            });
            // getting the total in the same category
            $table = [];
          
            foreach ($result as $value) {
           
                $i = -1;
                
                foreach ($table as $key => $val){
                    if($this->categoryName($request) != 'ALL'){
                        if (strtoupper($val->cat) === strtoupper($value->cat) &&
                            strtoupper($val->sub) === strtoupper($value->sub) &&
                            strtoupper($val->des) === strtoupper($value->des)) {
                            $i = $key;
                     
                        }
                    }else{
                        if (strtoupper($val->cat) === strtoupper($value->cat)) {
                            $i = $key;
                          
                        }
                    }
                }
                    if($i === -1){
                        $table[] = $value;
                    }else{
                        $table[$i]->req += $value->req;
                        $table[$i]->act += $value->act;
                    }
            }
            return base64_encode(json_encode(['manpower'=> $manpower, 'table' =>$table]));;
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    // getting number of manpower

    public function getManpower($val,$req){
        try {
       
            $data = Personnel::select('actual')
            ->where('month',$req->month)
            ->where('status', '5');
            if($req->unit != 'all'){
                $data = $data->where('unit',$val->unit)->first();
                if($data && $data->actual){
                    $this->manpower += $data->actual;
                }
            }else{
                $data = $data->get();
                $this->manpower = $data->sum('actual');
            }
            return $this->manpower;

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    // this function is to convert the request to category name  of equipment

    private function categoryName($req){
        switch ($req->tab) {
            case '1':
                return 'MOBILITY';
                break;
            case '2':
                return 'WEAPON';
                break;
            case '3':
                return 'COMUNICATION';
                break;
            case '4':
                return 'MEDICAL';
                break;
            case '5':
                return 'DENTAL';
                break;
            case '6':
                return 'QUARTERMASTER';
                break;
            default:
                return 'ALL';
                break;
        }
    }

    // getting department unit
    // getting all unit
    public function getUnit($val){
        try {
            $data = Unitrelation::select(
                'unitrelations.code_rel as unit',
            )
            ->leftjoin('unitdepartments','unitdepartments.code','unitrelations.department');
            if($val->unit != 'all'){
                $data = $data->where('unitrelations.department',$val->unit)->get();
            }else{
                $data = $data->get();
            }

            return $data;

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
    // if record in equipment items
    private function getRecord($val, $req)
    {
            return Equipmentitem::where([
                ['month',$req->month],
                ['unit',$val->unit]
            ])->get();
    }

}
