<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");

spl_autoload_register(function($class){
    include strtolower($class).".class.php";
});

include_once "functions.class.php";

class Transactions extends Functions{

    public function initializePayment($conn, $dataObj){

        $d = new DateTime();
        $curd = $d->format("d, F Y");

        $track_id = $this->checktrackid($conn, "transactions");
        $reference = $this->checktrxcode($conn, "transactions", "reference");
        $email = $dataObj->details->email;
        $user_id = $dataObj->details->track_id;

        $customer_code = $dataObj->details->virtual_customer_code;
        $transaction_type = $dataObj->transactionType;
        $amount = $dataObj->amount;
        $pamount = ceil($amount * 100);
        $currency = $dataObj->details->currency;
        $status = "pending";

        $payObj = Array(
            "reference" => $reference,
            "email" => $email,
            "amount" => (int) $pamount
        );

        $url = $this->_makePayment($payObj);


        $trnx = "";

        try {
            $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `transaction_type`, `category`, `recipient`, `amount`, `currency`, `status`, `trnx_date`, `verified`, `payment_url`) 
            VALUES ('$track_id','$user_id', '$email', '$customer_code', '$reference', '$transaction_type','debit','$user_id','$amount', '$currency', '$status', '$curd', 'no', '$url')";
            $querytrnx = $conn->prepare($sqltrnx);
            $querytrnx->execute();

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $url;
    }

