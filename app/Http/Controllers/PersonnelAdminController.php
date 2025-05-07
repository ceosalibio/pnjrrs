<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\requiredpersonnel;
use App\Models\Category;
use App\Models\Unitdepartment;
use App\Models\Unitsection;
use App\Models\Unitteam;
use App\Models\Unitrelation;


class PersonnelAdminController extends Controller
{

    public $crypter;
    public function __construct()
    {
        $this->crypter = new Crypter;
    }
// updloading TO
    public function uploadTO(Request $request){

        try {
            $validatedData = $request->validate([
                'unit' => 'required|string',
                'updated_by' => 'required|string',
            ]);

            // $details = $this->crypter->decryptCrypto($request->details);
            $obj = new RequiredPersonnel();
            $obj->unit = $validatedData['unit'];
            $obj->details = $request->details;
            $obj->org = '2019'; //subject to change (TO year)
            $obj->updated_by = $validatedData['updated_by'];
            $obj->save();
    
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// insert required Afpos
    public function insertAfpos(Request $request){
       try {
            $existingAfpos = \DB::table('requiredafpos')->pluck('AfposName')->all();
            $newData = array_filter($request->data, function ($value) use ($existingAfpos) {
                return isset($value["Afpos"]) &&!in_array($value["Afpos"], $existingAfpos);
            });

            foreach ($newData as $value) {
                $val = [
                    'DivisionCode' => $value["DivisionCode"],
                    'AfposName' => strtoupper($value["Afpos"]),
                ];
                \DB::table('requiredafpos')->insert($val);
            }
            
       } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// getting TO table
    public function getTable(Request $request){
        try {
            return  requiredpersonnel::where('unit',$request->unit)->first();
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// deleting position in json details
    public function deletePositon(Request $request){
        try {
            requiredpersonnel::where('unit',$request->unit)->update(['details' => $request->details]);
           
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// manual adding of units
    public function insertManualUnit(Request $request){
        // return $request;
        try {
            if($request->CategoryName){
                $category = [
                    'category_code' => $request->CategoryCode,
                    'category_name' => $request->CategoryName

                ];
                Category::insert($category);
            }
            if($request->DepartmentName){
                $department = [
                    'code' => $request->DepartmentCode,
                    'name' => $request->DepartmentName
                    
                ];
                Unitdepartment::insert($department);
            }
            if($request->SectionName){
                $section = [
                    'code' => $request->SectionCode,
                    'name' => $request->SectionName,
                    'abr' => $request->SectionAbr
                ];
                Unitsection::insert($section);
            }
            if($request->TeamName){
                $team = [
                    'code' => $request->TeamCode,
                    'name' => $request->TeamName,
                    'abr' => $request->TeamAbr
                ];
                Unitteam::insert($team);
            }
            
            $relation = [
                'category' => $request->CategoryCode,
                'department' => $request->DepartmentCode,
                'section' => $request->SectionCode,
                'team' => $request->TeamCode,
                'code_rel' => $request->UnitCode,
                'office' => $request->TeamName?'1':null,
                'org' => '2019'
            ];
            Unitrelation::insert($relation);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
// 

    public function viewAfpos(){
        try {
            return $data = \DB::table('requiredafpos')
            ->orderBy('DivisionCode','asc')
            ->orderBy('updated_at','desc')
            ->get();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
    public function registerAfpos(Request $request){
        try {
            $db = \DB::table('requiredafpos');
            $data = $db->select('AfposName')
            ->where('AfposName',$request->value)
            ->count();

            if(!$data){
                $val = [
                    'DivisionCode' => $request->division,
                    'AfposName' => $request->value
                ];
                $db->insert($val);
                return 'FOS already save!';
            }else{
                return 'FOS already exists!';
            }
        } catch (\Exception $e) {
            return 'FOS not save';
        }
        
    }
}
