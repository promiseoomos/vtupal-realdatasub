<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");

spl_autoload_register(function($class){
    //echo $class;
    include strtolower($class).".class.php";
});

include "functions.class.php";

include_once "../db/dbconn.php";


class User extends Functions{

    public function signUp($conn, $userObj){

        $first_name = $userObj->first_name;
        $last_name = $userObj->last_name;
        $email = $userObj->email;
        $phone = $userObj->phone;
        $password = $userObj->password;
        $referrer_code = $userObj->ref_code;
        $track_id = $this->checktrackid($conn, "users");
        $ref_code = $this->checktrxcode($conn, "users", "referral_code");

        try {
            // This pumpplace checks if the Current Referrer has complete 3 referrals. If Yes
            // The pumplace looks for the nearest downline that has not completed its 3 referrals
            
            $sqlref = "select * from users where referral_code = '$referrer_code'";
            $queryref = $conn->prepare($sqlref);
            $queryref->execute();
            $resultref = $queryref->fetchAll();

            $countref = count($resultref);
            // echo $countref;
            
            $referrer_id = "";
            $referrer_name = "";

            $d = new DateTime();
            $curd = $d->format("d, F Y");
            

            

            if($countref > 0){
                foreach($resultref as $rowref){
                    $referrer_id = $rowref['track_id'];
                    $referrer_name = $rowref['first_name']." ".$rowref['last_name'];
                }
                $track_id_ref = $this->checktrackid($conn, "referrals");

                $sqlinref = "INSERT INTO `referrals`(`track_id`, `referrer_id`, `referrer_code`, `referred_id`, `referred_date`)  VALUES ('$track_id_ref', '$referrer_id', '$referrer_code', '$track_id', '$curd')";
                $queryinref = $conn->prepare($sqlinref);
                $queryinref->execute();
            }

        } catch (PDOException $e) {

            $return = Array(
                "status" => false,
                "message" => $e->getMessage() . " From Line " . $e->getLine() 
            );

            goto resolution;
        }

        $insert = true;

        // Hash the Password before inserting into the database.
        // From this point only the Hashing script and the password owner knows the real password
        $hashpassword = password_hash($password, PASSWORD_BCRYPT);
        // Email Checker. Checks if the email is existing
        $emch = $this->checkavailable($conn, "email", $email);
       

        if (!$emch) {
            // If email exists, alert the user to use a different email
            $return = Array(
                "message" => "Email Already Exists. Use a different email address.",
                "status" => false,
                "reason" => "existing_email"
            );
            $insert = false;

            goto resolution;
        }

        if($insert){
            $vacctobj = Array(
                "email" => $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "phone" => $phone
            );
            $virtualacct = $this->virtualAcct($vacctobj);
            
            if($virtualacct["account_details"]->status){

                $virtualacctname = $virtualacct["account_details"]->data->account_name;
                $virtualacctno = $virtualacct["account_details"]->data->account_number;
                $virtualacctbank = $virtualacct["account_details"]->data->bank->name;
            }
            if($virtualacct['customer_details']->status){
                $customercode = $virtualacct['customer_details']->data->customer_code;
            }

            $sql =<<<SQLite
                INSERT INTO `users`(`track_id`, `referrer_id`, `first_name`, `last_name`, `password`, `phone`, `email`, `joined_date`,`virtual_account_name`, `virtual_customer_code`, `virtual_account_no`,`virtual_account_bank`, `referral_code`) 
                VALUES ("$track_id", "$referrer_id", "$first_name", "$last_name", "$hashpassword", "$phone", "$email", "$curd", "$virtualacctname", "$customercode", "$virtualacctno", "$virtualacctbank", "$ref_code")
SQLite;

            $query = $conn->prepare($sql);
            $query->execute();

            $this->sendemail($track_id, $email, "https://app.akfemtopup.com.ng");

            if ($query) {
                $return = Array(
                    "status" => true,
                    "message" => "Thanks for Signing up. Go to your mail and verify your account."
                );

                goto resolution;
            }
        }

        resolution:
        echo json_encode($return);
    }