    public function recordPayment($conn, $dataObj){

        $track_id = $this->checktrackid($conn, "transactions");
        $trx_reference = $this->checktrxcode($conn, "transactions", "reference"); 

        $d = new DateTime();
        $curd = $d->format("d, F Y");

        if(isset($dataObj->event) ? $dataObj->event == "charge.success" || $dataObj->event == "transfer.success"  : (isset($dataObj->status) ? $dataObj->status : false)){
            
            if(isset($dataObj->event) ? $dataObj->event == "charge.success" : (isset($dataObj->status) ? $dataObj->status : false)){
                $customer_code = $dataObj->data->customer->customer_code;
                $amount = ceil($dataObj->data->amount / 100);
                $reference = $dataObj->data->reference;
                $channel = $dataObj->data->channel;
                $currency = $dataObj->data->currency;
                $status = $dataObj->data->status;
                $trnxobj = json_encode($dataObj->data);
                $transaction_id = $dataObj->data->id;
                $charge = ceil($dataObj->data->fees / 100); 

                try {
                    $sqluser = "select * from users where virtual_customer_code = '$customer_code'";
                    $queryuser = $conn->prepare($sqluser);
                    $queryuser->execute();
                    $resultuser = $queryuser->fetchAll();
                    $countuser = count($resultuser);
    
                    if($countuser > 0){
                        foreach($resultuser as $rowuser){
                            $user_email = $rowuser['email'];
                            $user_id = $rowuser['track_id'];
        
                            $currentbal = $rowuser['wallet_balance'];
                        }
                    }else{
                        $return_status = "error";
                        $return = Array(
                            "status" => "error",
                            "message" => "No User Found"
                        );
                        $return_obj = json_encode($return);
    
                        goto resolution;
                    }
    
                    $nextbal = $currentbal + $amount;
                    // echo $nextbal;
    
                } catch (PDOException $e) {
                    echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                }

                try{

                    $sqlchktrnx = "select * from transactions where reference = '$reference'";
                    $querychktrnx = $conn->prepare($sqlchktrnx);
                    $querychktrnx->execute();
                    $resultchktrnx = $querychktrnx->fetchAll();
                    $counttrnx = count($resultchktrnx);
    
                    $sqlchktrnxverified = "select * from transactions where transaction_id = '$transaction_id' && verified = 'yes'";
                    $querychktrnxverified = $conn->prepare($sqlchktrnxverified);
                    $querychktrnxverified->execute();
                    $resultchktrnxverified = $querychktrnxverified->fetchAll();
                    $counttrnxverified = count($resultchktrnxverified);
    
        
                    if($counttrnxverified > 0){
                        foreach($resultchktrnxverified as $rowchktrnxverified){
                            $verified = $rowchktrnxverified['verified'];
                        }
    
                        if($verified == 'yes'){
                            $return_status = "success";
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully. Transaction has already been verified."
                            );
                            $return_obj = json_encode($return);
                            $this->creditReferral($conn,$user_id);
    
                            goto resolution;
                        }
                    }
    
                    if($counttrnx > 0){
                        foreach($resultchktrnx as $rowchktrnx){
                            $verified = $rowchktrnx['verified'];
                            $transactionType = $rowchktrnx['transaction_type'];
                        }
    
                        if($verified == 'yes'){
                            $return_status = "success";
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully. Transaction has already been Verified"
                            );
                            $this->creditReferral($conn,$user_id);
                            $return_obj = json_encode($return);
                        }else{
    
                            $sqluptrnx = "UPDATE `transactions` SET `payment_channel`='$channel',`amount`='$amount',`currency`='$currency',`charge` = '$charge',`status`='$status',`transaction_object`='$trnxobj',`trnx_date`='$curd', `verified`='yes', `transaction_id` = '$transaction_id' WHERE reference = '$reference'";
                            $queryuptrnx = $conn->prepare($sqluptrnx);
                            $queryuptrnx->execute();

                            $return_status = "success";
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully. Transaction processed and verified Successfully"
                            );

                            $this->creditReferral($conn,$user_id);
                            $return_obj = json_encode($return);

                            if($transactionType == 'upgrade'){
                                try {
                                
                                    $sqlupuserbal = "UPDATE `users` set `type`='reseller' where virtual_customer_code = '$customer_code'";
                                    $queryupuserbal = $conn->prepare($sqlupuserbal);
                                    $queryupuserbal->execute();
                                    
                                    
                                } catch (PDOException $e) {
                                    echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                                }
                            }else{
                                $this->creditWallet($conn, $amount, $user_id);
                            }
                        }
    
                        goto resolution;
                    }else{
                        try {
                            $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `transaction_type`, `transaction_id`, `payment_channel`, `amount`, `category`, `currency`, `charge`, `transaction_object`,`status`, `trnx_date`, `verified`) 
                                        VALUES ('$track_id', '$user_id', '$user_email', '$customer_code', '$trx_reference', 'bank-deposit','$transaction_id', '$channel', '$amount','credit', '$currency', '$charge', '${trnxobj}','success','$curd', 'yes')";
                            $querytrnx = $conn->prepare($sqltrnx);
                            $querytrnx->execute();
            
                        } catch (PDOException $e) {
                            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                        }

                        
                        $this->creditWallet($conn, $amount, $user_id);
                        $this->creditReferral($conn,$user_id);
                        
                        $return_status = "success";
                        $return = Array(
                            "status" => "success",
                            "message" => "Webhook Processed Successfully. Transaction processed and verified Successfully"
                        );
                        $return_obj = json_encode($return);

                        goto resolution;
            
                    }
    
                }catch (PDOException $e){
                    echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                }

    
            }else if($dataObj->event == 'transfer.success'){

                $recipient_code = $dataObj->data->recipient->recipient_code;
                $amount = ceil($dataObj->data->amount / 100);
                $reference = $dataObj->data->reference;
                $channel = isset($dataObj->data->channel) ? $dataObj->data->channel : "";
                $currency = isset($dataObj->data->currency) ? $dataObj->data->currency : "";
                $status = isset($dataObj->data->status) ? $dataObj->data->status : "";
                $description = isset($dataObj->data->reason) ? $dataObj->data->reason : "";
                $trnxobj = json_encode($dataObj->data);
                $transaction_id = $dataObj->data->id;
                $charge = $dataObj->data->fee_charged;

                $totalcharge = $charge + 50;

                $amount = $amount + $totalcharge;

                try {
                    $sqluser = "select * from users where recipient_code = '$recipient_code'";
                    $queryuser = $conn->prepare($sqluser);
                    $queryuser->execute();
                    $resultuser = $queryuser->fetchAll();
                    $countuser = count($resultuser);
    
                    if($countuser > 0){
                        foreach($resultuser as $rowuser){
                            $user_email = $rowuser['email'];
                            $user_id = $rowuser['track_id'];
        
                            $currentbal = $rowuser['wallet_balance'];
                        }
                    }else{
                        $return_status = "error";
                        $return = Array(
                            "status" => "error",
                            "message" => "No User Found"
                        );
                        $return_obj = json_encode($return);
    
                        goto resolution;
                    }
    
                    $nextbal = $currentbal - ($amount + $totalcharge);
    
                } catch (PDOException $e) {
                    echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                }

                try{

                    $sqlchktrnx = "select * from transactions where reference = '$reference'";
                    $querychktrnx = $conn->prepare($sqlchktrnx);
                    $querychktrnx->execute();
                    $resultchktrnx = $querychktrnx->fetchAll();
                    $counttrnx = count($resultchktrnx);
    
                    $sqlchktrnxverified = "select * from transactions where transaction_id = '$transaction_id' && verified = 'yes'";
                    $querychktrnxverified = $conn->prepare($sqlchktrnxverified);
                    $querychktrnxverified->execute();
                    $resultchktrnxverified = $querychktrnxverified->fetchAll();
                    $counttrnxverified = count($resultchktrnxverified);
    
        
                    if($counttrnxverified > 0){
                        foreach($resultchktrnxverified as $rowchktrnxverified){
                            $verified = $rowchktrnxverified['verified'];
                        }
    
                        if($verified == 'yes'){
                            $return_status = "success";
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully. Transaction has already been verified."
                            );
                            $return_obj = json_encode($return);
    
                            goto resolution;
                        }
                    }
    
                    if($counttrnx > 0){
                        foreach($resultchktrnx as $rowchktrnx){
                            $verified = $rowchktrnx['verified'];
                        }
    
                        if($verified == 'yes'){
                            $return_status = "success";
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully. Already verified"
                            );
                            $return_obj = json_encode($return);
                        }else{
    
                            $sqluptrnx = "UPDATE `transactions` SET `payment_channel`='$channel',`amount`='$amount',`charge` = '$totalcharge',`status`='$status',`transaction_object`='$trnxobj',`trnx_date`='$curd', `verified`='yes', `transaction_id` = '$transaction_id' WHERE reference = '$reference'";
                            $queryuptrnx = $conn->prepare($sqluptrnx);
                            $queryuptrnx->execute();
                            $return = Array(
                                "status" => "success",
                                "message" => "Webhook Processed Successfully"
                            );
                            $return_status = "success";
                            $return_obj = json_encode($return);
                        }

                        try {

                            $sqlupuserbal = "UPDATE `users` set `wallet_balance`='$nextbal' where recipient_code = '$recipient_code'";
                            $queryupuserbal = $conn->prepare($sqlupuserbal);
                            $queryupuserbal->execute();
                            
                        } catch (PDOException $e) {
                            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                        }
    
                        goto resolution;
                    }else{
                        try {
                            $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `description`, `transaction_type`, `transaction_id`, `payment_channel`, `amount`,`category`, `charge`, `transaction_object`,`status`, `trnx_date`, `verified`) 
                                        VALUES ('$track_id', '$user_id', '$user_email', '$recipient_code', '$trx_reference', '$description', 'bank-withdrawal','$transaction_id', '$channel', '$amount', 'debit', '$totalcharge', '${trnxobj}','success','$curd', 'yes')";
                            $querytrnx = $conn->prepare($sqltrnx);
                            $querytrnx->execute();
            
                        } catch (PDOException $e) {
                            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                        }
                    }

                    try {
                    
                        $sqlupuserbal = "UPDATE `users` set `wallet_balance`='$nextbal' where recipient_code = '$recipient_code'";
                        $queryupuserbal = $conn->prepare($sqlupuserbal);
                        $queryupuserbal->execute();
                        
                    } catch (PDOException $e) {
                        echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                    }
    
                }catch (PDOException $e){
                    echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                }
    
            }

            
            $return_status = "success";
            $return = Array(
                "status" => "success",
                "message" => "Webhook Processed Successfully"
            );
            $return_obj = json_encode($return);
        }

        if(isset($dataObj->event)){
            if($dataObj->event == "transfer.failed"){
                $recipient_code = $dataObj->data->recipient->recipient_code;
                $amount = ceil($dataObj->data->amount / 100);
                $reference = $dataObj->data->reference;
                $channel = isset($dataObj->data->channel) ? $dataObj->data->channel : "";
                $currency = isset($dataObj->data->currency) ? $dataObj->data->currency : "";
                $status = isset($dataObj->data->status) ? $dataObj->data->status : "error";
                $description = isset($dataObj->data->reason) ? $dataObj->data->reason : "";
                $trnxobj = json_encode($dataObj->data);
                $transaction_id = $dataObj->data->id;
                $charge = $dataObj->data->fee_charged;

                if($counttrnx > 0){
                    foreach($resultchktrnx as $rowchktrnx){
                        $verified = $rowchktrnx['verified'];
                    }

                    if($verified == 'yes'){
                        $return_status = "success";
                        $return = Array(
                            "status" => "success",
                            "message" => "Webhook Processed Successfully"
                        );
                        $return_obj = json_encode($return);
                    }else{

                        $sqluptrnx = "UPDATE `transactions` SET `payment_channel`='$channel',`amount`='$amount',`charge` = '$totalcharge',`status`='$status',`transaction_object`='$trnxobj',`trnx_date`='$curd', `verified`='yes', `transaction_id` = '$transaction_id' WHERE reference = '$reference'";
                        $queryuptrnx = $conn->prepare($sqluptrnx);
                        $queryuptrnx->execute();

                        $return = Array(
                            "status" => "success",
                            "message" => "Transaction Processed and Verified Successfully"
                        );
                        $return_obj = json_encode($return);
                    }

                    goto resolution;
                }else{
                    try {
                        $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `description`, `transaction_type`, `transaction_id`, `payment_channel`, `amount`,`category`, `charge`, `transaction_object`,`status`, `trnx_date`, `verified`) 
                                    VALUES ('$track_id', '$user_id', '$user_email', '$recipient_code', '$trx_reference', '$description', 'bank-withdrawal','$transaction_id', '$channel', '$amount', 'debit', '$totalcharge', '${trnxobj}','success','$curd', 'yes')";
                        $querytrnx = $conn->prepare($sqltrnx);
                        $querytrnx->execute();
        
                    } catch (PDOException $e) {
                        echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                    }
                }

            }
        }


        resolution:

        $track_id = $this->checktrackid($conn, "webhooks");
        $event = isset($dataObj->event) ? $dataObj->event : "deposit.success";
        $webhook_data = json_encode($dataObj);

        try{
            $sqlwebhook = "INSERT INTO `webhooks`(`track_id`, `webhook_data`, `transaction_reference`, `transaction_event`, `webhook_status`, `status_obj`, `sender`, `sent_date`) 
                        VALUES ('$track_id', '${webhook_data}', '$reference', '$event', '$return_status', '${return_obj}', 'Paystack', '$curd')";
            $querywebhook = $conn->prepare($sqlwebhook);
            $querywebhook->execute();
        }catch (PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        echo $return_obj;    
    }
    public function makeWithdrawal($conn, $dataObj){
        $reference = $this->checktrxcode($conn, "transactions", "reference");

        $queueTransfer = $this->_makeWithdrawal($dataObj);
        $transObj = array();

        if($queueTransfer){
            if($queueTransfer->status){
                $transObj = Array(
                    "userId" => $dataObj->details->userId,
                    "transactionType" => "bank-withdrawal",
                    "paymentChannel" => "Paystack",
                    "amount" => $dataObj->amount,
                    "status" => $queueTransfer->data->status,
                    "description" => $queueTransfer->data->reason. " - ". $queueTransfer->data->transfer_code,
        
                );

                
                $this->recordTransactions($conn, $transObj, $queueTransfer->data->reference, "debit");
            }
        }


        echo json_encode($queueTransfer);



    }

    public function verifyPayment($conn, $reference){

        $verify = $this->_verifyPayment($reference);

        if(!isset($verify->status)){
           $return = Array(
                "status" => "error",
                "message" => "Network Error. Check your internet Connection to continue"
           );

           $return_obj = json_encode($return);
        }else{
            if($verify->status){

                $record = $this->recordPayment($conn,$verify);
                
                $return_obj = $record;
            }else{
                $return = Array(
                    "status" => "error",
                    "message" => $verify->message
                );

                $return_obj = json_encode($return);
            }
        }

        echo $return_obj;

    }

    


}