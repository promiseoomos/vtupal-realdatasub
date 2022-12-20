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
    // $id = $imploded[4];

    $session->getUserAccounts($conn);
}else if($method == 'POST'){
    $session->creditUserAccount($conn, $data);
}