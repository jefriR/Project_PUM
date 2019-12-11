<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/notif.php';

use App\Employees;
use App\User;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestingController extends Controller
{
    public function testingpdf(){
        $data = DB::connection('api_hr')->table('hr_departments')->select('*')->where('NAME', 'DEV')->get()->toArray();

        $pdf = PDF::loadview('pertanggungjawabanPum',['datas'=>$data]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream();
//        return $pdf->download('data-pdf');
    }

    public function testarray(Request $request){
        $file = $request->file('image');
//        $name = $file->getClientOriginalName();
//        $path = $file->storeAs('public',$name);
//        $ph = Storage::disk('public_uploads')->put( $name);

        echo base_path(). '<br>';

// Path to the 'app' folder
        echo app_path(). '<br>';

// Path to the 'public' folder
        echo public_path(). '<br>';

// Path to the 'storage' folder
        echo storage_path(). '<br>';

// Path to the 'storage/app' folder
        echo storage_path('app'). '<br>';

        $get = url('public/storage/Koala.jpg');
        return $get;

    }


    public function testing(Request $request){
        $notif = new \Notification();

        $title = 'Titel Test';
        $message = 'Message Test';

        $notif->setTitle($title);
        $notif->setMessage($message);

        $firebase_api = "AIzaSyCCdupu_-bEbgqiSKmfZx7302_rl2feBlc";

        $requestData = $notif->getNotificatin();

        $fields = array(
            'to' => '/topics/topic',
            'data' => $requestData,
        );

        $url = 'https://fcm.googleapis.com/fcm/send';

        $firebase_api = "AAAAgSKeiQQ:APA91bEEc5jkcAOZb1SE0KdTPtytWnqdTwgbCZezPJSK7vIz-XgbGnC9ELvxlvRjJh7tIwo4JlaUNeDeVgNFiOrlvAQ92SRtlgrvUjWbZ0VFxEMGALbL2Njq78QKvI4COVUHTfOpeMKp";

        $headers = array(
            'Authorization: key=' . $firebase_api,
            'Content-Type: application/json'
        );

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
        echo json_encode($fields,JSON_PRETTY_PRINT);
        echo '</pre></p><h3>Response </h3><p><pre>';
        echo $result;
        echo '</pre></p>';


    }
}
