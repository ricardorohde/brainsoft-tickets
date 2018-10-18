<?php 

date_default_timezone_set('America/Sao_Paulo');
$day = date('d');
$month = date('m');
$year = date('Y');

$customers_at_reception = 0;

$ch_1 = curl_init();

$url = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/date/'.$year."/".$month."/".$day;

curl_setopt($ch_1, CURLOPT_URL, $url);
curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

$response_1 = curl_exec($ch_1);

curl_close($ch_1);

$data1 = json_decode($response_1);

foreach ($data1 as $key => $value){
    if ($value->departamento == "Camila" && $value->chat_nota_atendimento == null){
    	$customers_at_reception++;
    } else{
    	$customers_at_reception = $customers_at_reception;
    }
}

?>