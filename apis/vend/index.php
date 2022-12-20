<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");


include_once "../db/dbconn.php";
include_once "../classes/vend.class.php";
include_once "../classes/functions.class.php";



$conn = dbconn();
$session = new Vend;

// $session2 = new Functions;

$data = json_decode(file_get_contents("php://input"));

$url = $_SERVER['REQUEST_URI'];

$imploded = explode("/", $url);

$method = $_SERVER['REQUEST_METHOD'];


if($method == 'GET'){
    $operation = $imploded[4];

    switch($operation){
        case "billers":
            $session->getBillerCat();
        break;
    }

}else if($method == 'POST'){
    $operation = $imploded[4];

    switch($operation){
        case "billers":
            $session->getBillerCat();
        break;
        case "createbill":
            $session->createBill($conn, $data);
        break;
        case "validaterecipient":
            $session->billValidator($data);
        break;
        case "sme":
            $network = $imploded[5];
            $session->vendSME($conn, $data, $network);
        break;
        case "airtime":
            $network = $imploded[5];
            $session->vendAirtime($conn, $data, $network);
        break;
        case "bulksms":
            $session->vendSMS($conn, $data);
        break;
    }
}