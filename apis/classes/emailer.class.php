<?php
ignore_user_abort(true);
set_time_limit(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

include_once "functions.class.php";

class Emailer{

    private $support = Array("support@akfemtopup.com.ng");
    private $mailpassword = "r!1lWR}g#?Qa";
    private $mailhost = "mail.akfemtopup.com.ng";
    private $origin = "app.akfemtopup.com.ng";

    public function pickRandomemail($pickfrom){

        $endlimit = count($this->$pickfrom) - 1;
        $randomemail = rand(0,$endlimit);

        return $this->$pickfrom[$randomemail];
    }

    public function emailPayment($fields, $origin){
        $senderemail = $this->pickRandomemail("noreply");

        $name = $fields['name'];
        $ref = $fields["reference"];
        $email = $fields["email"];

        $emaildiv = <<<Div
            <div style="background-color: rgb(248, 248, 248);padding:20px;text-align:center">
                <div class="img">
                    <p><img src="https://usequicktest.com/images/Logo_blue.png" style="height: 50px;width: 200px"></p>
                </div>
                
                <div style="background-color: white;padding:20px;border-top:3px solid #E1800E;font-size:15px;text-align: left;">
                    <h5>Hello $name</h5>
                    <p>You are one step away to becoming a Premium Quicktester! Click on the button below to Verify Your Payment.</p>
                    <p>Do this if you were not automatically Redirected to the Verify Page<p>
                    <div style="text-align: center">
                        <a style="display:inline-block; width:150px;border-radius:10px 10px;padding:10px;margin-bottom:20px;margin-top:10px;text-align:center;background-color:#E1800E;color:white;text-decoration:none;font-size:medium;font-weight:bold;" 
                        href="$origin/verify/?reference=$ref"> Verify Payment </a>    
                    </div>
                    <hr>

                    <p>Or if You Can't see the button above. Click on this Link <a href="$origin/verify/?reference=$ref">Verify Payment</a></p>
                    
                </div>

                <div class="footer" style="text-align:left; margin-top:40px; color:grey">
                    This Message was sent to {$email}. If you think you that's not you and someone may be using your email address. Please tell us so we can delete the email
                </div>

                <div class="uns" style="text-align:left; color:gray;margin-top: 20px">
                    <a href="https://usequicktest.com/mail/?uei={$email}">Stop receiving Emails from Quicktest</a>
                </div>

            </div>
Div;

        try {

            $mail = new PHPMailer(true);

            $mail->Host = 'smtp.hostinger.com';
            $mail->isSMTP();
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            //$mail ->SMTPDebug = 2;
            $mail->Username = "$senderemail";
            $mail->Password = $this->mailpassword;
            $mail->SMTPSecure = 'ssl';
            $mail->setFrom("$senderemail", "Quicktest");
            $mail->addAddress("{$email}");
            $mail->addReplyTo("quicktestng@gmail.com");

            $mail->isHTML(true);
            $mail->Subject = "Verify Payment Here";

            $mail->Body = $emaildiv;
            //$mail ->AltBody = "Please Enable HTML Email!";

            if (!$mail->send()) {
                echo "Not sent" . $mail->ErrorInfo;
            } else {

                //echo "";
            }
        } catch (Exception $e) {
            //echo "Could Not Send Mail.<br> Check Your Internet Connection<br>". $e->getMessage();
        }
    }
    public function sendRtoken($email, $rtoken){
        $senderemail = $this->pickRandomemail("support");

        try {
            $mail = new PHPMailer(true);

            $mail->Host = "$this->mailhost";
            $mail->isSMTP();
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            // $mail->SMTPDebug = 2;
            $mail->Username = "$senderemail";
            $mail->Password = $this->mailpassword;
            $mail->SMTPSecure = 'ssl';
            $mail->setFrom("$senderemail", "Akfemtopup");
            $mail->addAddress("{$email}");
            $mail->Subject = "Password Reset Link";
            $mail->isHTML(true);
            

            $mail->Body = <<<Div

                <div style="background-color: rgb(248, 248, 248);padding:20px;text-align:center">
                    
                    
                    <div style="background-color: white;padding:20px;border-top:3px solid #E1800E;font-size:15px;text-align: left;">
                        <h4>One Step Away!</h4>
                        <p>Click on the button below to Reset your Password.</p>
                        <div style="text-align: center">
                            <a style="display:inline-block; width:150px;border-radius:10px 10px;padding:10px;margin-bottom:20px;margin-top:10px;text-align:center;background-color:#E1800E;color:white;text-decoration:none;font-size:medium;font-weight:bold;" href="{$this->origin}/passwordreset/?email=$email&token=$rtoken"> Reset Password  </a>    
                        </div>
                        <hr>

                        <p>Or if You Can't see the button above. Click on this Link <a href="{$this->origin}/passwordreset/?email=$email&token=$rtoken">Reset Password</a></p>
                        
                    </div>

                    <div class="footer" style="text-align:left; margin-top:40px; color:grey">
                        This Message was sent to {$email}. If you think you that's not you and someone may be using your email address. Please tell us so we can delete the email
                    </div>

                </div>
Div;
            $mail->AltBody = "Please Enable HTML Email!";

            if (!$mail->send()) {
                // echo "Not sent" . $mail->ErrorInfo;
            } else {
                return true;
            }
        } catch (Exception $e) {
            // echo "Could Not Send Mail.<br> Check Your Internet Connection<br>" . $e->getMessage();
        }

    }

    public function sendemail($track_id, $email, $origin){
        $senderemail = $this->pickRandomemail("support");

        $emaildiv = <<<Div
        <div style="background-color: rgb(248, 248, 248);padding:20px;text-align:center">
               
                <div style="background-color: white;padding:20px;border-top:3px solid #E1800E;font-size:15px;text-align: left;">
                    <h4>One Step Away!</h4>
                    <p>Hello, please confirm your email Address. Just click on the button below to confirm Your email address.</p>
                    <div style="text-align: center">
                        <a style="display:inline-block; width:150px;border-radius:10px 10px;padding:10px;margin-bottom:20px;margin-top:10px;text-align:center;background-color:#E1800E;color:white;text-decoration:none;font-size:medium;font-weight:bold;" href="$origin/login/?cid=$track_id"> Confirm Email </a>    
                    </div>
                    <hr>

                    <p>Or if You Can't see the button above. Click on this Link <a href="$origin/login/?cid=$track_id">Confirm Email Address</a></p>
                    
                </div>

                <div class="footer" style="text-align:left; margin-top:40px; color:grey">
                    This Message was sent to {$email}. If you think you that's not you and someone may be using your email address. Please tell us so we can delete the email
                </div>

            </div>
Div;

        try {

            $mail = new PHPMailer(true);

            $mail->Host = "$this->mailhost";
            $mail->isSMTP();
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            // $mail ->SMTPDebug = 2;
            $mail->Username = "$senderemail";
            $mail->Password = $this->mailpassword;
            $mail->SMTPSecure = 'ssl';
            $mail->setFrom("$senderemail", "Akfemtopup");
            $mail->addAddress("{$email}");
         

            $mail->isHTML(true);
            $mail->Subject = "Confirm your Email";

            $mail->Body = $emaildiv;
      

            if (!$mail->send()) {
                echo "Not sent" . $mail->ErrorInfo;
            } else {

  
            }
        } catch (Exception $e) {
            // echo "Could Not Send Mail.<br> Check Your Internet Connection<br>". $e->getMessage();
        }
    }


}