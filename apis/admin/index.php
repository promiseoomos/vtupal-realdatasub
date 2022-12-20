<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");


include_once "../db/dbconn.php";
include "../classes/admin.class.php";



$conn = dbconn();
$session = new Admin;

$data = json_decode(file_get_contents("php://input"));

$url = $_SERVER['REQUEST_URI'];

$imploded = explode("/", $url);

$method = $_SERVER['REQUEST_METHOD'];


if($method == 'GET'){
    $operation = $imploded[4];
    // $orgId = $imploded[5];

    switch($operation){
        case "profitanalysis":
            $session->profitAnalysis($conn);
        break;
        case "transactions":
            $session->getTransactions($conn);
        break;
        case "sitedetails":
            $session->getSiteDetails($conn);
        break;
    }

}else if($method == 'POST'){
    $operation = $imploded[4];

    switch($operation){
        case "login":
            $session->loginAdmin($conn, $data);
        break;
        case "updatesitedetails":
            $session->updateSiteDetails($conn, $data);
        break;
        case "updatesitebank":
            $session->updateSiteBank($conn, $data);
        break;
        case "createsiteaccount":
            $session->createSiteAccount($conn, $data);
        break;
        case "updateservice":
            $session->updateService($conn, $data);
        break;
    }
    
    
}