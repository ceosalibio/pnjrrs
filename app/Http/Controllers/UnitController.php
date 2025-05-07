<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Category;
use App\Models\Unitdepartment;
use App\Models\Unitsection;
use App\Models\Unitteam;
use App\Models\Unitrelation;

class UnitController extends Controller
{
// 
    public function getCategory(){
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function getDepartment(Request $request){
        try {
            if(!$request->data){
                $department = Unitdepartment::all();
            }else{
                $department = Unitdepartment::select(
                    'code',
                    'abr',
                    'name',
                    'address',
                    'code_rel',
                    'office',
                    'restrict',
                    'rights',
                    'hpn',
                    'org'
                )
                ->leftjoin('unitrelations','unitrelations.department','unitdepartments.code')
                ->where('unitrelations.category',$request->data)
                ->get();
            }
            return response()->json($department);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function getSection(Request $request){
        try {
                $section = Unitsection::select(
                    'code',
                    'abr',
                    'name',
                    'code_rel',
                    'office',
                    'restrict',
                    'rights',
                    'hpn',
                    'org'
                )
                ->leftjoin('unitrelations','unitrelations.section','unitsections.code')
                ->where('unitrelations.department',$request->data)
                ->get();
            return response()->json($section);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function getTeam(Request $request){
        try {
                $team = Unitteam::select(
                    'code',
                    'abr',
                    'name',
                    'code_rel',
                    'office',
                    'restrict',
                    'rights',
                    'hpn',
                    'org'

                )
                ->leftjoin('unitrelations','unitrelations.team','unitteams.code')
                ->where('unitrelations.section',$request->data)
                ->get();
            return response()->json($team);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function unitInformation(Request $request){
        try {
            $relation = Unitrelation::select(
                'code_rel',
                'office',
                'restrict',
                'rights',
                'hpn',
                'org'
            );
            $relation = $request->team?$relation->where('team',$request->team):
            ($request->section?$relation->where('section',$request->section):$relation->where('code_rel',$request->unit));
            $relation = $relation->get();
            return response()->json($relation);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function unitRelation($val){
        try {
            $data = Unitrelation::select(
                'unitrelations.code_rel',
                'unitrelations.office',
                'unitrelations.restrict',
                'unitrelations.rights',
                'unitrelations.hpn',
                'unitrelations.org',
                'categories.category_code as categorycode',
                'categories.category_name as category',
                'unitdepartments.code as departmentcode',
                'unitdepartments.name as department',
                'unitdepartments.abr as departmentabr',
                'unitdepartments.address as departmentaddress',
                'unitsections.code as sectioncode',
                'unitsections.name as section',
                'unitsections.abr as sectionabr',
                'unitteams.code as teamcode',
                'unitteams.name as team',
                'unitteams.abr as teamabr'
            )
            ->leftjoin('categories','categories.category_code','unitrelations.category')
            ->leftjoin('unitdepartments','unitdepartments.code','unitrelations.department')
            ->leftjoin('unitsections','unitsections.code','unitrelations.section')
            ->leftjoin('unitteams','unitteams.code','unitrelations.team')
            ->where('unitrelations.code_rel',$val)
            ->first();
            return $data;

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// 
    public function getRank(){
        try {
             $data = \DB::table('rank')->whereNull('DeletedDate')->get();
             return base64_encode(json_encode($data));
        }  catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// 
    public function gradeRequired(){
        try {
            $data = \DB::table('requiredgrade')->whereNull('DeletedDate')->get();
            return base64_encode(json_encode($data));
        }  catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

    
    public function getAfpos(){
        try {
             $data = \DB::table('requiredafpos')->whereRaw("AfposName NOT LIKE '%/%'")->get();
             return base64_encode(json_encode($data));
        }  catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
    
}
