<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");

spl_autoload_register(function($class){
    //echo $class;
    include strtolower($class).".class.php";
});

include "functions.class.php";



class Vend extends Functions{

    public function transferFunds($conn, $dataObj){

        $track_id = $dataObj->track_id;
        $amount = $dataObj->amount;
        
        
    }

    public function getBanks(){
        $banks = $this->listBanks();

        echo json_encode(Array(
            "banks" => $banks
        ));
    }

    public function verifyAccountName($dataObj){
        $account_name = $this->verifyAccount($dataObj);

        echo json_encode($account_name);
    }
    public function getBillerCat(){
        $billers = $this->getBillerCategory();

        $return = Array(
            "status" => "success",
            "message" => "Successfully fetched Billers",
            "data" => Array(
                "dataPlans" => $billers->Dataplans,
                "cablePlans" => $billers->Cableplan
            )
        );
        echo json_encode($return);
    }

    public function billValidator($dataObj){
        $validated = $this->_validateBill($dataObj);

        echo json_encode($validated);
    }

    public function createBill($conn, $dataObj){
        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');
        $recorded = $this->recordTransactions($conn, $dataObj, $reference, "debit");

        $check = $this->checkTransactionFeasibility($conn, $dataObj->userId, $dataObj->amount);
  
        if($check['status'] == 'failed'){
            $return = Array(
                "status" => "error",
                "message" => $check['message']
            );

            $trnxObj = Array(
                "status" => "error",
                "id" => "",
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
            );

            goto resolution;
        }

        $createdBill = $this->_createBill( $dataObj, $reference);


        $returnBill = json_decode($createdBill);

        if(isset($returnBill->error)){
            $status = "error";
            $tnxId = "";

            $return = Array(
                "status" => $status,
                "message" => "could not Process Billing : {$returnBill->error[0]}. If Issue persist, contact the site admin",
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
                "userId" => $dataObj->userId
            );
        }else if(isset($returnBill->Status)){
            if($returnBill->Status == 'successful'){
                $status = "success";
                $tnxId = $returnBill->id;
    
               
    
                $return = Array(
                    "status" => "success",
                    "message" => "Billing done successfully",
                    "amount" => $dataObj->amount,
                    "face_amount" => $dataObj->face_amount,
                    "userId" => $dataObj->userId
                );
            }else{
                $status = "error";
                $tnxId = "";
    
                $return = Array(
                    "status" => "error",
                    "message" => "Could not process Billing : Network Error",
                    "amount" => $dataObj->amount,
                    "face_amount" => $dataObj->face_amount,
                    "userId" => $dataObj->userId
                );
            }  
        }else{
            $status = "error";
            $tnxId = "";

            $return = Array(
                "status" => "error",
                "message" => "Could not process Billing : An API Error Occurred",
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
                "userId" => $dataObj->userId
            );
        }

        $trnxObj = Array(
            "status" => $status,
            "id" => $tnxId,
            "amount" => $dataObj->amount,
            "face_amount" => $dataObj->face_amount,
        );


        resolution:
        $this->updateTransaction($conn, $trnxObj, $dataObj, $recorded);
        
        echo json_encode($return);
    }
    public function vendSME($conn, $dataObj, $network){

        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');
        $recorded = $this->recordTransactions($conn, $dataObj, $reference, "debit");

        $check = $this->checkTransactionFeasibility($conn, $dataObj->userId, $dataObj->amount);

        if($check['status'] == 'failed'){
            $return = Array(
                "status" => "error",
                "message" => $check['message'],
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
                "userId" => $dataObj->userId
            );

            goto resolution;
        }

        $buySME = json_decode($this->_vendSME($dataObj, $reference));

        if($buySME->success){
            
            $return = Array(
                "status" => "success",
                "message" => "SME Vended successfully",
                "data" => $buySME,
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
                "userId" => $dataObj->userId
            );

        }else{
            $return = Array(
                "status" => "error",
                "message" => "$buySME->comment. If the issue persist, contact the site owner",
                "amount" => $dataObj->amount,
                "face_amount" => $dataObj->face_amount,
                "userId" => $dataObj->userId
            );
        }

        

        resolution:
        $this->updateTransaction($conn, $return, $dataObj, $recorded);
        echo json_encode($return);

    }

