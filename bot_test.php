<?php
// SIMPLE TEST BOT FOR INFINITYFREE
$botToken = "8363494373:AAGTxGa6EMZtJxGmqt4EeEouDpKhnftkg9c";
$website = "https://api.telegram.org/bot".$botToken;

// Get update
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    
    // Very simple reply to check if script is being reached
    $text = "CONNECTED! YOUR BOT IS WORKING ON THIS HOSTING. ✅";
    
    file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode($text));
}
?>
