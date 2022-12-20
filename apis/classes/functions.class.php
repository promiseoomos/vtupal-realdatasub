<?php

include "api.class.php";

class Functions extends API{

    public function generatetrack(){

        $random1 = rand(100, 1000);
        $random2 = rand(100, 1000);
        $track_id = "10" . $random1 . $random2;

        return $track_id;

    }
    public function sanitize2($var){

        $var = stripslashes($var);

        $var = strip_tags($var);

        trim($var);

        $var = htmlentities($var);

        return $var;

    }

    public function checktrackid($conn, $table, $checktype = "noloop", $tracksarray = []){
        set_time_limit(0);
        ignore_user_abort(true);

        // print_r($tracksarray);
        // echo $checktype;

        $track_id = "";
        try {
            $chktrack = $this->generatetrack();

            if($checktype == "noloop"){
                switch ($table) {
                    case "$table" : 
                        $sql = "select * from $table";
                        $sqlc = "select count(*) from $table";
                    break;
                }
    
                $query = $conn->prepare($sql);
                $query->execute();
    
                $num = $conn->query($sqlc)->fetchColumn();
    
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
                if ($num > 0) {
                    checker:
                    foreach ($result as $checker) {
                        if ($checker['track_id'] != $chktrack) {
                            $track_id = $chktrack;
                        } else {
                            $track_id = $this->generatetrack();
                            goto checker;
                        }
                    }
                } else {
                    $track_id = $chktrack;
                }
            }else if($checktype == 'loop'){
                checker2:
                if(!in_array($chktrack, $tracksarray)){
                    $track_id = $chktrack;
                }else{
                    $track_id = $this->generatetrack();
                    goto checker2;
                }
            }
            

            return $track_id;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function checkavailable($conn, $column, $item){

        if ($column == "email") {

            $sql = "select * from users where email = '$item'";
            $sqlc = "select count(*) from users where email = '$item'";
        } else if ($column == "username") {
            $sql = "select * from users where username = '$item'";
            $sqlc = "select count(*) from users where username = '$item'";
        }

        $query = $conn->prepare($sql);
        $query->execute();

        $num = $conn->query($sqlc)->fetchColumn();

        //$result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($num > 0) {
            return false;
        } else {
            return true;
        }

    }

    public function generatetrx(){
            
        $genkeys = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $numkeys = strlen($genkeys);
        $ref = '';
        for($i=0;$i< 10; $i++){
            $pos = rand(0, $numkeys);
            $ref .= substr($genkeys, $pos, 1);
        }

        return $ref;
    }

    public function checktrxcode($conn, $table, $column = 'trx_code'){
        $trx_code = "";

        try {
            $trx = $this->generatetrx();
               
            $sql = "select * from $table where $column = '$trx'";
            $sqlc = "select count(*) from $table where $column = '$trx'";
                        
            $query = $conn->prepare($sql);
            $query->execute();

            $num = $conn->query($sqlc)->fetchColumn();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if($num > 0){
                checker:
                foreach( $result as $checker){
                    if($checker[$column] != $trx ){
                        $trx_code = $trx;
                    }else{
                        $trx_code = $this->generatetrx();
                        goto checker;
                    }
                }
            }else{
                $trx_code = $trx;
            }
            
            // $return = Array(
            //     "voucher" => $trx_code
            // );
            
            // echo json_encode($return);
            return $trx_code;

        } catch (PDOException $e) {
            echo $e->getMessage();
        } 
    }
    public function cleanNumber($number){
        $patt = "/\s/";
        $patt2 = "/\D/";
        $patt3 = "/^0/";

        $cleannumber1 = preg_replace($patt, "", $number);
        $cleannumber2 = preg_replace($patt2, "", $cleannumber1);
        $finalclean = preg_replace($patt3, "234", $cleannumber2);
        
        return $finalclean;
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

            $sqlsite = "select wallet_balance from site where id = 1 limit 1";
            $querysite = $conn->prepare($sqlsite);
            $querysite->execute();
            $resultsite = $querysite->fetchAll();
        
            foreach($resultsite as $rowsite){
                $sitecurrent_balance = $rowsite['wallet_balance'];
            }

            $sitenewBalance = $sitecurrent_balance - $amount;

            $sqlfin = "UPDATE `site` SET `wallet_balance` = '$sitenewBalance' where id = 1";
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

            $sqlsite = "select wallet_balance from site where id = 1 limit 1";
            $querysite = $conn->prepare($sqlsite);
            $querysite->execute();
            $resultsite = $querysite->fetchAll();
        
            foreach($resultsite as $rowsite){
                $sitecurrent_balance = $rowsite['wallet_balance'];
            }

            $sitenewBalance = (int) $sitecurrent_balance + $amount;

            $sqlfin = "UPDATE `site` SET `wallet_balance` = '$sitenewBalance' where id = 1";
            $queryfin = $conn->prepare($sqlfin);
            $queryfin->execute();

        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $newBalance;
    }

    public function checkTransactionFeasibility($conn, $userId, $amount){

        try{
            $sqlchk = "select wallet_balance from users where track_id = '$userId' limit 1";
            $querychk = $conn->prepare($sqlchk);
            $querychk->execute();
            $resultchk = $querychk->fetchAll();
            $countchk = count($resultchk);

            if($countchk == 1){
                foreach($resultchk as $rowchk){
                    $walletBalance = $rowchk['wallet_balance'];
                }

                if($walletBalance < $amount){
                    $return = Array(
                        "status" => "failed",
                        "message" => "Insufficient Balance. Fund your wallet to carry out this Transaction"
                    );

                    goto resolution;
                }else{
                    $return = Array(
                        "status" => "success",
                        "message" => "Amount is sufficient. Transaction can be Successfully Carried out"
                    );

                    goto resolution;
                }
            }else{
                $return = Array(
                    "status" => "failed",
                    "message" => "User does not exist"
                );

                goto resolution;
            }
        }catch(PDOException $e){
            echo $e->getMessage(). " at ". $e->getLine(). " in " . $e->getFile(). " File";
        }

        resolution:

        return $return;
    }

    public function recordTransactions($conn, $transObj,string $reference,$category, string $paymentUrl = ""){

        $track_id =  $this->checktrackid($conn, "transactions");
        $status = "pending";

        if(gettype($transObj) == 'object'){
            $userId = $transObj->userId;
            $transactionType = $transObj->transactionType;
            $paymentChannel = $transObj->paymentChannel;
            $amount = $transObj->amount;
            $recipient = isset($transObj->recipient) ? $transObj->recipient : "";
            $face_amount = $transObj->face_amount;
            $charge = isset($transObj->charge) ? $transObj->charge : 0;
            $status = isset($transObj->status) ? $transObj->status : "pending";
            $verified = isset($transObj->verified) ? $transObj->verified : "no";
            $type = isset($transObj->type) ? $transObj->type : "Generic Product";
            $description = isset($transObj->description) ? $transObj->description : "";
        }else if(gettype($transObj) == "array"){

            $userId = $transObj["userId"];
            $transactionType = $transObj["transactionType"];
            $paymentChannel = $transObj["paymentChannel"];
            $amount = $transObj["amount"];
            $face_amount = $transObj["face_amount"];
            $charge = isset($transObj["charge"]) ? $transObj["charge"] : 0;
            $recipient = isset($transObj["recipient"]) ? $transObj["recipient"] : "";
            $status = isset($transObj["status"]) ? $transObj["status"] : "pending";
            $verified = isset($transObj["verified"]) ? $transObj["verified"] : "no";
            $type = isset($transObj["type"]) ? $transObj["type"] : "Generic Product";
            $description = isset($transObj["description"]) ? $transObj["description"] : "";
        }else{
            return "Body object type not recognized";
        }

        $json_transObj = json_encode($transObj);

        $now = new DateTime();
        $curd = $now->format("D, d M Y H:i:s");


        try {
            $sqluser = "select * from users where track_id = '$userId' limit 1";
            $queryuser = $conn->prepare($sqluser);
            $queryuser->execute();
            $resultuser = $queryuser->fetchAll();
            $countresult = count($resultuser);

            foreach($resultuser as $rowuser){
                $userEmail = $rowuser['email'];
                $customerCode = $rowuser['virtual_customer_code'];
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
        try{

            $sql = "INSERT INTO `transactions`(`track_id`, `user_id`, `user_email`, `customer_code`, `reference`, `description`, `transaction_type`,`recipient`, `payment_channel`,`request_obj`,`product_patronized`, `amount`,`face_amount`, `charge`, `status`, `category`, `trnx_date`,`payment_url`, `verified`) 
            VALUES ('$track_id', '$userId', '$userEmail', '$customerCode', '$reference', '$description', '$transactionType','$recipient', '$paymentChannel','{$json_transObj}','$type', '$amount','$face_amount', '$charge', '$status','$category', '$curd', '$paymentUrl', '$verified')";
            $query = $conn->prepare($sql);
            $query->execute();

        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        return $track_id;
    }

    public function updateTransaction($conn, $trnxObj,$dataObj, $existing){

        $status = $trnxObj["status"];

        if($trnxObj["status"] == 'success'){
            
            $tnxId = isset($trnxObj["id"]) ? $trnxObj["id"] : "";

            $verified = "yes";
            $debited = $this->debitWallet($conn, $dataObj->amount, $dataObj->userId);
            $profit = (int) $trnxObj["amount"] - (int) $trnxObj["face_amount"];

        }else{
            $tnxId = "";
            $verified = "no";
            $profit = 0;
        }

        $tObj = json_encode($trnxObj);


        try {
            $sql = "UPDATE `transactions` SET `transaction_id`='$tnxId',`status`='$status',`transaction_object`= '${tObj}',`verified`='$verified',`profit`='$profit' WHERE track_id = '$existing'";
            $query = $conn->prepare($sql);
            $query->execute();

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function checkSiteDeficit($conn){

        try {
            $sqlsum = "select sum(wallet_balance) total from users";
            $querysum = $conn->prepare($sqlsum);
            $querysum->execute();
            $resultsum = $querysum->fetchAll(PDO::FETCH_OBJ);

            $totalusersbalance = $resultsum[0]->total;

            $sqlsite = "select wallet_balance as sitebalance from site";
            $querysite = $conn->prepare($sqlsite);
            $querysite->execute();
            $resultsite = $querysite->fetchAll(PDO::FETCH_OBJ);

            $sitebalance = $resultsite[0]->sitebalance;

            $deficit = $totalusersbalance - $sitebalance;
            
            return $deficit;


        } catch (PDOException $e) {
            $e->getMessage(). " in line ". $e->getLine(). " in file ". $e->getFile();
        }
    }

    public function creditReferral($conn, $refid){

        try{
            $sqlchkref = "select * from referrals where referred_id = '$refid'";
            $querychkref = $conn->prepare($sqlchkref);
            $querychkref->execute();
            $resultchkref = $querychkref->fetchAll();
            $countref = count($resultchkref);

            $fixedAmount = 100;

            if($countref > 0){
                foreach($resultchkref as $rowchkref){
                    $first_payment = $rowchkref['first_payment'];
                    $status = $rowchkref['status'];
                    $referrer = $rowchkref['referrer_id'];
                }

                $track_id =  $this->checktrackid($conn, "transactions");
                
                if($status == 'unredeemed'){
                    
                    {
                        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');
                        $this->creditWallet($conn, $fixedAmount, $referrer);
                        $transObj = Array(
                            "userId" => $referrer,
                            "transactionType" => "referrals",
                            "paymentChannel" => "referrals",
                            "amount" => $fixedAmount,
                            "currency" => "NGN",
                            "charge" => "0.00",
                            "status" => "success",
                            "verified" => "yes"
                        );

                        $this->recordTransactions($conn, $transObj, $reference, "credit");

                    }

                    {
                        $reference = $this->checktrxcode($conn, "transactions", $column = 'reference');
                        $this->debitWallet($conn, $fixedAmount, $refid);
                        $transObj = Array(
                            "userId" => $refid,
                            "transactionType" => "referrals",
                            "paymentChannel" => "referrals",
                            "amount" => $fixedAmount,
                            "currency" => "NGN",
                            "charge" => "0.00",
                        );

                        $this->recordTransactions($conn, $transObj, $reference, "debit");
                    }

                    $sqlupref = "UPDATE referrals SET `status`='redeemed', `first_payment` = 'redeemed' where referred_id = '$refid'";
                    $queryupref = $conn->prepare($sqlupref);
                    $queryupref->execute();


                    $return = Array(
                        "status" => "success",
                        "message" => "Referral Bonus Redeemed Successfully"
                    );
                    

                    
                }else{
                    $return = Array(
                        "status" => "success",
                        "message" => "Referral bonus has already being Redeemed"
                    );
                }
            }else{
                $return = Array(
                    "status" => "error",
                    "message" => "User was not referred"
                );

                goto resolution;
            }
        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        resolution:
        // echo json_encode($return);
    }

}
