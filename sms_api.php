<?php
// sms_api.php
session_start();
header('Content-Type: application/json');

require_once 'send_sms_impl.php';
require_once './dto/send_text_body.php';
require_once './dto/token_body.php';

// Read POST input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['mobiles']) || empty($input['message'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'mobiles and message are required']);
    exit;
}

$mobiles = $input['mobiles']; // array of phone numbers as strings
$message = $input['message'];
$transactionId = $input['transaction_id'] ?? time();

$sendSmsImpl = new SendSMSImpl();
$tokenBody = new TokenBody();
$tokenBody->setUsername("Nuwan1995");
$tokenBody->setPassword("1995@Nuwan");

// Function to get valid token and refresh if expired
function getValidToken($sendSmsImpl, $tokenBody) {
    if (isset($_SESSION['sms_token'])) {
        $token = $_SESSION['sms_token'];
    } else {
        $token = $sendSmsImpl->getToken($tokenBody)->getToken();
        $_SESSION['sms_token'] = $token;
    }

    // Test token validity
    $testResponse = $sendSmsImpl->getToken($tokenBody);
    if ($testResponse->getComment() === "Authentication Token Expired") {
        $token = $sendSmsImpl->getToken($tokenBody)->getToken();
        $_SESSION['sms_token'] = $token;
    }

    return $_SESSION['sms_token'];
}

// Function to send SMS
function sendSMS($sendSmsImpl, $mobiles, $message, $transactionId, $token) {
    $sendTextBody = new SendTextBody();
    $sendTextBody->setSourceAddress("NipponLanka"); // Approved mask
    $sendTextBody->setMessage($message);
    $sendTextBody->setTransactionId($transactionId);
    $sendTextBody->setMsisdn($sendSmsImpl->setMsisdns($mobiles));

    $response = $sendSmsImpl->sendText($sendTextBody, $token);

    $data = $response->getData();
    $failedNumbers = $data ? $data->getInvalidNumbers() : [];

    return [
        'status' => $response->getStatus(),
        'comment' => $response->getComment(),
        'error_code' => $response->getErrCode(),
        'failed_numbers' => $failedNumbers,
        'transaction_id' => $transactionId
    ];
}

// Get valid token
$token = getValidToken($sendSmsImpl, $tokenBody);

// Send SMS
$result = sendSMS($sendSmsImpl, $mobiles, $message, $transactionId, $token);

// Return response
echo json_encode($result, JSON_PRETTY_PRINT);
?>
