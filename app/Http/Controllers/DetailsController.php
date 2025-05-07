<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public function remarksTemplate(){
        $data =[
            [
                'question' => 'a. What capability shortfall exists or what has occurred to cause the lower assessment, and which elements of operational preparedness are affected?',
                'assessment' => ''
            ],
            [
                'question' => 'b. What are the consequences of the lower level of readiness?',
                'assessment' => ''
            ],
            [
                'question' => 'c. What is required to remedy the situation?',
                'assessment' => ''
            ],
            [
                'question' => 'd.  What level is achievable within a prescribed period?',
                'assessment' => ''
            ],
        ];

        return json_encode($data);
    }

    public function notedTemplate(){
        $data =[
            [
                'prepared_by' => '',
                'prepared_date' => '',
                'approved_by2' => '',
                'approved_date2' => '',
                'approved_by3' => '',
                'approved_date3' => '',
                'approved_by4' => '',
                'approved_date4' => '',
                'noted_by' => '',
                'noted_date' => ''
            ],
           
        ];

        return json_encode($data);
    }

    public function ipaddress(){
        return \Request::ip();
    }
}
