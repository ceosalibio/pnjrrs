<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rank;
class InsertRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:Rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $array = [
            ["DivisionCode" => "001", "Grade" => "O10", "Rank" => "ADM"],
            ["DivisionCode" => "001", "Grade" => "O10", "Rank" => "GEN"],
            ["DivisionCode" => "001", "Grade" => "O9", "Rank" => "VADM"],
            ["DivisionCode" => "001", "Grade" => "O9", "Rank" => "LTGEN"],
            ["DivisionCode" => "001", "Grade" => "O8", "Rank" => "RADM"],
            ["DivisionCode" => "001", "Grade" => "O8", "Rank" => "MGEN"],
            ["DivisionCode" => "001", "Grade" => "O7", "Rank" => "COMMO"],
            ["DivisionCode" => "001", "Grade" => "O7", "Rank" => "BGEN"],
            ["DivisionCode" => "001", "Grade" => "O6", "Rank" => "CAPT"],
            ["DivisionCode" => "001", "Grade" => "O6", "Rank" => "COL"],
            ["DivisionCode" => "001", "Grade" => "O5", "Rank" => "CDR"],
            ["DivisionCode" => "001", "Grade" => "O5", "Rank" => "LTCOL"],
            ["DivisionCode" => "001", "Grade" => "O4", "Rank" => "LCDR"],
            ["DivisionCode" => "001", "Grade" => "O4", "Rank" => "MAJ"],
            ["DivisionCode" => "001", "Grade" => "O3", "Rank" => "CPT"],
            ["DivisionCode" => "001", "Grade" => "O3", "Rank" => "LT"],
            ["DivisionCode" => "001", "Grade" => "O2", "Rank" => "1LT"],
            ["DivisionCode" => "001", "Grade" => "O2", "Rank" => "LTJG"],
            ["DivisionCode" => "001", "Grade" => "O1", "Rank" => "2LT"],
            ["DivisionCode" => "001", "Grade" => "O1", "Rank" => "ENS"],
            ["DivisionCode" => "002", "Grade" => "E10", "Rank" => "CSM"],
            ["DivisionCode" => "002", "Grade" => "E9", "Rank" => "MCPO"],
            ["DivisionCode" => "002", "Grade" => "E9", "Rank" => "CMSGT"],
            ["DivisionCode" => "002", "Grade" => "E8", "Rank" => "SCPO"],
            ["DivisionCode" => "002", "Grade" => "E8", "Rank" => "SMSGT"],
            ["DivisionCode" => "002", "Grade" => "E7", "Rank" => "CPO"],
            ["DivisionCode" => "002", "Grade" => "E7", "Rank" => "MSGT"],
            ["DivisionCode" => "002", "Grade" => "E6", "Rank" => "PO1"],
            ["DivisionCode" => "002", "Grade" => "E6", "Rank" => "TSGT"],
            ["DivisionCode" => "002", "Grade" => "E5", "Rank" => "PO2"],
            ["DivisionCode" => "002", "Grade" => "E5", "Rank" => "SSGT"],
            ["DivisionCode" => "002", "Grade" => "E4", "Rank" => "PO3"],
            ["DivisionCode" => "002", "Grade" => "E4", "Rank" => "SGT"],
            ["DivisionCode" => "002", "Grade" => "E3", "Rank" => "SN1"],
            ["DivisionCode" => "002", "Grade" => "E3", "Rank" => "FN1"],
            ["DivisionCode" => "002", "Grade" => "E3", "Rank" => "CPL"],
            ["DivisionCode" => "002", "Grade" => "E2", "Rank" => "SN2"],
            ["DivisionCode" => "002", "Grade" => "E2", "Rank" => "FN2"],
            ["DivisionCode" => "002", "Grade" => "E2", "Rank" => "PFC"],
            ["DivisionCode" => "002", "Grade" => "E1", "Rank" => "ASN"],
            ["DivisionCode" => "002", "Grade" => "E1", "Rank" => "AFN"],
            ["DivisionCode" => "002", "Grade" => "E1", "Rank" => "PVT"],
            ["DivisionCode" => "003", "Grade" => "SG24", "Rank" => "SG24"],
            ["DivisionCode" => "003", "Grade" => "SG23", "Rank" => "SG23"],
            ["DivisionCode" => "003", "Grade" => "SG22", "Rank" => "SG22"],
            ["DivisionCode" => "003", "Grade" => "SG21", "Rank" => "SG21"],
            ["DivisionCode" => "003", "Grade" => "SG20", "Rank" => "SG20"],
            ["DivisionCode" => "003", "Grade" => "SG19", "Rank" => "SG19"],
            ["DivisionCode" => "003", "Grade" => "SG18", "Rank" => "SG18"],
            ["DivisionCode" => "003", "Grade" => "SG17", "Rank" => "SG17"],
            ["DivisionCode" => "003", "Grade" => "SG16", "Rank" => "SG16"],
            ["DivisionCode" => "003", "Grade" => "SG15", "Rank" => "SG15"],
            ["DivisionCode" => "003", "Grade" => "SG14", "Rank" => "SG14"],
            ["DivisionCode" => "003", "Grade" => "SG13", "Rank" => "SG13"],
            ["DivisionCode" => "003", "Grade" => "SG12", "Rank" => "SG12"],
            ["DivisionCode" => "003", "Grade" => "SG11", "Rank" => "SG11"],
            ["DivisionCode" => "003", "Grade" => "SG10", "Rank" => "SG10"],
            ["DivisionCode" => "003", "Grade" => "SG9", "Rank" => "SG9"],
            ["DivisionCode" => "003", "Grade" => "SG8", "Rank" => "SG8"],
            ["DivisionCode" => "003", "Grade" => "SG7", "Rank" => "SG7"],
            ["DivisionCode" => "003", "Grade" => "SG6", "Rank" => "SG6"],
            ["DivisionCode" => "003", "Grade" => "SG5", "Rank" => "SG5"],
            ["DivisionCode" => "003", "Grade" => "SG4", "Rank" => "SG4"],
            ["DivisionCode" => "003", "Grade" => "SG3", "Rank" => "SG3"],
            ["DivisionCode" => "003", "Grade" => "SG2", "Rank" => "SG2"],
            ["DivisionCode" => "003", "Grade" => "SG1", "Rank" => "SG1"]
        ];
        foreach ($array as $key => $value) {
            Rank::create($value);
        }
    }
}
