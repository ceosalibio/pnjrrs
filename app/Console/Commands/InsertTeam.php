<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Unitteam;

class InsertTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:team';

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
            ["code" => "T01", "abr" => "OFOC", "name" => "OFFICE OF THE FLAG OFFICER IN COMMAND", "updated_by" => null],
            ["code" => "T02", "abr" => "OFVC", "name" => "OFFICE OF THE VICE COMMANDER", "updated_by" => null],
            ["code" => "T03", "abr" => "CNS", "name" => "CHIEF OF NAVAL STAFF", "updated_by" => null],
            ["code" => "T04", "abr" => "OESPA", "name" => "OFFICE OF ETHICAL STANDARDS AND PUBLIC ACCOUNTABILITY", "updated_by" => null],
            ["code" => "T05", "abr" => "NIA", "name" => "THE NAVAL INTERNAL AUDITOR", "updated_by" => null],
            ["code" => "T06", "abr" => "NSO", "name" => "NAVAL SAFETY OFFICE", "updated_by" => null],
            ["code" => "T07", "abr" => "ONSS", "name" => "OFFICE OF THE NAVAL STRATEGIC STUDIES", "updated_by" => null],
            ["code" => "T08", "abr" => "CNLE", "name" => "CENTER FOR NAVAL LEADERSHIP & EXCELLENCE", "updated_by" => null],
            ["code" => "T09", "abr" => "NIG", "name" => "THE NAVAL INSPECTOR GENERAL", "updated_by" => null],
            ["code" => "T10", "abr" => "ONFM", "name" => "OFFICE OF THE NAVY FINANCIAL MANAGEMENT", "updated_by" => null],
            ["code" => "T11", "abr" => "OCMC", "name" => "OFFICE OF THE COMMAND MASTER CHIEF PETTY OFFICER", "updated_by" => null],
            ["code" => "T12", "abr" => "ACNSN1", "name" => "AC OF NS FOR PERSONNEL N1", "updated_by" => null],
            ["code" => "T13", "abr" => "ACNSN2", "name" => "AC OF NS FOR INTELLIGENCE N2", "updated_by" => null],
            ["code" => "T14", "abr" => "ACNSN3", "name" => "AC OF NS FOR OPERATIONS N3", "updated_by" => null],
            ["code" => "T15", "abr" => "ACNSN4", "name" => "AC OF NS FOR LOGISTICS N4", "updated_by" => null],
            ["code" => "T16", "abr" => "ACNSN5", "name" => "AC OF NS FOR PLANS N5", "updated_by" => null],
            ["code" => "T17", "abr" => "ACNSN6", "name" => "AC OF NS FOR C4ISR SYSTEMS N6", "updated_by" => null],
            ["code" => "T18", "abr" => "ACNSN7", "name" => "AC OF NS FOR CIVIL MILITARY OPERATIONS N7", "updated_by" => null],
            ["code" => "T19", "abr" => "ACNSN8", "name" => "AC OF NS FOR EDUCATION & TRAINING N8", "updated_by" => null],
            ["code" => "T20", "abr" => "ACNSN9", "name" => "AC OF NS FOR RESERVIST & RETIREE AFFAIRS N9", "updated_by" => null],
            ["code" => "T21", "abr" => "ACNSN10", "name" => "AC OF NS FOR FINANCIAL MANAGEMENT N10", "updated_by" => null],
            ["code" => "T22", "abr" => "ACNSN11", "name" => "AC OF NS FOR NAVAL SYSTEMS ENGINEERING N11", "updated_by" => null],
            ["code" => "T23", "abr" => "NJA", "name" => "THE NAVAL JUDGE ADVOCATE", "updated_by" => null],
            ["code" => "T24", "abr" => "NCE", "name" => "THE NAVY CHIEF OF ENGINEERS", "updated_by" => null],
            ["code" => "T25", "abr" => "NCC", "name" => "THE NAVAL CHIEF CHAPLAIN", "updated_by" => null],
            ["code" => "T26", "abr" => "CNN", "name" => "THE CHIEF NURSE NAVY", "updated_by" => null],
            ["code" => "T27", "abr" => "CSN", "name" => "THE CHIEF SURGEON NAVY", "updated_by" => null],
            ["code" => "T28", "abr" => "CDS", "name" => "THE CHIEF DENTAL SURGEON", "updated_by" => null],
            ["code" => "T29", "abr" => "NOC", "name" => "NAVAL OPERATIONS CENTER", "updated_by" => null],
            ["code" => "T30", "abr" => "ONA", "name" => "OFFICE OF THE NAVAL ADJUTANT", "updated_by" => null],
            ["code" => "T31", "abr" => "NPM", "name" => "THE NAVAL PROVOST MARSHAL", "updated_by" => null],
            ["code" => "T32", "abr" => "NASO", "name" => "NAVAL ACCOUNTING SERVICE OFFICE", "updated_by" => null],
            ["code" => "T33", "abr" => "NPAO", "name" => "NAVAL PUBLIC AFFAIRS OFFICE", "updated_by" => null],
            ["code" => "T34", "abr" => "NHRO", "name" => "NAVY HUMAN RIGHTS OFFICE", "updated_by" => null],
            ["code" => "T35", "abr" => "NSPAO", "name" => "NAVAL SUPPLY AND PROPERTY ACCOUNTABILITY OFFICE", "updated_by" => null],
            ["code" => "T36", "abr" => "GDO", "name" => "GENDER AND DEVELOPMENT OFFICE", "updated_by" => null],
            ["code" => "T37", "abr" => "CRDO", "name" => "CIVILIAN RESOURCE DEVELOPMENT OFFICE", "updated_by" => null],
            ["code" => "T38", "abr" => "HSSG", "name" => "HEADQUARTERS SERVICE SUPPORT GROUP", "updated_by" => null],
            ["code" => "T39", "abr" => "HNIC", "name" => "HEADQUARTERS NAVAL INSTALLATION COMMAND", "updated_by" => null],
            ["code" => "T40", "abr" => "ICTS", "name" => "INSTALLATION COMMAND TRAINING SCHOOL", "updated_by" => null],
            ["code" => "T41", "abr" => "HNBC", "name" => "HEADQUARTERS NAVAL BASE CAVITE (HNBC)", "updated_by" => null],
            ["code" => "T42", "abr" => "NSG", "name" => "NBC SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T43", "abr" => "NSG", "name" => "NBC SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T44", "abr" => "NSPL", "name" => "NAVAL STATION PASCUAL LEDESMA", "updated_by" => null],
            ["code" => "T45", "abr" => "HCNH", "name" => "HEADQUARTERS CAVITE NAVAL HOSPITAL", "updated_by" => null],
            ["code" => "T46", "abr" => "CNH-AS", "name" => "CNH - ADMINISTRATIVE SERVICE", "updated_by" => null],
            ["code" => "T47", "abr" => "CNH-MS", "name" => "CNH - MEDICAL SERVICE", "updated_by" => null],
            ["code" => "T48", "abr" => "CNH-NS", "name" => "CNH - NURSING SERVICE", "updated_by" => null],
            ["code" => "T49", "abr" => "HBNS", "name" => "HEADQUARTERS BONIFACIO NAVAL STATION (HBNS)", "updated_by" => null],
            ["code" => "T50", "abr" => "HMNH", "name" => "HEADQUARTERS MANILA NAVAL HOSPITAL", "updated_by" => null],
            ["code" => "T51", "abr" => "BSG", "name" => "BNS SUPPORT GROUP", "updated_by" => null],
            ["code" => "T52", "abr" => "MNH-AS", "name" => "MNH - ADMINISTRATIVE SERVICE", "updated_by" => null],
            ["code" => "T53", "abr" => "MNH-MS", "name" => "MNH - MEDICAL SERVICE", "updated_by" => null],
            ["code" => "T54", "abr" => "MNH-NS", "name" => "MNH - NURSING SERVICE", "updated_by" => null],
            ["code" => "T55", "abr" => "HNSEO", "name" => "HEADQUARTERS NAVAL STATION ERNESTO OGBINAR (NSEO)", "updated_by" => null],
            ["code" => "T56", "abr" => "NSEO-BSG", "name" => "NSEO -NAVAL BASES/STATION SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T57", "abr" => "HNBCO", "name" => "HEADQUARTERS NAVAL BASE CAMILO OSIAS (NBCO)", "updated_by" => null],
            ["code" => "T58", "abr" => "NBCO-NOBSG", "name" => "NBCO - NAVAL OPERATING BASE SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T59", "abr" => "HNSJA", "name" => "HEADQUARTERS NAVAL STATION JULHASAN ARASAIN (NSJA)", "updated_by" => null],
            ["code" => "T60", "abr" => "NSJA-SSG", "name" => "NSJA - STATION SUPPORT GROUP", "updated_by" => null],
            ["code" => "T61", "abr" => "HNSCC", "name" => "HEADQUARTERS NAVAL STATION CARLITO CUNANAN (HNSCC)", "updated_by" => null],
            ["code" => "T62", "abr" => "NSCC-SG", "name" => "NSCC - SUPPORT GROUP", "updated_by" => null],
            ["code" => "T63", "abr" => "NSEL", "name" => "NAVAL STATION EMILIO LIWANAG", "updated_by" => null],
            ["code" => "T64", "abr" => "NSAJ", "name" => "NAVAL STATION APOLINARIO JALANDOON", "updated_by" => null],
            ["code" => "T65", "abr" => "NSNDR", "name" => "NAVAL STATION NARCISO DEL ROSARIO", "updated_by" => null],
            ["code" => "T66", "abr" => "HNBRR", "name" => "HEADQUARTERS NAVAL BASE RAFAEL RAMOS (NBRR)", "updated_by" => null],
            ["code" => "T67", "abr" => "NBRR-NBSG", "name" => "NBRR - NAVAL BASE SUPPORT GROUP", "updated_by" => null],
            ["code" => "T68", "abr" => "NSAP", "name" => "NAVAL STATION ALFONSO PALCENCIA", "updated_by" => null],
            ["code" => "T69", "abr" => "NSDP", "name" => "NAVAL STATION DIOSCORO PAPA", "updated_by" => null],
            ["code" => "T70", "abr" => "HNSFA", "name" => "HEADQUARTERS NAVAL STATION FELIX APOLINARIO (HNSFA)", "updated_by" => null],
            ["code" => "T71", "abr" => "NSFA -NBSG", "name" => "NSFA - NAVAL BASES/STATION SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T72", "abr" => "HNSRE", "name" => "HEADQUARTERS NAVAL STATION ROMULO ESPALDON (HNSRE)", "updated_by" => null],
            ["code" => "T73", "abr" => "NSRE-NBSG", "name" => "NSRE - NAVAL BASES/STATION SUPPORT GROUPS", "updated_by" => null],
            ["code" => "T74", "abr" => "NSJM", "name" => "NAVAL STATION JUAN MAGLUYAN", "updated_by" => null],
            ["code" => "T75", "abr" => "NSRH", "name" => "NAVAL STATION RIO HONDO", "updated_by" => null],
            ["code" => "T76", "abr" => "HNSLG", "name" => "HEADQUARTERS NAVAL STATION LEOVIGILDO GANTIOQUI (HNSLG)", "updated_by" => null],
            ["code" => "T77", "abr" => "NSLG-SSG", "name" => "NSLG - STATION SUPPORT GROUPS", "updated_by" => null]
        ];

        foreach ($array as $value) {
            Unitteam::create($value);
        }
    }
}
