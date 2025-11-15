<?php



require_once 'SendSMSImpl.php';
require_once 'TokenBody.php';
 
$sendSmsImpl = new SendSMSImpl();
 
$tokenBody = new TokenBody();
$tokenBody->setUsername('<YOUR USERNAME>'); // Replace with your username
$tokenBody->setPassword('<YOUR PASSWORD>'); // Replace with your password

// Step 3: Get the token
$tokenResponse = $sendSmsImpl->getToken($tokenBody);

// Step 4: Retrieve values from TokenResponse
$token = $tokenResponse->getToken();
$comment = $tokenResponse->getComment();
$status = $tokenResponse->getStatus();
$remainingCount = $tokenResponse->getRemainingCount();
$expiration = $tokenResponse->getExpiration();
$refreshToken = $tokenResponse->getRefreshToken();
$refreshExpiration = $tokenResponse->getRefreshExpiration();
$errorCode = $tokenResponse->getErrCode();

// Step 5: Retrieve user data
$userData = $tokenResponse->getUserData();
$walletBalance = $userData->getWalletBalance();
$defaultMask = $userData->getDefaultMask();
$email = $userData->getEmail();
$mobile = $userData->getMobile();
$address = $userData->getAddress();
$lastName = $userData->getLname();
$firstName = $userData->getFname();
$id = $userData->getId();
$additionalMasks = $userData->getAdditionalMask();

// Step 6: Output results
echo "Token: $token\n";
echo "Status: $status\n";
echo "Remaining SMS: $remainingCount\n";
echo "Wallet Balance: $walletBalance\n";
echo "Default Mask: $defaultMask\n";
echo "Email: $email\n";
echo "Mobile: $mobile\n";
echo "First Name: $firstName\n";
echo "Last Name: $lastName\n";

if (!empty($additionalMasks)) {
    echo "Additional Mask: " . $additionalMasks[0]['mask'] . "\n";
}

?>
