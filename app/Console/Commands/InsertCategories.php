<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class InsertCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $array = [
            ["category_code" => "CA01", "category_name" => "HPN & HSG", "updated_by" => null],
            ["category_code" => "CA02", "category_name" => "TYPE COMMANDS", "updated_by" => null],
            ["category_code" => "CA03", "category_name" => "COMBAT FORCES", "updated_by" => null],
            ["category_code" => "CA04", "category_name" => "COMBAT SUPPORT FORCES", "updated_by" => null],
            ["category_code" => "CA05", "category_name" => "GENERAL SUPPORT AND SUSTAINMENT FORCES", "updated_by" => null],
            ["category_code" => "CA06", "category_name" => "RESERVE FORCES", "updated_by" => null],
        ];


        foreach ($array as $key => $value) {
            Category::create($value);
        }
    }
}
