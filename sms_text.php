<?php
session_start();

require_once 'send_sms_impl.php';
require_once './dto/send_text_body.php';
require_once './dto/token_body.php';
require_once './dto/transaction_body.php';

$sendSmsImpl = new SendSMSImpl();  
$sendTextBody = new SendTextBody();  
$tokenBody = new TokenBody();  

$tokenBody->setUsername("Nuwan1995");  
$tokenBody->setPassword("1995@Nuwan");  

// Get token (you can also save it to session)
$token = $sendSmsImpl->getToken($tokenBody)->getToken();

$_SESSION['sms_token'] = $token;

// Prepare SMS data
$sendTextBody->setSourceAddress("NipponLanka");  // Approved mask
$mobiles = ["94762797637","94762797637"];
$sendTextBody->setMsisdn($sendSmsImpl->setMsisdns($mobiles));  
$sendTextBody->setTransactionId("146");  
$sendTextBody->setMessage("Hi this is test from PHP");  

// Send SMS
$sendTextResponse = $sendSmsImpl->sendText($sendTextBody, $token);

// Get response data
$userId = $sendTextResponse->getData()->getUserId();
$status = $sendTextResponse->getStatus();
$comment = $sendTextResponse->getComment();

echo "Status: $status\n";
echo "Comment: $comment\n";
echo "User ID: $userId\n";
?>
