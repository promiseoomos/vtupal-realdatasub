<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");


include_once "../db/dbconn.php";
include_once "../classes/operations.class.php";
include_once "../classes/transactions.class.php";



$conn = dbconn();
$session = new Operations;

$sessionTrx = new Transactions;


$data = json_decode(file_get_contents("php://input"));

$url = $_SERVER['REQUEST_URI'];

$imploded = explode("/", $url);

$method = $_SERVER['REQUEST_METHOD'];


if($method == 'GET'){
    $operation = $imploded[5];

    switch($operation){
        case "referrals":
            $auth = $imploded[6];
            $session->getReferrals($conn, $auth);
        break;
        case "payments":
            $auth = $imploded[6];
            $session->getPayments($conn, $auth);
        break;
        case "deposits":
            $auth = $imploded[6];
            $session->getDeposits($conn, $auth);
        break;
        case "transactions":
            $auth = $imploded[6];
            $session->getTransactions($conn, $auth);
        break;
        case "sms":
            $auth = $imploded[6];
            $session->getSMSMessages($conn, $auth);
        break;
        case "banks":
            $session->getBanks();
        break;
        case "services":
            $session->getServices($conn);
        break;
    }

}else if($method == 'POST'){
    $operation = $imploded[4];

    switch($operation){
        case "upgrade" :
            $sessionTrx->initializePayment($conn, $data);
        break;
    }
}