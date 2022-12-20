<?php
spl_autoload_register(function($class){
    //echo $class;
    include strtolower($class).".class.php";
});

class API extends Emailer{

    private $paystackkey = "sk_live_7044fae843ba3d9847e2b0cc7bfd4051dde2c502";
    private $arisetoken = "6ddea222106951c60a4c38fc1430ca28ff6ff2d1";

    private $simhosttoken = "P3bCYScKzfiRw7wgwdCNUbPMaNjR1OgcB1xnKkUezSddm1UH5Z";
    private $smartsmstoken = "ndhFo1JbD4uea1cNFnSg3ntm5cAe4tMYuJkJGgWIYdSIYXq1Cr";

    public function createCustomer($dataObj){

        $email = $dataObj["email"];
        $first_name = $dataObj["first_name"];
        $last_name = $dataObj["last_name"];
        $phone = $dataObj["phone"];

        $url = "https://api.paystack.co/customer";


        $fields = [
            "email" => $email,
            "first_name"=> $first_name,
            "last_name"=> $last_name,
            "phone" => $phone
        ];
        $fields_string = http_build_query($fields);
        //open connection

        $ch = curl_init();
        //set the url, number of POST vars, POST data

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->paystackkey",
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        //execute post

        $result = curl_exec($ch);
        return json_decode($result);   
    }
    public function virtualAcct($dataObj){
        
        $customer = $this->createCustomer($dataObj);

        $banks = ['wema-bank'];

    
        $url = "https://api.paystack.co/dedicated_account";


        $fields = [
            "customer" => $customer->data->customer_code,
            "first_name" => $dataObj["first_name"],
            "last_name" => $dataObj["last_name"],
            "phone" => $dataObj["phone"],
            "preferred_bank" => $banks[array_rand($banks)]
        ];


        $fields_string = http_build_query($fields);
        //open connection

        $ch = curl_init();

        //set the url, number of POST vars, POST data

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->paystackkey",
            "Cache-Control: no-cache",
        ));

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($ch);

        return Array(
            "account_details" => json_decode($result),
            "customer_details" => $customer
        );
    }

    public function _makePayment($dataObj){

        $fields = [
            'email' => $dataObj['email'],
            'amount' => $dataObj['amount'],
            'reference' => $dataObj['reference'],
        ];

        $fields_string = http_build_query($fields);

        $url = "https://api.paystack.co/transaction/initialize";
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->paystackkey",
            "Cache-Control: no-cache",
        ));
        //So that curl_exec returns the contents of the cURL; rather than echoing it

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array();
        
        //execute post
        $request = curl_exec($ch);
        $result = json_decode($request);
        // $this->emailPayment($fields, $origin);

        // print_r($result);

        $return = Array(
            "rurl" => $result->data->authorization_url
        );
        //$this->rurl = $result->data->authorization_url;

        echo json_encode($return);
    }

    public function _verifyPayment($reference){

        $result = array();
    
        // $url = 'https://api.paystack.co/transaction/verify/' . $reference;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $this->paystackkey",
                "Cache-Control: no-cache",
            ),
        ));

        $request = curl_exec($curl);
        curl_close($curl);

        if ($request) {
            $result = json_decode($request);
        }

        return $result;
    }

    public function _sendSMS($dataObj, $reference){

        $message = $dataObj->message;
        $senderId = $dataObj->senderId;
        $recipients = $dataObj->recipients;
        $routingConfig = $dataObj->routing;
        $type = $dataObj->type;

        
        
        $curl = curl_init();

        $url = "https://app.smartsmssolutions.com/io/api/client/v1/sms/";

        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "token=$this->smartsmstoken&sender=$senderId&to=$recipients&message=$message&type=$type&routing=$routingConfig&ref_id=$reference";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($resp);
    }

    public function makeTransfer($dataObj){
        
    }

    public function listBanks(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank?currency=NGN",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $this->paystackkey",
                "Cache-Control: no-cache",
            ),
        ));

        
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            return $response->data;
        }
    }

    public function verifyAccount($dataObj){
        $account_number = $dataObj->account_number;
        $bank_code = $dataObj->bank_code;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $this->paystackkey",
                "Cache-Control: no-cache",
            ),
        ));

        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);

        if($err){
            echo "cURL Error #:" . $err;
        }else{
            // print_r($response);

            if($response->status == true){
                $account_name = $response->data->account_name;
            }else{
                $account_name = "";
            }
            $return = Array(
                "status" => $response->status,
                "account_name" => $account_name
            );

            return $return;
        }
    }

    public function getBillerCategory(){
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://arisedata.com.ng/api/user/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Authorization: Token $this->arisetoken",
            "User-Agent: Thunder Client (https://www.thunderclient.com)"
        ],
        ]);

        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);

        curl_close($curl);

  
        return $response;
    }



    public function _validateBill($dataObj){

        $cableId = $dataObj->planId;
        $cable = $dataObj->cable;
        $recipient = $dataObj->recipient;

        $url = "https://arisedata.com.ng/api/validateiuc?smart_card_number=$recipient&cablename=$cable";
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://arisedata.com.ng/api/validateiuc?smart_card_number=4613794660&cablename=GOTV",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Authorization: Token 6ddea222106951c60a4c38fc1430ca28ff6ff2d1"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }

        //  $resp;
    }

    public function _createBill($dataObj, $reference){
        $curl = curl_init();

        $type = $dataObj->type;


        switch($type){
            case 'cable' :
                $postfields = "{\r\n  \"cablename\": {$dataObj->cableId},\r\n  \"cableplan\" :\"{$dataObj->planId}\", \r\n  \"smart_card_number\": \"{$dataObj->recipient}\"\r\n}";
                $url = "https://arisedata.com.ng/api/cablesub/";
            break;
            case "giftdata":
                $postfields = "{\n  \"mobile_number\": \"$dataObj->mobile_number\", \"network\": $dataObj->network,\n  \"type\": \"DATA\",\n  \"country\": \"NG\",\n  \"amount\": $dataObj->amount,\n  \"plan\": $dataObj->plan,\n  \"Ported_number\": $dataObj->Ported_number}";
                $url = "https://arisedata.com.ng/api/data/";
            break;
            case "disco":
                $postfields = "{\r\n  \"disco_name\": {$dataObj->disco_name},\r\n  \"amount\" :\"{$dataObj->amount}\", \r\n  \"meter_number\" :\"{$dataObj->meter_number}\", \r\n  \"MeterType\": \"{$dataObj->MeterType}\"\r\n}";
                $url = "https://arisedata.com.ng/api/billpayment/";
            break;
            default:
                $postfields = "";
                $url = "https://www.thunderclient.com/welcome";
            break;
        }


        curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Authorization: Token $this->arisetoken",
            "Content-Type: application/json",
            "User-Agent: Thunder Client (https://www.thunderclient.com)"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        return $response;
        }
    }

    public function _vendSME($dataObj, $reference){
      

        $token = $this->simhosttoken;
        $product = $dataObj->product;
        $recipient = $dataObj->recipient;
        $url = "https://zwiift.com/api/vend/mtn-sme/vend/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "token=$token&product=$product&phone=$recipient&refid=$reference&=";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        return $resp;

    }

    public function _vendAirtime($dataObj, $reference){
      

        $token = $this->simhosttoken;
        $amount = $dataObj->amount;
        $recipient = $dataObj->recipient;
        $url = "https://zwiift.com/api/vend/airtime/vend/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "token=$token&phone=$recipient&value=$amount&refid=$reference";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        return $resp;

    }

    public function _createRecipient($dataObj){
        $url = "https://api.paystack.co/transferrecipient";

        $fields = [
            'type' => "nuban",
            'name' => "$dataObj->account_name",
            'account_number' => "$dataObj->account_number",
            'bank_code' => "$dataObj->bank_code",
            'currency' => "NGN"
        ];

        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->paystackkey",
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        
        return json_decode($result);

    }

    public function _makeWithdrawal($dataObj){
        $url = "https://api.paystack.co/transfer";

        $amount = $dataObj->amount * 100;

        $fields = [
            'source' => "balance",
            'amount' => $amount,
            'recipient' => $dataObj->details->recipient_code,
            'reason' => "Funds Withdrawal"
        ];

        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->paystackkey",
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        return json_decode($result);
    }
}