    public function login($conn, $userObj){
        $email = $userObj->email;
        $password = $userObj->password;

        if (empty($email) || empty($password)) {
            $response = array(
                "status" => false,
                "message" => "Password and Email Fields cannot be left Empty",
                "reason" => "empty"
            );

            goto resolution;
        }

        try {
            $sql = "select * from users where email = '{$email}'";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();

            $hashpassword = '';

            foreach ($result as $row) {
                $hashpassword = $row['password'];
                $confirmed = $row['email_verified'];
                $referrerid = $row['referrer_id'];
                $track_id = $row['track_id'];
                $email = $row['email'];

                $newdetails = array(
                    "first_name" => $row['first_name'],
                    "last_name" => $row['last_name'],
                    "fullname" => $row['first_name'] . " " . $row['last_name'],
                    "email" => $row['email'],
                    "phone" => $row['phone'],
                    "type" => $row['type'],
                    "virtual_account_name" => $row['virtual_account_name'],
                    "virtual_account_no" => $row['virtual_account_no'],
                    "virtual_account_bank" => $row['virtual_account_bank'],
                    "virtual_customer_code" => $row["virtual_customer_code"],
                    "currency" => $row['currency'],
                    "wallet_balance" => $row['wallet_balance'],
                    "referral_code" => $row['referral_code'],
                    "referral_wallet" => $row['referral_wallet'],
                    "account_name" => $row['account_name'],
                    "account_number" => $row['account_number'],
                    "bank_name" => $row['bank_name'],
                    "recipient_code" => $row['recipient_code'],
                    "track_id" => $row['track_id'],
                );
            }

            if(strtolower($confirmed) == 'yes'){
                if (password_verify($password, $hashpassword)) {
                
                    $response = array(
                        "status" => true,
                        "message" => "login successful",
                        "details" => $newdetails,
                    );
                    
                } else {
                    $response = array(
                        "status" => false,
                        "message" => "Password and Email Incorrect",
                        "reason" => "incorrect"
                    );
                }
            }else{
                $response = array(
                    "status" => false,
                    "message" => "You have not verified your mail. Please go to your mailbox and verify your mail.",
                    "reason" => "incorrect"
                );

                $this->sendemail($track_id, $email, "akfemtopup.vercel.app");
            }
            

            goto resolution;

        } catch (PDOException $e) {
            $response = array(
                "message" => "error" . $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile(),
            );

            goto resolution;
        }



        resolution:
        echo json_encode($response);
    }

