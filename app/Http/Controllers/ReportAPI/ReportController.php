<?php

namespace App\Http\Controllers\ReportAPI;

use App\ReportPum;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(){
        $model  = new ReportPum();

        $getDept    = $model->getDept();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDept], 200);
    }
}
