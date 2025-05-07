<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unitrelation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public $unit;
    public function __construct()
    {
        $this->unit = new UnitController();
    }

    public function getUser(Request $request){
        try {
            $user = User::all();
            $result = [];
            foreach ($user as $key => $value) {
                 $code = $this->unit->unitRelation($value->unitcode);
                
                    $value->category = $code['category'];
                    $value->categorycode = $code['categorycode'];
                    $value->departmentcode = $code['departmentcode'];
                    $value->department = $code['department'];
                    $value->sectioncode = $code['sectioncode'];
                    $value->section = $code['section'];
                    $value->teamcode = $code['teamcode'];
                    $value->team = $code['team'];
                    $value->departmentabr = $code['departmentabr'];
                    $value->sectionabr = $code['sectionabr'];
                    $value->teamabr = $code['teamabr'];
                    $value->name = decrypt($value->name);
                    $value->password = $value->password;
                    $value->repas = decrypt($value->repas);
                    $value->username = $value->username;
                    $result[] =  $value;
                }
            return base64_encode(json_encode($result));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function searchUser(Request $request){
        try {
            $result = [];
            $data = User::select(
                'users.id as id',
                'unitrelations.code_rel',
                'unitdepartments.name as department',
                'unitdepartments.abr as departmentabr',
                'unitdepartments.address as departmentaddress',
                'unitsections.name as section',
                'unitsections.abr as sectionabr',
                'unitteams.name as team',
                'unitteams.abr as teamabr',
                'users.unitcode as usercode',
                'users.name as name',
                'users.username as username',
                'users.repas as repas',
                'users.approver as approver',
                'users.office as office',
                'users.rights as rights',
                'users.log as log'
            )
            ->leftjoin('unitrelations','users.unitcode','unitrelations.code_rel')
            ->leftjoin('categories','categories.category_code','unitrelations.category')
            ->leftjoin('unitdepartments','unitdepartments.code','unitrelations.department')
            ->leftjoin('unitsections','unitsections.code','unitrelations.section')
            ->leftjoin('unitteams','unitteams.code','unitrelations.team');
            
            // if($request->keyword){
            //     $data = $data->where('users.name','LIKE','%' . $request->keyword . '%');
            // }
            if($request->category){
                $data = $data->where('unitrelations.category',$request->category);
            }
            if($request->department){
                $data = $data->where('unitrelations.department',$request->department);
            }
            if($request->section){
                $data = $data->where('unitrelations.section',$request->section);
            }
            if($request->team){
                $data = $data->where('unitrelations.team',$request->team);
            }
            $data = $data->get();
            foreach ($data as $key => $value) {
                $value->name = decrypt($value->name);
                $value->username = $value->username;
                $value->repas = decrypt($value->repas);
                // return $value->name;
                if(str_contains(strtolower($value->name), strtolower($request->keyword))){
                    $result[] = $value;
                }
              
               
            }
            return base64_encode(json_encode($result));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function editUser(Request $request){
        try {
            $item = $request->data;
            $val = [
                'unitcode' => $item["unitcode"],
                'name' => encrypt($item["name"]),
                'username' => $item["username"],
                'password' => Hash::make($item["repas"]),
                'repas' => encrypt($item["repas"]),
                'email' => $item["email"],
                'approver' => $item["approver"],
                'rights' => $item["rights"]
            ];
            User::where('id',$item["id"])->update($val);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// 
    public function updateRights(Request $request){
        try {
            User::where('id',$request->id)->update(['rights' => $request->rights]);
            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

}
