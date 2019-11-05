<?php

namespace App\Http\Controllers\ReportAPI;

use App\ReportPum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
    public function getDeptReport(){
        $model      = new ReportPum();
        $getDept    = $model->getDept();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDept], 200);
    }

    public function getEmpReport(){
        $model      = new ReportPum();
        $getEmp     = $model->getEmp();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getEmp], 200);
    }

}
