<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
/*
*  CONFIGURATION
*/

  // Send email
if(isset($_POST["email"])) {
  if(!isset($_POST["email"]))
  {
      $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
      die($output);
  }
  else {
      $user_Email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
  }
}
if(isset($_POST["message"])) {
  if(!isset($_POST["message"]))
  {
      $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
      die($output);
  }
  else {
      $user_Message = htmlspecialchars($_POST["message"]);
      
  }
}
if(isset($_POST["subject"])) {
  if(!isset($_POST["subject"]))
  {
      $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
      die($output);
  }
  else {
      $feedback = htmlspecialchars($_POST["subject"]);
      
  }
}
if(isset($_POST["fullname"])) {
  if(!isset($_POST["fullname"]))
  {
      $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
      die($output);
  }
  else {
      $user_Name =  htmlspecialchars($_POST["fullname"]);
  }
}

// Recipients
$fromEmail = $user_Email; // Email address that will be in the from field of the message.
$fromName = $user_Name; // Name that will be in the from field of the message.
$sendToEmail = 'info@fppschools.com.ng'; // Email address that will receive the message with the output of the form
$sendToName = 'FPP Schools'; // Name that will receive the message with the output of the form

// Subject
$subject = 'New Contact Message';

// SMTP settings
$smtpUse = true; // Set to true to enable SMTP authentication
$smtpHost =  'mail.fppschools.com.ng'; // Enter SMTP host ie. smtp.gmail.com
$smtpUsername =  'esender@fppschools.com.ng'; // SMTP username ie. gmail address
$smtpPassword =  'IRENElof012345@'; // SMTP password ie gmail password
$smtpSecure = 'ssl'; // Enable TLS or SSL encryption
$smtpAutoTLS = false; // Enable Auto TLS
$smtpPort = 465; // TCP port to connect to

// Success and error alerts
$okMessage = 'We have received your message. Stay tuned, we’ll get back to you ASAP!';
$errorMessage = 'There was an error while submitting the form. Please try again later';


/*
*  LET'S DO THE SENDING
*/

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);
try {
  if(count($_POST) == 0) throw new \Exception('Form is empty');
  $emailTextHtml =  '
  <p>Hi there,</p>
                        <p>Someone just sent an enquiry</p>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Fullname</th>
                                            <th>Email Address</th>
                                            <th>Feedback Type</th>
                                            <th>Message</th>
                                            
                                        </tr>
                                    </thead>  
                                <tbody>
                                    <tr>
                                      <td> '.date("d-m-Y").'</td>
                                      <td> '.$user_Name.'</td>
                                      <td> '.$fromEmail.'</td>
                                      <td>'.$feedback.'</td>
                                      <td>'.$user_Message.'</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
  ';
  
  $mail = new PHPMailer;
  $mail->setFrom($fromEmail, $fromName);
  $mail->addAddress($sendToEmail, $sendToName);
  $mail->addReplyTo($fromEmail);
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Subject = $subject;
  $mail->Body    = $emailTextHtml;
  $mail->msgHTML($emailTextHtml);
  if($smtpUse == true) {
    // Tell PHPMailer to use SMTP
    $mail->isSMTP();
    // Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->Debugoutput = function ($str, $level) use (&$mailerErrors) {
      $mailerErrors[] = [ 'str' => $str, 'level' => $level ];
    };
    $mail->SMTPDebug = 3;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = $smtpSecure;
    $mail->SMTPAutoTLS = $smtpAutoTLS;
    $mail->Host = $smtpHost;
    $mail->Port = $smtpPort;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
  }
  if(!$mail->send()) {
    throw new \Exception('I could not send the email.' . $mail->ErrorInfo);
  }
  $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e) {
  $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}
// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  $encoded = json_encode($responseArray); 
  header('Content-Type: application/json');
  echo $encoded;
}
// else just display the message
else {
  echo $responseArray['message'];
}