    public function vendAirtime($conn, $dataObj, $network){

        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');
        $recorded = $this->recordTransactions($conn, $dataObj, $reference, "debit");

        $check = $this->checkTransactionFeasibility($conn, $dataObj->userId, $dataObj->amount);

        if($check['status'] == 'failed'){
            $return = Array(
                "status" => "error",
                "message" => $check['message'],
                "amount" => $dataObj->face_amount,
                "face_amount" => $dataObj->charge_amount,
                "userId" => $dataObj->userId
            );

            goto resolution;
        }

        $buyAirtime = json_decode($this->_vendAirtime($dataObj, $reference));

        if($buyAirtime->success){
            
            $return = Array(
                "status" => "success",
                "message" => "Airtime Vended successfully",
                "data" => $buyAirtime,
                "amount" => $dataObj->face_amount,
                "face_amount" => $dataObj->charge_amount,
                "userId" => $dataObj->userId
            );

        }else{
            $return = Array(
                "status" => "error",
                "message" => "$buyAirtime->comment. If the issue persist, contact the site owner",
                "amount" => $dataObj->face_amount,
                "face_amount" => $dataObj->charge_amount,
                "userId" => $dataObj->userId
            );
        }

        

        resolution:

        $this->updateTransaction($conn, $return, $dataObj, $recorded);
        echo json_encode($return);

    }

    public function vendSMS($conn, $dataObj){
        
        $track_id = $this->checktrackid($conn, "smsmessages");
        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');


        $recorded = $this->recordTransactions($conn, $dataObj, $reference, "debit");

        $totalAmount = $dataObj->amountPerPage * $dataObj->recipientsCount;

        $check = $this->checkTransactionFeasibility($conn, $dataObj->userId, $totalAmount);

        if($check['status'] == 'failed'){
            $return = Array(
                "status" => "error",
                "message" => $check['message']
            );

            goto resolution;
        }

        $subject = $dataObj->subject;
        $message = addSlashes($dataObj->message);
        $senderId = $dataObj->senderId;
        $recipients = $dataObj->recipients;
        $recipientsCount = $dataObj->recipientsCount;
        $amountPerPage = $dataObj->amountPerPage;
        $routingConfig = $dataObj->routing;
        $type = $dataObj->type;
        $userId = $dataObj->userId;
        $face_amount = $dataObj->face_amount;

        $total_amount = (float) $amountPerPage * $recipientsCount;
        
        try{

            $sqlinsert =<<<Div
            INSERT INTO `smsmessages`( `track_id`,`user_id`, `reference`, `subject`, `message`, `sender_id`, `recipients`, `route_config`, `type`, `amount`)
            VALUES ('$track_id', '$userId', '$reference', '$subject',"${message}", '$senderId', '$recipients', '$routingConfig', '$type', '$total_amount' )
Div;
            $queryinsert = $conn->prepare($sqlinsert);
            $queryinsert->execute();

        }catch(PDOException $e){
            echo $e->getMessage();
        }

        $sent = $this->_sendSMS($dataObj, $reference);

        $encoded_sent = json_encode($sent);

        if(isset($sent->successful)){
            if(strlen($sent->successful) > 0){

                $return = Array(
                    "status" => "success",
                    "message" => "Bulk SMS Has been sent successfully. Total amount spent is ₦$total_amount",
                    "data" => $sent,
                    "amount" => $total_amount,
                    "face_amount" => $face_amount,
                    "userId" => $dataObj->userId
                );

                try{
                    
                    $sqlup = "UPDATE `smsmessages` SET `status`='success',`response_obj`= '${encoded_sent}' WHERE track_id = '$track_id'";
                    $queryup = $conn->prepare($sqlup);
                    $queryup->execute();

                }catch(PDOException $e){
                    echo $e->getMessage(). " at ". $e->getLine(). " in " . $e->getFile(). " File";
                }

                goto resolution;
            }
        }else if(isset($sent->error)){
            if($sent->error){
                
                $return = Array(
                    "status" => "error",
                    "message" => $sent->comment,
                    "amount" => $total_amount,
                    "face_amount" => $face_amount,
                    "data" => $sent,
                    "userId" => $dataObj->userId
                );

                try{

                    $sqlup = "UPDATE `smsmessages` SET `status`='error',`response_obj`='${encoded_sent}' WHERE track_id = '$track_id'";
                    $queryup = $conn->prepare($sqlup);
                    $queryup->execute();

                }catch(PDOException $e){
                    echo $e->getMessage(). " at ". $e->getLine(). " in " . $e->getFile(). " File";
                }

                goto resolution;
            }
        }else{
            $return = Array(
                "status" => "error",
                "message" => "Could not process Request",
                "amount" => $total_amount,
                "face_amount" => $face_amount,
                "userId" => $dataObj->userId
            );
        }

        resolution:
        
        $this->updateTransaction($conn, $return, $dataObj, $recorded);
        echo json_encode($return);
    }
    


}