<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");


include_once "../db/dbconn.php";
include_once "../classes/transactions.class.php";



$conn = dbconn();
$session = new Transactions;

$data = json_decode(file_get_contents("php://input"));

$url = $_SERVER['REQUEST_URI'];

$imploded = explode("/", $url);

$method = $_SERVER['REQUEST_METHOD'];


if($method == 'GET'){
    $operation = $imploded[4];

    if($operation == "verify"){
        $reference = $imploded[5];
        $session->verifyPayment($conn, $reference);
    }
}else if($method == 'POST'){
    $operation = $imploded[4];

    switch($operation){
        case "paystack":
            $session->recordPayment($conn, $data);
        break;
        case "withdraw" :
            $session->makeWithdrawal($conn, $data);
        break;
        case "fund" :
            $session->initializePayment($conn, $data);
        break;
     }


    
}