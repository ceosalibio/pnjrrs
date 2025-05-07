<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Personnel;
use App\Models\Serial;
use App\Models\Training;
use App\Models\Equipments;
use App\Models\Equipmentitem;
use App\Models\Maintenance;
use App\Models\Facilities;
use App\Models\Unitrelation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public $crypter;
    public $other;
    public function __construct()
    {
        $this->crypter = new Crypter;
        $this->other = new DetailsController;
    }


    public function getReport(Request $request){
        try {

            switch ($request->report) {
                //office
                case '1': 
                    return $this->getOffice($request);
                    break;
                default:
                    return $this->getUnit($request);
                    break;
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
//********** */ office function
    public function getOffice($request){
        DB::beginTransaction(); 
        try {
            $data = [];
            $tables = [
                ['table' => 'personnels' , 'name' => 'Personnel Readiness'],
                ['table' => 'trainings' , 'name' => 'Training Readiness'],
                ['table' => 'equipments' , 'name' => 'Equipment Readiness'],
                ['table' => 'maintenances' , 'name' => 'Maintenance Readiness'],
                ['table' => 'facilities' , 'name' => 'Facility Readiness'],
            ];
            $totalReadiness = 0;
            $countReadiness = 0;
            $overallReadiness = 0;
            foreach ($tables as $key => $table) {
                $name = $table["name"];
                $item = DB::table($table["table"])
                    ->select("result as $name")
                    ->where([
                        ['status',5],
                        ['month',$request->month],
                        ['unit',$request->unit]
                    ])
                    ->first();
                if ($item) {
                    $val = decrypt($item->$name); // decrypt the specific column
                    if (isset($val['readiness'])) {
                        $totalReadiness += $val['readiness'];
                        $countReadiness++;
                        $item->column = $name;
                        $item->readiness = $val['readiness'];
                        
                    }
                }
                $data[] = $item;
            }
            if ($countReadiness > 0) {
                $overallReadiness = $totalReadiness / $countReadiness;
            }
            $overallRating = (object) ['column' => 'OVERALL RATING', 'readiness' => $overallReadiness, 'total' => true];
            $data[] = $overallRating;
            return base64_encode(json_encode($data));
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

// getting all unit
    public function allUnit($val){
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
            ->leftjoin('unitteams','unitteams.code','unitrelations.team');
            if($val->report == '2'){
                $data = $data->where('unitrelations.department',$val->dept)->get();
            }else{
                $data = $data->get();
            }

            return $data;

        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// *************************unit function
   /**
     * Retrieves unit data from multiple tables.
     *
     * @param object $request
     * @return array
     */
    public function getUnit($request)
    {
        try {
            $units = $this->allUnit($request);
            $tables = ['personnels', 'trainings', 'equipments', 'maintenances', 'facilities'];
            $results = [];

            foreach ($units as $unit) {
                foreach ($tables as $table) {
                    $record = $this->getRecord($table, $unit, $request->month);
                    if ($record) {
                        $results[$table][] = $this->formatRecord($record, $unit, $request);
                    } else {
                        $results[$table][] = $this->formatEmptyRecord($unit, $request);
                    }
                    
                }
            }
            // return $results;
            return base64_encode(json_encode($results));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }



    /**
     * Retrieves a record from a table based on the given conditions.
     *
     * @param string $table
     * @param object $unit
     * @param int $month
     * @return object|null
     */
    private function getRecord($table, $unit, $month)
    {
        return DB::table($table)
            ->where([
                ['status', 5],
                ['month', $month],
                ['unit', $unit->code_rel]
            ])
            ->first();
    }

    /**
     * Formats a record with additional data.
     *
     * @param object $record
     * @param object $unit
     * @return object
     */
    private function formatRecord($record, $unit,$request)
    {
        // $record->main = $unit->departmentabr;
        // $record->maincode = $unit->departmentcode;
        // $record->mainname = $unit->department;
        $record->unit = $request->report == '3'?$unit->departmentabr:($unit->office?$unit->teamabr:$unit->sectionabr);
        $record->unitcode = $request->report == '3'?$unit->departmentcode:($unit->office?$unit->teamcode:$unit->sectioncode);
        $record->unitname = $request->report == '3'?$unit->department:($unit->office?$unit->team:$unit->section);
        $record->result = decrypt($record->result)?decrypt($record->result):'';
        return $record;
    }

    private function formatEmptyRecord($unit,$request)
    {
        return [
            'unit_rel' => $unit->code_rel,
            // 'main' => $unit->departmentabr,
            // 'maincode' => $unit->departmentcode,
            // 'mainname' => $unit->department,
            'unit' =>  $request->report == '3'?$unit->departmentabr:($unit->office?$unit->teamabr:$unit->sectionabr),
            'unitcode' => $request->report == '3'?$unit->departmentcode:($unit->office?$unit->teamcode:$unit->sectioncode),
            'unitname' => $request->report == '3'?$unit->department:($unit->office?$unit->team:$unit->section)

        ];
    }


    // **********for all report(for user have office restriction)
    public function reportRestriction(Request $request){
        try {
            $data = Report::where([
                ['report',$request->report],
                ['month',$request->month],
                ['unit',$request->unit],
                $request->restriction != '3' ? ['tab', $request->tab] :  ['tab','!=','']
            ])
            ->get();
            $count = count($data);
            if($count){
                $data[0]['tabStatus'] = $this->tabStatus($request); //to get status of every tab
                if($request->tab == '6'){
                    $data[0]['allAssessment'] = $this->allAssessment($request); //to get all assessment of every tab
                }
                
                return base64_encode(json_encode($data));
            }else{
                return $this->insertReport($request);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

//******get the previous data
    public function insertReport($val){
        try {
            $insert = new Report;
            $insert->unit = $val->unit;
            $insert->report = $val->report;
            $insert->tab = $val->restriction == '3'?'6':$val->tab;
            $insert->assessment = $this->other->remarksTemplate();
            $insert->declined = json_encode(array());
            $insert->noted = $this->other->notedTemplate();
            $insert->month = $val->month;
            $insert->status = '0';
            $insert->updated_by = $val->name;
            $insert->ipaddress = $this->other->ipaddress();
            $insert->save();
            return $this->reportRestriction($val);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
// tab status
    public function tabStatus($req){
        try {
            $reports = [];
            $units = $this->allUnit($req);
            foreach ($units as $unit) {
                for ($i = 1; $i <= 6; $i++) {
                    $report = [
                        'tabNo' => $i,
                        'tabStatus' => '0',
                    ];
                    $val = Report::where([
                        ['report', $req->report],
                        ['month', $req->month],
                        ['unit', $unit->code_rel],
                        ['tab', $i],
                    ])->first();
                    if (!empty($val)) {
                        $report['tabStatus'] = $val->status;
                        $reports[] = $report;
                    }
                    // 
                    
                }
            }
            usort($reports, function($a, $b) {
                return $a['tabNo'] <=> $b['tabNo'];
            });
            return $reports;
            
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }

// get all assessment
    public function allAssessment($req){
        try {
            $remarks = [];
            $units = $this->allUnit($req);
            foreach ($units as $unit) {
                for ($i = 1; $i <= 5; $i++) {
                    $remark = [
                        'tab' => $i,
                        'remarks' => '',
                    ];
                    $val = Report::select('tab','assessment')->where([
                        ['report', $req->report],
                        ['month', $req->month],
                        ['unit', $unit->code_rel],
                        ['tab', $i],
                    ])->first();
                    if (!empty($val)) {
                        $remark['remarks'] = $val->assessment;
                        $remarks[] = $remark;
                    }
                }
            }
            usort($remarks, function($a, $b) {
                return $a['tab'] <=> $b['tab'];
            });
            return $remarks;
            
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
    }
}
