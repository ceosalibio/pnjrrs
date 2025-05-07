<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Division;

class InsertDivision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:division';

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
            ["division_code" => "001", "division_name" => "Officer", "updated_by" => null],
            ["division_code" => "002", "division_name" => "Enlisted", "updated_by" => null],
            ["division_code" => "003", "division_name" => "Civillian", "updated_by" => null],
        ];

        foreach ($array as $key => $value) {
            Division::create($value);
        }
    }
}
