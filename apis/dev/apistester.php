<?php


        // $url = "https://api.paystack.co/customer";

        // $fields = [
        //     "email" => "promiseoomos@gmail.com",
        //     "first_name"=> "Promise",
        //     "last_name"=> "Omos",
        //     "phone" => "+2348123456789"
        // ];
        // $fields_string = http_build_query($fields);
        // //open connection
        // $ch = curl_init();
        // //set the url, number of POST vars, POST data

        // curl_setopt($ch,CURLOPT_URL, $url);
        // curl_setopt($ch,CURLOPT_POST, true);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     "Authorization: Bearer sk_live_36202f9e97fa46ca25b2119331a13603f0b58fdb",
        //     "Cache-Control: no-cache",
        // ));

        // //So that curl_exec returns the contents of the cURL; rather than echoing it
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        // //execute post
        // $result = curl_exec($ch);
        // echo $result;

        // $url = "https://api.paystack.co/customer/:code";


        // $fields = [
        //     'first_name' => "BoJack"
        // ];
        // $fields_string = http_build_query($fields);
        // //open connection
        // $ch = curl_init();

        // //set the url, number of POST vars, POST data
        // curl_setopt($ch,CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     "Authorization: Bearer SECRET_KEY",
        //     "Cache-Control: no-cache",
        // ));

        // //So that curl_exec returns the contents of the cURL; rather than echoing it

        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        // //execute post

        // $result = curl_exec($ch);
        // echo $result;



        // /* Create Custom Account */
        // $url = "https://api.paystack.co/dedicated_account";


        // $fields = [
        //     "customer" => "CUS_f9wogpt6hakcgzp",
        //     "first_name" => "Promise",
        //     "last_name" => "Omorotionmwan",
        //     "phone" => "0908727637847",
        //     "preferred_bank" => "wema-bank"
        // ];


        // $fields_string = http_build_query($fields);


        // //open connection

        // $ch = curl_init();

        // //set the url, number of POST vars, POST data

        // curl_setopt($ch,CURLOPT_URL, $url);

        // curl_setopt($ch,CURLOPT_POST, true);

        // curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(

        //     "Authorization: Bearer sk_live_36202f9e97fa46ca25b2119331a13603f0b58fdb",

        //     "Cache-Control: no-cache",

        // ));

        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        // $result = curl_exec($ch);

        // echo $result;

        // $curl = curl_init();

        // $fields = Array(
        //     "to" => Array("09020301539"),
        //     "body" => "You have been selected for a global challenge",
        //     "from" => "Quicktest",
        //     "notificationURL" => " https://webhook.site/3dd15858-787d-4636-a761-8be6457d3eaa",
        //     "clientId" => "5tlGRY1GKUnZNHoy"
        // );

        // $http_fields = json_encode($fields);

        // curl_setopt_array($curl, 
        // [
        //     CURLOPT_URL => "https://api.mtn.com/v1/messages/sms",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => $http_fields,
        //     CURLOPT_HTTPHEADER => [
        //         "Content-Type: application/json"
        //     ],
        // ]);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        // echo "cURL Error #:" . $err;
        // } else {
        // echo $response;
        // }
        
        // $fields = Array(
        // );

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.paystack.co/bank",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_POSTFIELDS => http_build_query($fields),
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "Authorization: Bearer sk_live_36202f9e97fa46ca25b2119331a13603f0b58fdb",
        //         "Cache-Control: no-cache",
        //     ),
        // ));

        
        // $response = json_decode(curl_exec($curl));
        // $err = curl_error($curl);
        // curl_close($curl);
        // // echo count($response);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {

        //     print_r($response);
        //     // echo $response;
        // }

        
// $curl = curl_init();

// $str = "IKIA9DCFA676F238A91A63DF3D6508851D5BFB0C5EEB:aoSmeSm5ASjvJR9";
// $client = base64_encode("IKIA9DCFA676F238A91A63DF3D6508851D5BFB0C5EEB");

// $based = base64_encode($str);


// curl_setopt_array($curl, [

//   CURLOPT_URL => "https://apps.qa.interswitchng.com/passport/oauth/token?grant_type=client_credentials",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_HTTPHEADER => [
//         "Accept: application/json",
//         "Authorization: Basic $based",
//         "Content-Type: application/x-www-form-urlencoded"
//   ],

// ]);


// $response = json_decode(curl_exec($curl));

// $err = curl_error($curl);


// curl_close($curl);


// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   print_r($response);
// }

// $access_token = $response->access_token;
// $timestamp = time();
// echo $timestamp;


// $curl = curl_init();


// curl_setopt_array($curl, [
//   CURLOPT_URL => "https://sandbox.interswitchng.com/api/v2/quickteller/categorys/1/billers",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_HTTPHEADER => [
//     "Accept: application/json",
//     "Content-Type: application/json",
//     "InterswitchAuth: null",
//     "TerminalID: 3DMO0001"
//   ],
// ]);


// $response = curl_exec($curl);

// $err = curl_error($curl);


// curl_close($curl);


// if ($err) {

//   echo "cURL Error #:" . $err;

// } else {
//   echo "<br><br><br/>";
//   echo $response;

// }

// require("Flutterwave-PHP-v3/library/Transactions.php");
// use Flutterwave\Transactions;

// $payment = new Bill();
// $getBillCategories = $payment->getBillCategories();



// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.flutterwave.com//v3/bills',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>'{
//     "amount": 50,
//     "biller_name": "AIRTEL 40 MB data bundle",
//     "country": "NG",
//     "customer": "09046646693",
//     "package_data": "DATA",
//     "recurrence": "ONCE",
//     "reference": "16062933142381",
//     "type": "AIRTEL 40 MB data bundle"
//   }',
//   CURLOPT_HTTPHEADER => array(
//     'Authorization: Bearer FLWSECK_TEST-3eae7634ec08b9db601aa06ea95a14d9-X',
//     'Content-Type: application/json'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// echo $response;



// try {
//   // $secret = config('setting.flutterwaveSecretKey');
//   $url = "https://api.flutterwave.com/v3/bill-categories";
//   $data = "{
//       'power' : 1,
//   }";
//   // if (!empty($amount)) {
//   //     $data['amount'] = $amount;
//   // }
//   $secret_key_test = "FLWSECK_TEST-3eae7634ec08b9db601aa06ea95a14d9-X";
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $secret_key_test]);
//   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "power" : "1" }');
//   curl_setopt($ch, CURLOPT_URL, $url);
//   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//   $response = curl_exec($ch);
//   curl_close($ch);
//   echo $response;
// } catch (\Exception $e) {
//   return $e->getMessage();
// }

// $public_key = "FLWPUBK_TEST-739753451dae65e6b5b5ab94c23dadbf-X";
// $secret_key = "FLWSECK_TEST-3eae7634ec08b9db601aa06ea95a14d9-X";
// $encryption_key = "FLWSECK_TEST6cb2424ad913";

// $public_key_test = "FLWPUBK_TEST-739753451dae65e6b5b5ab94c23dadbf-X";
// $secret_key_test = "FLWSECK_TEST-3eae7634ec08b9db601aa06ea95a14d9-X";
// $encryption_key_test = "FLWSECK_TEST6cb2424ad913";


$url = "https://api.flutterwave.com/v3/bill-categories";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Authorization: Bearer FLWSECK_TEST-3eae7634ec08b9db601aa06ea95a14d9-X",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);




		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

