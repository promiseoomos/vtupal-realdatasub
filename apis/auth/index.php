<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");


include_once "../db/dbconn.php";
include "../classes/user.class.php";



$conn = dbconn();
$session = new User;

$data = json_decode(file_get_contents("php://input"));

$url = $_SERVER['REQUEST_URI'];

$imploded = explode("/", $url);

$method = $_SERVER['REQUEST_METHOD'];


if($method == 'GET'){
    $operation = $imploded[4];
    $auth = $imploded[5];

    switch($operation){
        case "confirm":
            $cid = $_REQUEST['cid'];

            $session->confirmEmail($conn,$cid);
        break;
        case "resettoken":
            $email = $_REQUEST['email'];

            $session->resettoken($conn,$email);
        break;
    }

}else if($method == 'POST'){
    $operation = $imploded[4];

    switch($operation){
        case "login":
            $session->login($conn, $data);
        break;
        case "signup":
            $session->signUp($conn, $data);
        break;
        case "resetpassword":
            $session->newPassword($conn, $data);
        break;
    }
}