    public function confirmEmail($conn, $cid){

        try{
            $sqls = "select count(*) from users where track_id = '$cid'";
            $querys = $conn->prepare($sqls);

            $querys->execute();
            $result = $querys->fetchColumn();

            if($result > 0){
                try{
            
                    $sql = "UPDATE `users` SET `email_verified` = 'yes' WHERE `track_id` = '$cid'";
                    $query = $conn->prepare($sql);
                    $query->execute();
                    if ($query) {
                        $return = Array(
                            "status" => true,
                            "message" => "Your Email has Been Verified. You can now Login to the App.",
                        );
                    }
        
                }catch(PDOException $e){
                    echo $e->getMessage();
                }

            }else{
                $msg = "Email Does not Exist.";
                $return = Array(
                    "status" => "error",
                    "message" => $msg,
                    "confirmed" => true
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }


        echo json_encode($return);
        
    }

    public function resettoken($conn, $email){

        $rtoken = rand(100000, 1000000);

        try {

            $sql = <<<Sql
                update users set reset_token = '$rtoken' where email = '$email'
            Sql;
            $query = $conn->query($sql);

            if (!$query) {
                echo "Could not Give you a Reset Token";
            } else {

                $sr = $this->sendRtoken($email, $rtoken);
            }

            if($sr){
                $return = Array(
                    'status' => true,
                    "message" => "A Reset Token link has been sent to your {$email}. Check your email and click on the Link to Proceed with Resetting your Password. Please Check your Spam Folder also. Due to a little glitch, all emails from us are read as Spam. It will be resolved soon."
                );
            }else{
                $return = Array(
                    'status' => false,
                    "message" => "Could not send Reset token mail to {$email}. "
                );
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        echo json_encode($return);
    }

    public function newPassword($conn, $pwordobj){
        $pword1 = $pwordobj->password;
        $pword2 = $pwordobj->confirm_password;
        $email = $pwordobj->email;
        $token = $pwordobj->token;

        $hashpword = password_hash($pword1, PASSWORD_BCRYPT);

        if ($pword1 != $pword2) {

            $result = Array(
                'status' => false,
                'message' => "Passwords do not Match"
            );
          
        } else {

            try {

                $sql = <<<Sql
                    select count(*) from users where email = '$email' and reset_token = '$token'
                Sql;
                $query = $conn->query($sql);
                $num_items = $query->fetchColumn();
                //echo $num_items;
                if ($num_items == 0) {

                    $result = Array(
                        'status' => false,
                        'message' => "Your Reset Token has Expired. <a href=". ""."{ name : 'forgot' }".">Request for a new Token</a>"
                    );
                   
                } else {

                    $sql = <<<Sql
                        update users set password = '$hashpword' where email = '$email' and reset_token = '$token'
                    Sql;
                    $query = $conn->query($sql);

                    $this->cleartoken($conn, $email);

                    $result = Array(
                        'status' => true,
                        'message' => "Your Account's Password has been changed successfully."
                    );

                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        echo json_encode($result);

    }

    public function cleartoken($conn, $email){

        try {
            $sql = <<<Sql
                update users set reset_token = '' where email = '$email'
            Sql;
            $query = $conn->query($sql);
            if (!$query) {
                echo "COuld not reset Token";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getUserDetails($conn, $userId){

        try{
            $sqlusers = "select * from users where track_id = '$userId'";
            $queryusers = $conn->prepare($sqlusers);
            $queryusers->execute();
            $resultusers = $queryusers->fetchAll();
            $countusers = count($resultusers);

            if($countusers > 0){
                foreach ($resultusers as $row) {
                    $hashpassword = $row['password'];
                    // $confirmed = $row['email_confirmed'];
                    $referrerid = $row['referrer_id'];
                    $track_id = $row['track_id'];
    
                    $newdetails = array(
                        "first_name" => $row['first_name'],
                        "last_name" => $row['last_name'],
                        "fullname" => $row['first_name'] . " " . $row['last_name'],
                        "email" => $row['email'],
                        "phone" => $row['phone'],
                        "type" => $row['type'],
                        "virtual_account_name" => $row['virtual_account_name'],
                        "virtual_account_no" => $row['virtual_account_no'],
                        "virtual_account_bank" => $row['virtual_account_bank'],
                        "virtual_customer_code" => $row["virtual_customer_code"],
                        "currency" => $row['currency'],
                        "wallet_balance" => $row['wallet_balance'],
                        "referral_code" => $row['referral_code'],
                        "referral_wallet" => $row['referral_wallet'],
                        "account_name" => $row['account_name'],
                        "account_number" => $row['account_number'],
                        "bank_name" => $row['bank_name'],
                        "recipient_code" => $row['recipient_code'],
                        "track_id" => $row['track_id'],
                    );
                }

                $response = array(
                    "status" => true,
                    "message" => "Details gotten successfully",
                    "details" => $newdetails,
                );
            }else{
                $response = array(
                    "status" => false,
                    "message" => "Could not fetch any user with that Id",
                    "details" => Array(),
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage() . " from line " . $e->getLine() . " in ". $e->getFile();
        }

        echo json_encode($response);
    }

    public function initializePayment($conn, $dataObj){

        // print_r($dataObj);

        $d = new DateTime();
        $curd = $d->format("d, F Y");

        $track_id = $this->checktrackid($conn, "transactions");
        $reference = $this->checktrxcode($conn, "transactions", "reference");
        $email = $dataObj->details->email;
        $user_id = $dataObj->details->track_id;

        $customer_code = $dataObj->details->virtual_customer_code;
        $transaction_type = "deposit";
        $amount = $dataObj->amount;
        $pamount = ceil($amount * 100);
        $currency = $dataObj->details->currency;
        $status = "Pending";

        $payObj = Array(
            "reference" => $reference,
            "email" => $email,
            "amount" => (int) $pamount
        );

        $url = $this->_makePayment($payObj);


        $trnx = "";

        try {
            $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `transaction_type`, `amount`, `currency`, `status`, `trnx_date`, `verified`, `payment_url`) 
            VALUES ('$track_id','$user_id', '$email', '$customer_code', '$reference', '$transaction_type', '$amount', '$currency', '$status', '$curd', 'no', '$url')";
            $querytrnx = $conn->prepare($sqltrnx);
            $querytrnx->execute();

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $url;
    }

    public function recordPayment($conn, $dataObj){

        // print_r($dataObj);
        $track_id = $this->checktrackid($conn, "transactions");
        $trx_reference = $this->checktrxcode($conn, "transactions", "reference"); 

        $d = new DateTime();
        $curd = $d->format("d, F Y");

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
                    }

                    if($verified == 'yes'){
                        $return_status = "success";
                        $return = Array(
                            "status" => "success",
                            "message" => "Webhook Processed Successfully"
                        );
                        $this->creditReferral($conn,$user_id);
                        $return_obj = json_encode($return);
                    }else{

                        $sqluptrnx = "UPDATE `transactions` SET `payment_channel`='$channel',`amount`='$amount',`currency`='$currency',`charge` = '$charge',`status`='$status',`transaction_object`='$trnxobj',`trnx_date`='$curd', `verified`='yes', `transaction_id` = '$transaction_id' WHERE reference = '$reference'";
                        $queryuptrnx = $conn->prepare($sqluptrnx);
                        $queryuptrnx->execute();
                        $return = Array(
                            "status" => "success",
                            "message" => "Webhook Processed Successfully"
                        );
                        $this->creditReferral($conn,$user_id);
                        $return_obj = json_encode($return);

                        $this->creditWallet($conn, $amount, $user_id);
                    }

                    goto resolution;
                }

            }catch (PDOException $e){
                echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
            }

            try {
                $sqltrnx = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `transaction_type`, `transaction_id`, `payment_channel`, `amount`, `currency`, `charge`, `transaction_object`,`status`, `trnx_date`, `verified`) 
                            VALUES ('$track_id', '$user_id', '$user_email', '$customer_code', '$trx_reference', 'bank-deposit','$transaction_id', '$channel', '$amount', '$currency', '$charge', '${trnxobj}','success','$curd', 'yes')";
                $querytrnx = $conn->prepare($sqltrnx);
                $querytrnx->execute();

            } catch (PDOException $e) {
                echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
            }

            try {
                
                $sqlupuserbal = "UPDATE `users` set `wallet_balance`='$nextbal' where virtual_customer_code = '$customer_code'";
                $queryupuserbal = $conn->prepare($sqlupuserbal);
                $queryupuserbal->execute();
                
                $this->creditReferral($conn,$user_id);
            } catch (PDOException $e) {
                echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
            }

            

            $return_status = "success";
            $return = Array(
                "status" => "success",
                "message" => "Webhook Processed Successfully"
            );
            $return_obj = json_encode($return);
        }

        $track_id = $this->checktrackid($conn, "webhooks");
        $event = $dataObj->event;
        $webhook_data = json_encode($dataObj);

        try{
            $sqlwebhook = "INSERT INTO `webhooks`(`track_id`, `webhook_data`, `transaction_reference`, `transaction_event`, `webhook_status`, `status_obj`, `sender`, `sent_date`) 
                        VALUES ('$track_id', '${webhook_data}', '$reference', '$event', '$return_status', '${return_obj}', 'Paystack', '$curd')";
            $querywebhook = $conn->prepare($sqlwebhook);
            $querywebhook->execute();
        }catch (PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        resolution:
        // echo $return_obj;    
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

    public function updateUser($conn, $userObj){
        
        $first_name = $userObj->first_name;
        $last_name = $userObj->last_name;
        $email = $userObj->email;
        $phone = $userObj->phone;
        $account_number = $userObj->account_number;
        $account_name = $userObj->account_name;
        $bank_name = $userObj->bank_name;
        $bank_code = $userObj->bank_code;
        $phone = $userObj->phone;
        $track_id = $userObj->track_id;

        $recipient_code = "";

        if(strlen($account_number) > 0 && strlen($bank_code) > 0){
            try{
                $sqlchk = "select * from users where track_id = '$track_id'";
                $querychk = $conn->prepare($sqlchk);
                $querychk->execute();
                $resultchk = $querychk->fetchAll();
                $countchk = count($resultchk);

 

                if($countchk > 0){
                    foreach ($resultchk as $rowchk) {
                        $ex_account_number = $rowchk['account_number'];
                        $ex_recipient_code = $rowchk['recipient_code'];
                    }

                    if(($account_number == $ex_account_number) && strlen($ex_recipient_code) > 0){
                        $recipient_code = $ex_recipient_code;
                    }else if($account_number != $ex_account_number || strlen($ex_recipient_code) < 1){
                        $createRecipient = $this->_createRecipient($userObj);

                        if(isset($createRecipient->status)){
                            if($createRecipient->status){
                                $recipient_code = $createRecipient->data->recipient_code;
                            }
                        }else{
                            $return = Array(
                                "status" => false,
                                "message" => "Could not update account details, try again."
                            );

                            goto resolution;
                        }
                        
                    }
                }else{
                    $return = Array(
                        "status" => false,
                        "message" => "Could not find user account"
                    );

                    goto resolution;
                }

            }catch(PDOException $e){
                echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
            }
        }

        try {
            $sql = "UPDATE `users` SET `first_name`='$first_name',`last_name`='$last_name',`phone`='$phone',`account_name`='$account_name',`account_number`='$account_number',`bank_name`='$bank_name', `recipient_code` = '$recipient_code' WHERE track_id = '$track_id'";
            $query = $conn->prepare($sql);
            $query->execute();

            if($query){
                $return = Array(
                    "status" => true,
                    "message" => "User details has been updated successful."
                );
            }

            goto resolution;
        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        resolution:
        echo json_encode($return);
        
    }

    public function debitWallet($conn, $amount, $userId){
        try{
            $sqlini = "select wallet_balance  from users where track_id = '$userId'";
            $queryini = $conn->prepare($sqlini);
            $queryini->execute();
            $resultini = $queryini->fetchAll();
        
            foreach($resultini as $rowini){
                $current_balance = $rowini['wallet_balance'];
            }
            $newBalance = $current_balance - $amount;

            $sqlfin = "UPDATE `users` SET `wallet_balance` = '$newBalance' where track_id = '$userId'";
            $queryfin = $conn->prepare($sqlfin);
            $queryfin->execute();

        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $newBalance;        
    }

    public function creditWallet($conn, $amount, $userId){

        try{
            $sqlini = "select wallet_balance from users where track_id = '$userId'";
            $queryini = $conn->prepare($sqlini);
            $queryini->execute();
            $resultini = $queryini->fetchAll();
        
            foreach($resultini as $rowini){
                $current_balance = $rowini['wallet_balance'];
            }
            $newBalance = (int) $current_balance + $amount;

            $sqlfin = "UPDATE users SET  `wallet_balance` = '$newBalance' where track_id = '$userId'";
            $queryfin = $conn->prepare($sqlfin);
            $queryfin->execute();

        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $newBalance;
    }

    public function verifyPayment($conn, $reference){

        $verify = $this->_verifyPayment($reference);

        if($verify->status){

            $this->recordPayment($conn,$verify);

            $return = Array(
                "status" => "success",
                "message" => $verify->message
            );
        }else{
            $return = Array(
                "status" => "error",
                "message" => $verify->message
            );
        }

        echo json_encode($return);

    }

    public function makeWithdrawal($conn, $dataObj){
        print_r($this->_makeWithdrawal($dataObj));
    }

    


}

$conn = dbconn();

$session = new User();

$data = json_decode(file_get_contents("php://input"));

if(isset($data->action)){
    switch($data->action){
        case "register-user":
            $session->signUp($conn, $data->dataObj);
        break;
        case "login-user":
            $session->login($conn, $data->dataObj);
        break;
        case "fund-wallet":
            $session->initializePayment($conn, $data->dataObj);
        break;
        case "get-banks":
            $session->getBanks();
        break;
        case "verify-account":
            $session->verifyAccountName($data->dataObj);
        break;
        case "update-user":
            $session->updateUser($conn, $data->dataObj);
        break;
        
        case "get-user-details":
            $session->getUserDetails($conn, $data->userId);
        break;
        case "verify-pay":
            $session->verifyPayment($conn, $data->reference);
        break;
        case "withdraw-funds":
            $session->makeWithdrawal($conn, $data->dataObj);
        break;
    }

}



if(isset($_GET['paystack_payment'])){
    $session->recordPayment($conn, $data);
}