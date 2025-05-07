<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Unitdepartment;
class InsertDepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create department list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $array = [
            [
                'code' => 'D01',
                'abr' => 'HPN & HSG',
                'name' => 'HEADQUARTERS PHILIPPINE NAVY',
                'address' => 'Naval Station Jose V Andrada, 2535 Roxas Boulevard, Manila'
            ],
            [
                'code' => 'D02',
                'abr' => 'PF',
                'name' => 'PHILIPPINE FLEET',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D03',
                'abr' => 'PMC',
                'name' => 'PHILIPPINE MARINES CORPS',
                'address' => 'Marine Barracks Rudiardo Brown, Fort Bonifacio, Taguig City, pmc.mc3@navy.mil.ph'
            ],
            [
                'code' => 'D04',
                'abr' => 'FMRC',
                'name' => 'FLEET-MARINE READY FORCE',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D05',
                'abr' => 'NFW',
                'name' => 'NAVAL FORCES WEST',
                'address' => '\N'
            ],
            [
                'code' => 'D06',
                'abr' => 'NFWM',
                'name' => 'NAVAL FORCES WESTERN MINDANAO',
                'address' => '\N'
            ],
            [
                'code' => 'D07',
                'abr' => 'NFNL',
                'name' => 'NAVAL FORCES NORTHERN LUZON',
                'address' => 'Naval Station Ernesto Ogbinar, Poro Point, City of San Fernando, La Union'
            ],
            [
                'code' => 'D08',
                'abr' => 'NFEM',
                'name' => 'NAVAL FORCES EASTERN MINDANAO',
                'address' => 'Naval Station Felix Apolinario, Panacan, Davao City'
            ],
            [
                'code' => 'D09',
                'abr' => 'NFSL',
                'name' => 'NAVAL FORCES SOUTHERN LUZON',
                'address' => 'Naval Station Julhasan Arasain, Regional Center Site, Rawis, Legazpi City'
            ],
            [
                'code' => 'D10',
                'abr' => 'NFC',
                'name' => 'NAVAL FORCES CENTRAL',
                'address' => 'Naval Base Rafael Ramos, Brgy Looc, Lapu-Lapu City'
            ],
            [
                'code' => 'D11',
                'abr' => 'NAEC',
                'name' => 'NAVAL AMMUNITION AND EXPLOSIVES CENTER',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D12',
                'abr' => 'NISF',
                'name' => 'NAVAL INTELLIGENCE AND SECURITY FORCE',
                'address' => 'Naval Station Jose Francisco, Fort Bonifacio, Taguig City'
            ],
            [
                'code' => 'D13',
                'abr' => 'NICTC',
                'name' => 'NAVAL INFORMATION AND COMMS TECHNOLOGY CENTER',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D14',
                'abr' => 'NCEBde',
                'name' => 'NAVAL COMBAT ENGINEERING BRIGADE',
                'address' => 'Naval Station Jose Francisco, Fort Bonifacio, Taguig City'
            ],
            [
                'code' => 'D15',
                'abr' => 'CMOG',
                'name' => 'CIVIL MILITARY OPERATIONS GROUP',
                'address' => 'Marine Barracks Rudiardo Brown, Fort Bonifacio, Taguig City, hpn.cmog@navy.mil.ph'
            ],
            [
                'code' => 'D16',
                'abr' => 'NAVSOCOM',
                'name' => 'NAVAL SPECIAL OPERATIONS COMMAND',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D17',
                'abr' => 'NMOC',
                'name' => 'NAVAL METEOROLOGICAL AND OCEANOGRAPHIC CENTER',
                'address' => 'Naval Station Pascual Ledesma, Fort San Felipe, Cavite City'
            ],
            [
                'code' => 'D18',
                'abr' => 'NSSC',
                'name' => 'NAVAL SEA SYSTEMS COMMAND',
                'address' => 'Naval Station Pascual Ledesma, Fort San Felipe, Cavite City'
            ],
            [
                'code' => 'D19',
                'abr' => 'NIC',
                'name' => 'NAVAL INSTALLATION COMMAND',
                'address' => 'Naval Base Heracleo Alano, Sangley Point, Cavite City'
            ],
            [
                'code' => 'D20',
                'abr' => 'NLC',
                'name' => 'NAVAL LOGISTICS CENTER',
                'address' => 'Naval Station Pascual Ledesma, Fort San Felipe, Cavite City'
            ],
            [
                'code' => 'D21',
                'abr' => 'NETDC',
                'name' => 'NAVAL EDUCATION, TRAINING & DOCTRINES COMMAND',
                'address' => 'Naval Station Leovigildo Gantioqui, San Antonio, Zambales, netdc.t3@navy.mil.ph'
            ],
            [
                'code' => 'D22',
                'abr' => 'NPMC',
                'name' => 'NAVY PERSONNEL MANAGEMENT CENTER',
                'address' => 'Naval Station Jose Francisco, Fort Bonifacio, Taguig City'
            ],
            [
                'code' => 'D23',
                'abr' => 'PNFC',
                'name' => 'PHILIPPINE NAVY FINANCE CENTER',
                'address' => 'Naval Station Jose Francisco, Fort Bonifacio, Taguig City'
            ],
            [
                'code' => 'D24',
                'abr' => 'NRC',
                'name' => 'NAVAL RESERVE COMMAND',
                'address' => '230 Cabildo Street, Intramuros, Manila'
            ]
        ];

        foreach ($array as $value) {
            Unitdepartment::create($value);
        }
        
    }
}
