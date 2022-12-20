<?php

require_once "functions.class.php";

class Admin extends Functions {

    public function loginAdmin($conn, $data){
        $email = $data->email;
        $password = $data->password;

        if (empty($email) || empty($password)) {
            $response = array(
                "status" => false,
                "message" => "Password and Email Fields cannot be left Empty",
                "reason" => "empty"
            );

            goto resolution;
        }

        try {
            $sql = "select * from site where owner_email = '{$email}'";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();

            $hashpassword = '';

            foreach ($result as $row) {
                $hashpassword = $row['owner_password'];
                $track_id = $row['track_id'];

                $newdetails = array(
                    "first_name" => $row['owner_fname'],
                    "last_name" => $row['owner_lname'],
                    "fullname" => $row['owner_fname'] . " " . $row['owner_lname'],
                    "email" => $row['owner_email'],
                    "phone" => $row['owner_number'],
                    "site_name" => $row['site_name'],
                    "virtual_account_name" => $row['virtual_account_name'],
                    "virtual_account_number" => $row['virtual_account_number'],
                    "virtual_account_bank" => $row['virtual_account_bank'],
                    "customer_code" => $row["customer_code"],
                    "currency" => $row['currency'],
                    "wallet_balance" => $row['wallet_balance'],
                    "account_name" => $row['account_name'],
                    "account_number" => $row['account_number'],
                    "account_bank" => $row['account_bank'],
                    "recipient_code" => $row['recipient_code'],
                    "track_id" => $row['track_id'],
                );
            }

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
    public function getSiteDetails($conn){
        
        try{
            $sqlsite = "select * from site limit 1";
            $querysite = $conn->prepare($sqlsite);
            $querysite->execute();
            $resultsite = $querysite->fetchAll();
            $countsite = count($resultsite);

            if($countsite > 0){
                foreach ($resultsite as $row) {
                        
                    $newdetails = array(
                        "first_name" => $row['owner_fname'],
                        "last_name" => $row['owner_lname'],
                        "fullname" => $row['owner_fname'] . " " . $row['owner_lname'],
                        "email" => $row['owner_email'],
                        "phone" => $row['owner_number'],
                        "site_name" => $row['site_name'],
                        "virtual_account_name" => $row['virtual_account_name'],
                        "virtual_account_number" => $row['virtual_account_number'],
                        "virtual_account_bank" => $row['virtual_account_bank'],
                        "customer_code" => $row["customer_code"],
                        "currency" => $row['currency'],
                        "wallet_balance" => $row['wallet_balance'],
                        "account_name" => $row['account_name'],
                        "account_number" => $row['account_number'],
                        "account_bank" => $row['account_bank'],
                        "recipient_code" => $row['recipient_code'],
                        "track_id" => $row['track_id'],
                    );
                  
                }

                $response = array(
                    "status" => true,
                    "message" => "Details gotten successfully",
                    "data" => $newdetails,
                );
            }else{
                $response = array(
                    "status" => false,
                    "message" => "Could not fetch site details",
                    "data" => Array(),
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage() . " from line " . $e->getLine() . " in ". $e->getFile();
        }

        echo json_encode($response);
    }
    public function getUserAccounts($conn){
        try {
            $sql = "select * from users order by date_joined desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $name = $row['first_name'] ." ". $row['last_name'];
                    $userId = $row['track_id'];
                    $email = $row['email'];
                    $currency = $row['currency'];
                    $status = $row['status'];
                    $phone = $row['phone'];
                    $type = $row['type'];
                    $createdDate = $row['date_joined'];
                    $virtualAcctno = $row['virtual_account_no'];
                    $virtualBank = $row['virtual_account_bank'];
                    $walletBalance = $row['wallet_balance'];

                    $data[] = Array(
                        "name" => $name,
                        "userId" => $userId,
                        "email" => $email,
                        "currency" => $currency,
                        "status" => $status,
                        "phone" => $phone,
                        "type" => $type,
                        "virtualAcct" => "$virtualAcctno($virtualBank)",
                        "createdDate" => $createdDate,
                        "walletBalance" => $walletBalance
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved Users",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched Users",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function validateUserEmail($conn, $email){
        try {
            $sqlchk = "select first_name, last_name from users where email = '$email'";
            $querychk = $conn->prepare($sqlchk);
            $querychk->execute();
            $result = $querychk->fetchAll();
            $count = count($result);

            if($count == 1){
                foreach($result as $row){
                    $name = $row['last_name']. " ".$row['first_name'];
                }
                
                $return = Array(
                    "status" => true,
                    "message" => "Account Exists",
                    "userName" => $name
                );
            }else if($count > 1){
                $return = Array(
                    "status" => false,
                    "message" => "Duplicate account with this email exists"
                );
            }else{
                $return = Array(
                    "status" => false,
                    "message" => "Account with this email does not exists"
                );
            }
        } catch (PDOException $e) {
            echo $e->getMEssage(). " in line ". $e->getLine(). " from file " . $e->getFile(); 
        }

        echo json_encode($return);
    }

    public function updateSiteDetails($conn, $dataObj){
        
        $ownerfName = $dataObj->first_name;
        $ownerlName = $dataObj->last_name;
        $siteName = $dataObj->site_name;
        $ownerPhone = $dataObj->phone;

        try {
            $sqlUpdate = "UPDATE `site` SET `owner_fname`='$ownerfName',`owner_lname`='$ownerlName',`owner_number`='$ownerPhone',`site_name`='$siteName' WHERE track_id = 'siteowner'";
            $queryUpdate = $conn->prepare($sqlUpdate);
            $queryUpdate->execute();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $return = array(
            "status" => true,
            "message" => "Account Successfully updated"
        );

        echo json_encode($return);
    }

    public function updateSiteBank($conn, $dataObj){
        
        
        $bankName = $dataObj->bank_name;
        $accountName = $dataObj->account_name;
        $account_number = $dataObj->account_number;
        $bankCode = $dataObj->bank_code;

        $createRecipient = $this->_createRecipient($dataObj);

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

        try {
            $sqlUpdate = "UPDATE `site` SET `account_name`='$accountName',`account_number`='$account_number',`account_bank`='$bankName', `bank_code` = '$bankCode', `recipient_code`='$recipient_code' WHERE track_id = 'siteowner'";
            $queryUpdate = $conn->prepare($sqlUpdate);
            $queryUpdate->execute();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $return = array(
            "status" => true,
            "message" => "Account Successfully updated"
        );

        resolution:
        echo json_encode($return);
    }

    public function creditUserAccount($conn, $dataObj){
        $email = $dataObj->recipient;
        $amount = $dataObj->amount;
        $description = $dataObj->description;

        $reference = $this->checktrxcode($conn, "transactions", "reference");

        $deficit = $this->checkSiteDeficit($conn);

        if($amount > $deficit){
            $return = Array(
                "status" => false,
                "message" => "Amount is greater than Site deficit. User cannot be credited"
            );
        }else{
            try {
                $sqlget = "select wallet_balance, track_id as userId from users where email = '$email' limit 1";
                $queryget = $conn->prepare($sqlget);
                $queryget->execute();
                $resultget = $queryget->fetchAll(PDO::FETCH_OBJ);
                $countget = count($resultget);

                if($countget <= 0){
                    $return = Array(
                        "status" => false,
                        "message" => "Account does not exist"
                    );

                    goto resolution;
                }else{
                    $currentBalance = $resultget[0]->wallet_balance;
                    $userId = $resultget[0]->userId;

                    $newBalance = $currentBalance + $amount;

                    $sqlput = "update `users` set wallet_balance = '$newBalance' where email = '$email'";
                    $queryput = $conn->prepare($sqlput);
                    $queryput->execute();

                    $return = Array(
                        "status" => true,
                        "message" => "Account has been successfully Credited with ₦$amount"
                    );

                    $transObj = Array(
                        "userId" => $userId,
                        "transactionType" => "credit-account",
                        "paymentChannel" => "website-admin",
                        "amount" => $amount,
                        "charge" => 0,
                        "status" => "success",
                        "verified" => "yes",
                        "description" => $description
                    );

                    $this->recordTransactions($conn,$transObj,$reference, "credit");
                    goto resolution;

                }
                
            } catch (PDOException $e) {
                echo $e->getMessage(). " in Line ". $e->getLine(). " in File ". $e->getFile();
            }
        }

        resolution:
        echo json_encode($return);
    }

    public function updateServices($conn, $dataObj){
        
        try {
             
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function profitAnalysis($conn){

        $profit = Array();
        $alltimewithdrawn = Array();
        $profitbalance = Array();

        $profitAnalysis = Array();

        
        try {
            $sqltrx = "select transaction_type as name, sum(amount) total from transactions where verified = 'yes' group by transaction_type order by date_made desc";
            $querytrx = $conn->prepare($sqltrx);
            $querytrx->execute();
            $resulttrx = $querytrx->fetchAll();
            $counttrx = count($resulttrx);

            $profitAnalysis["totals"] = $resulttrx;


            $sqlprofits = "select sum(profit) profits from transactions where verified = 'yes' order by date_made desc";
            $queryprofits = $conn->prepare($sqlprofits);
            $queryprofits->execute();
            $resultprofits = $queryprofits->fetchAll();
            $countprofits = count($resultprofits);

            $profitAnalysis["totalProfits"] = $resultprofits[0]["profits"];


            $sqlusers = "select sum(wallet_balance) total from users where email_verified = 'yes' order by last_name desc";
            $queryusers = $conn->prepare($sqlusers);
            $queryusers->execute();
            $resultusers = $queryusers->fetchAll();
            $countusers = count($resultusers);

            $profitAnalysis["userWallets"] = $resultusers[0]["total"];

            $sqlsite = "select wallet_balance from site";
            $querysite = $conn->prepare($sqlsite);
            $querysite->execute();
            $resultsite = $querysite->fetchAll();
            $countsite = count($resultsite);

            $profitAnalysis["siteBalance"] = $resultsite[0]["wallet_balance"];

            $withdrawable = $resultsite[0]["wallet_balance"] - $resultusers[0]["total"];
            $withdrawable = $withdrawable < 0 ? 0 : $withdrawable;

            $profitAnalysis["withdrawableBalance"] = round($withdrawable);
                 
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $return = Array(
            "status" => "success",
            "message" => "Fetched Profit Analysis successfully",
            "data" => $profitAnalysis
        );

        echo json_encode($return);
    }

    public function getTransactions($conn){
        
        $profitAnalysis = Array();

        
        try {
            $sqltrx = "select * from transactions where verified = 'yes' order by date_made desc";
            $querytrx = $conn->prepare($sqltrx);
            $querytrx->execute();
            $resulttrx = $querytrx->fetchAll(PDO::FETCH_ASSOC);
            $counttrx = count($resulttrx);

            if($counttrx > 0){
                foreach($resulttrx as $key => $rowtrx){
                    $userId = $rowtrx["user_id"];

                    $sqluser = "select * from users where track_id = '$userId'";
                    $queryuser = $conn->prepare($sqluser);
                    $queryuser->execute();
                    $resultuser = $queryuser->fetchAll(PDO::FETCH_OBJ);

                    $resulttrx[$key]["userDetails"] = $resultuser;
                }
            }
                 
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $return = Array(
            "status" => "success",
            "message" => "Fetched Transactions successfully",
            "data" => $resulttrx
        );

        echo json_encode($return);
    }

    public function createSiteAccount($conn, $data){
        $virtualacctname = "";$virtualacctno = "";$virtualacctbank = "";

        $vacctobj = Array(
            "email" => $data->email,
            "first_name" => $data->first_name,
            "last_name" => $data->last_name,
            "phone" => $data->phone
        );

        try{
            $sqlchk =<<<SQLite
                select * from site where id = 1
SQLite;
            $querychk = $conn->prepare($sqlchk);
            $querychk->execute();
            $resultchk = $querychk->fetchAll(PDO::FETCH_ASSOC);
            
            if(strlen($resultchk[0]['customer_code']) > 0){
                
                $return = Array(
                    "status" => true,
                    "message" => "Admin Already has an Account"
                );

                goto resolution;
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        

        $virtualacct = $this->virtualAcct($vacctobj);
        
        if($virtualacct["account_details"]->status){

            $virtualacctname = $virtualacct["account_details"]->data->account_name;
            $virtualacctno = $virtualacct["account_details"]->data->account_number;
            $virtualacctbank = $virtualacct["account_details"]->data->bank->name;
        }
        if($virtualacct['customer_details']->status){
            $customercode = $virtualacct['customer_details']->data->customer_code;
        }
    

        try {
            $sql =<<<SQLite
                UPDATE `site` SET `virtual_account_name`="$virtualacctname",`virtual_account_bank`="$virtualacctbank",`virtual_account_number`="$virtualacctno",`customer_code`="$customercode" WHERE id = 1
SQLite;
            $query = $conn->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        try {
            $sqluser =<<<SQLite
            UPDATE `users` SET `virtual_account_name`="$virtualacctname",`virtual_account_bank`="$virtualacctbank",`virtual_account_no`="$virtualacctno",`virtual_customer_code`="$customercode" WHERE track_id = 'siteowner'
SQLite;
            $queryuser = $conn->prepare($sqluser);
            $queryuser->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        

        if ($query) {
            $return = Array(
                "status" => true,
                "message" => "Admin Account Created Successfully"
            );

            goto resolution;
        }

        resolution:
        echo json_encode($return);

    }

    public function updateService($conn, $data){

        try {
            $sqlUpdate = "UPDATE `services` SET `display_name`='{$data->display_name}',`normal_user_price`='{$data->normal_price}',`reseller_user_price`='{$data->reseller_price}', `reseller_user_percentage`='{$data->reseller_percentage}',`normal_user_percentage`='{$data->normal_percentage}', `status`='{$data->status}', `pricing`='{$data->pricing}' WHERE track_id = '$data->track_id'";
            $queryUpdate = $conn->prepare($sqlUpdate);
            $queryUpdate->execute();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $return = array(
            "status" => true,
            "message" => "Service Successfully updated"
        );

        echo json_encode($return);
    }
}