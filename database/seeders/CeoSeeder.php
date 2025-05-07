<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'unitcode' => 'RE14',
            'name' => encrypt('Ceo Salibio'),
            'password' => Hash::make('26591'),
            'repas' => encrypt('26591'),
            'username' => 'ceo',
            'approver' => 1,
            'rights' => 1,
            'email' => '',
            'office' => 3,
            'log' => json_encode(array())
        ]);
    }
}
