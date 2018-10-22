<?php 
date_default_timezone_set('America/Sao_Paulo');
$day = date('d');
$month = date('m');
$year = date('Y');

$ch_1 = curl_init();

$url = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/date/';

curl_setopt($ch_1, CURLOPT_URL, $url);
curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

$response_1 = curl_exec($ch_1);

curl_close($ch_1);

$data1 = json_decode($response_1);
?>