<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type, Accept, Cache-Control, Pragma, *");

spl_autoload_register(function($class){
    //echo $class;
    include_once strtolower($class).".class.php";
});

include_once "functions.class.php";



class Operations extends Functions{
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

    public function getTransactions($conn, $userId){
        try {
            $sql = "select * from transactions where user_id = '$userId' order by date_made desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $reference = $row['reference'];
                    $transactionType = $row['transaction_type'];
                    $amount = $row['amount'];
                    $currency = $row['currency'];
                    $status = $row['status'];
                    $trnxDate = $row['trnx_date'];
                    $trackId = $row['track_id'];
                    $verified = $row['verified'];
                    $recipient = $row['recipient'];
                    $category = $row['category'];

                    $data[] = Array(
                        "reference" => $reference,
                        "transactionType" => $transactionType,
                        "amount" => $amount,
                        "currency" => $currency,
                        "status" => $status,
                        "trnxDate" => $trnxDate,
                        "trackId" => $trackId,
                        "recipient" => $recipient,
                        "verified" => $verified,
                        "category" => $category
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved Transactions",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched Transactions",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function getPayments($conn, $userId){

        try {
            $sql = "select * from transactions where transaction_type LIKE '%payment%' and user_id = '$userId' order by date_made desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $reference = $row['reference'];
                    $transactionType = $row['transaction_type'];
                    $amount = $row['amount'];
                    $currency = $row['currency'];
                    $status = $row['status'];
                    $trnxDate = $row['trnx_date'];
                    $trackId = $row['track_id'];
                    $verified = $row['verified'];
                    $category = $row['category'];
                    $recipient = $row['recipient'];

                    $data[] = Array(
                        "reference" => $reference,
                        "transactionType" => $transactionType,
                        "amount" => $amount,
                        "currency" => $currency,
                        "status" => $status,
                        "trnxDate" => $trnxDate,
                        "trackId" => $trackId,
                        "verified" => $verified,
                        "recipient" => $recipient,
                        "category" => $category
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved payments",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched payments",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function getDeposits($conn, $userId){
        try {
            $sql = "select * from transactions where transaction_type LIKE '%deposit%' and user_id = '$userId' order by date_made desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $reference = $row['reference'];
                    $transactionType = $row['transaction_type'];
                    $amount = $row['amount'];
                    $currency = $row['currency'];
                    $status = $row['status'];
                    $trnxDate = $row['trnx_date'];
                    $trackId = $row['track_id'];
                    $verified = $row['verified'];
                    $category = $row['category'];

                    $data[] = Array(
                        "reference" => $reference,
                        "transactionType" => $transactionType,
                        "amount" => $amount,
                        "currency" => $currency,
                        "status" => $status,
                        "trnxDate" => $trnxDate,
                        "trackId" => $trackId,
                        "verified" => $verified,
                        "category" => $category
                        
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved payments",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched payments",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function getSMSMessages($conn, $userId){
        try {
            $sql = "select * from smsmessages where user_id = '$userId' order by date_sent desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $reference = $row['reference'];
                    $subject = $row['subject'];
                    $message = $row['message'];
                    $senderId = $row['sender_id'];
                    $status = $row['status'];
                    $amount = $row['amount'];
                    $recipients = $row['recipients'];
                    $trackId = $row['track_id'];
                    $date_sent = $row['date_sent'];

                    $date = new DateTime("$date_sent");
                    $day = $date->format("d, M Y");
                    $time = $date->format("H:i a");

                    $data[] = Array(
                        "reference" => $reference,
                        "subject" => $subject,
                        "message" => $message,
                        "senderId" => $senderId,
                        "status" => $status,
                        "amount" => $amount,
                        "recipients" => $recipients,
                        "trackId" => $trackId,
                        "date_sent" => $day,
                        "time_sent" => $time
                        
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved payments",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched payments",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }

    public function getReferrals($conn, $userId){
        try{
            $sql = "select * from referrals where referrer_id = '$userId' order by date_referred desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            if($count > 0){
                foreach($result as $row){
                    $referred_id = $row['referred_id'];
                    $dateReferred = $row['referred_date'];
                    $firstPayment = $row['first_payment'];
                    $status = $row['status'];

                    try {
                        $sqlrefer = "select * from users where track_id = '$referred_id'";
                        $queryrefer = $conn->prepare($sqlrefer);
                        $queryrefer->execute();
                        $resultrefer = $queryrefer->fetchAll();
                        $countrefer = count($resultrefer);

                        if($countrefer > 0) {
                            foreach($resultrefer as $rowrefer){
                                $userName = $rowrefer['first_name']." ".$rowrefer['last_name'];
                            }
                        }

                        $data[] = Array(
                            "name" => $userName,
                            "dateReferred" => $dateReferred,
                            "firstPayment" => $firstPayment,
                            "status" => $status,
                            "amount" => 100
                        );
                    } catch (PDOException $e) {
                        echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
                    }
                }

                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved Referrals for user",
                    "data" => $data
                );

            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved Referrals for user",
                    "data" => Array() 
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }

        echo json_encode($return);
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

    public function getServices($conn){
        try {
            $sql = "select * from services order by created_date desc";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);

            // print_r($result);

            if($count > 0){
                foreach($result as $row){
                    $standard_name = $row['standard_name'];
                    $display_name = $row['display_name'];
                    $module = $row['module'];
                    $submodule = $row['submodule'];
                    $normal_price = $row['normal_user_price'];
                    $face_price = $row['api_price'];
                    $face_percentage = $row["api_percentage"];
                    $status = $row['status'];
                    $reseller_price = $row['reseller_user_price'];
                    $pricing = $row['pricing'];
                    $reseller_percentage = $row['reseller_user_percentage'];
                    $normal_percentage = $row['normal_user_percentage'];
                    $trackId = $row['track_id'];
                    $module_prefix = $row['module_prefix'];
                    $module_suffix = $row['module_suffix'];

                    $data[] = Array(
                        "standard_name" => $standard_name,
                        "display_name" => $display_name,
                        "module" => $module,
                        "submodule" => $submodule,
                        "normal_price" => $normal_price,
                        "face_price" => $face_price,
                        "face_percentage" => $face_percentage,
                        "status" => $status,
                        "reseller_price" => $reseller_price,
                        "pricing" => $pricing,
                        "reseller_percentage" => $reseller_percentage,
                        "normal_percentage" => $normal_percentage,
                        "track_id" => $trackId,
                        "module_prefix" => $module_prefix,
                        "module_suffix" => $module_suffix
                        
                    );
                }


                $return = Array(
                    "status" => "success",
                    "message" => "Successfully retrieved Services",
                    "data" => $data
                );
            }else{
                $return = Array(
                    "status" => "success",
                    "message" => "Successfully fetched Services",
                    "data" => Array()
                );
            }

            echo json_encode($return);

        } catch (PDOException $e) {
            echo $e->getMessage() . " From Line " . $e->getLine() . " in " . $e->getFile();
        }
    }
}