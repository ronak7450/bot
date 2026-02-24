<?php
// Telegram Bot Token
$botToken = "8363494373:AAGTxGa6EMZtJxGmqt4EeEouDpKhnftkg9c";
$website = "https://api.telegram.org/bot".$botToken;

// Get the update from Telegram
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

if (!$update || !isset($update["message"])) {
    exit;
}

$chatId = $update["message"]["chat"]["id"];
$message = isset($update["message"]["text"]) ? trim($update["message"]["text"]) : "";

// Basic "start" command
if ($message == "/start") {
    $welcomeMessage = "Welcome to *Ronak Hacker Bot* 💻\n\nSend a 10-digit mobile number to search details.";
    sendMessage($chatId, $welcomeMessage);
    exit;
}

// Clean and validate mobile number
$mobile = preg_replace('/[^0-9]/', '', $message);

if (strlen($mobile) == 10) {
    sendMessage($chatId, "🔍 *DECRYPTING SECURE DATABASE...*");

    // Fetch details directly from Worker API
    $api_url = "https://num.proportalxc.workers.dev/?mobile=".$mobile;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Validate if data is found
    $isNotFound = (!isset($data['data']) || 
                  (isset($data['data']['success']) && $data['data']['success'] === false) || 
                  (isset($data['data']['results']) && count($data['data']['results']) === 0));

    if ($isNotFound) {
        $errorMsg = "❌ *DATA NOT FOUND*\n\n[SYSTEM STATUS: TARGET SEARCH FAILED]\n[ALERT] NO RECORDS CORRESPONDING TO THIS NUMBER WERE LOCATED IN THE SECURE DATABASE.";
        sendMessage($chatId, $errorMsg);
    } else {
        $results = $data['data']['results'];
        $displayStr = ">>> TARGET DATA DECRYPTED SUCCESSFULLY <<<\n\n";
        
        foreach ($results as $index => $item) {
            $num = $index + 1;
            $displayStr .= "[RESULT #$num]\n";
            $displayStr .= "NAME          : " . ($item['name'] ?: '') . "\n";
            $displayStr .= "FATHER NAME   : " . ($item['father_name'] ?: '') . "\n";
            $displayStr .= "MOBILE        : " . ($item['mobile'] ?: '') . "\n";
            $displayStr .= "ALT MOBILE    : " . ($item['alt_mobile'] ?: '') . "\n";
            $displayStr .= "ADDRESS       : " . ($item['address'] ?: '') . "\n";
            $displayStr .= "CIRCLE        : " . ($item['circle'] ?: '') . "\n";
            $displayStr .= "AADHAAR NO.   : " . ($item['aadhaar_number'] ?: '') . "\n";
            $displayStr .= "EMAIL         : " . ($item['email'] ?: '') . "\n";
            $displayStr .= "------------------------------------------\n\n";
        }
        
        $displayStr .= "[SYSTEM STATUS: SEARCH COMPLETE]\n[BYPASS CREATED BY RONAK]";
        sendMessage($chatId, $displayStr);
    }
} else if ($message) {
    sendMessage($chatId, "⚠️ *Error:* Please enter a valid 10-digit mobile number.");
}

// Function to send message to Telegram using CURL (More stable than file_get_contents)
function sendMessage($chatId, $message) {
    global $website;
    $url = $website."/sendMessage";
    $post_fields = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
