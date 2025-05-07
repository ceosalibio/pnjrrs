<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requiredgrade;

class InsertGrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:grade';

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
            ["DivisionCode" => "001", "GradeCode" => "GRO01", "GradeName" => "O10"],
            ["DivisionCode" => "001", "GradeCode" => "GRO02", "GradeName" => "O9"],
            ["DivisionCode" => "001", "GradeCode" => "GRO03", "GradeName" => "O8"],
            ["DivisionCode" => "001", "GradeCode" => "GRO04", "GradeName" => "O7"],
            ["DivisionCode" => "001", "GradeCode" => "GRO05", "GradeName" => "O6"],
            ["DivisionCode" => "001", "GradeCode" => "GRO06", "GradeName" => "O5"],
            ["DivisionCode" => "001", "GradeCode" => "GRO07", "GradeName" => "O4"],
            ["DivisionCode" => "001", "GradeCode" => "GRO08", "GradeName" => "O3"],
            ["DivisionCode" => "001", "GradeCode" => "GRO09", "GradeName" => "O3/O4"],
            ["DivisionCode" => "001", "GradeCode" => "GRO10", "GradeName" => "O2"],
            ["DivisionCode" => "001", "GradeCode" => "GRO11", "GradeName" => "O1"],
            ["DivisionCode" => "002", "GradeCode" => "GRE01", "GradeName" => "E10"],
            ["DivisionCode" => "002", "GradeCode" => "GRE02", "GradeName" => "E9"],
            ["DivisionCode" => "002", "GradeCode" => "GRE03", "GradeName" => "E8"],
            ["DivisionCode" => "002", "GradeCode" => "GRE04", "GradeName" => "E7"],
            ["DivisionCode" => "002", "GradeCode" => "GRE05", "GradeName" => "E7/E8"],
            ["DivisionCode" => "002", "GradeCode" => "GRE06", "GradeName" => "E6"],
            ["DivisionCode" => "002", "GradeCode" => "GRE07", "GradeName" => "E5"],
            ["DivisionCode" => "002", "GradeCode" => "GRE08", "GradeName" => "E4"],
            ["DivisionCode" => "002", "GradeCode" => "GRE09", "GradeName" => "E4/E5/E6"],
            ["DivisionCode" => "002", "GradeCode" => "GRE10", "GradeName" => "E3"],
            ["DivisionCode" => "002", "GradeCode" => "GRE11", "GradeName" => "E2"],
            ["DivisionCode" => "002", "GradeCode" => "GRE12", "GradeName" => "E1"],
            ["DivisionCode" => "003", "GradeCode" => "GRC01", "GradeName" => "SG24"],
            ["DivisionCode" => "003", "GradeCode" => "GRC02", "GradeName" => "SG23"],
            ["DivisionCode" => "003", "GradeCode" => "GRC03", "GradeName" => "SG22"],
            ["DivisionCode" => "003", "GradeCode" => "GRC04", "GradeName" => "SG21"],
            ["DivisionCode" => "003", "GradeCode" => "GRC05", "GradeName" => "SG20"],
            ["DivisionCode" => "003", "GradeCode" => "GRC06", "GradeName" => "SG19"],
            ["DivisionCode" => "003", "GradeCode" => "GRC07", "GradeName" => "SG18"],
            ["DivisionCode" => "003", "GradeCode" => "GRC08", "GradeName" => "SG17"],
            ["DivisionCode" => "003", "GradeCode" => "GRC09", "GradeName" => "SG16"],
            ["DivisionCode" => "003", "GradeCode" => "GRC10", "GradeName" => "SG15"],
            ["DivisionCode" => "003", "GradeCode" => "GRC11", "GradeName" => "SG14"],
            ["DivisionCode" => "003", "GradeCode" => "GRC12", "GradeName" => "SG13"],
            ["DivisionCode" => "003", "GradeCode" => "GRC13", "GradeName" => "SG12"],
            ["DivisionCode" => "003", "GradeCode" => "GRC14", "GradeName" => "SG11"],
            ["DivisionCode" => "003", "GradeCode" => "GRC15", "GradeName" => "SG10"],
            ["DivisionCode" => "003", "GradeCode" => "GRC16", "GradeName" => "SG09"],
            ["DivisionCode" => "003", "GradeCode" => "GRC17", "GradeName" => "SG08"],
            ["DivisionCode" => "003", "GradeCode" => "GRC18", "GradeName" => "SG07"],
            ["DivisionCode" => "003", "GradeCode" => "GRC19", "GradeName" => "SG06"],
            ["DivisionCode" => "003", "GradeCode" => "GRC20", "GradeName" => "SG05"],
            ["DivisionCode" => "003", "GradeCode" => "GRC21", "GradeName" => "SG04"],
            ["DivisionCode" => "003", "GradeCode" => "GRC22", "GradeName" => "SG03"],
            ["DivisionCode" => "003", "GradeCode" => "GRC23", "GradeName" => "SG02"],
            ["DivisionCode" => "003", "GradeCode" => "GRC24", "GradeName" => "SG01"]
        ];

        foreach ($array as $key => $value) {
            Requiredgrade::create($value);
        }
    }
}
