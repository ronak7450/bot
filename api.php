<?php
header("Content-Type: application/json");

if (!isset($_GET['mobile'])) {
    echo json_encode(["error"=>"Mobile missing"]);
    exit;
}

$mobile = preg_replace('/[^0-9]/', '', $_GET['mobile']);
$api_url = "https://num.proportalxc.workers.dev/?mobile=".$mobile;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$data['api_developer'] = "@ronak7450";
$data['api_developer_end'] = "@ronak7450";

if(isset($data['data']['metadata'])){
unset($data['data']['metadata']);
}

$data = array_merge(["api_created_by"=>"Ronak"],$data);

echo json_encode($data, JSON_PRETTY_PRINT);