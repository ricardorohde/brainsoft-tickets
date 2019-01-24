<?php 

date_default_timezone_set('America/Sao_Paulo');
$day = date('d');
$month = "12";
$year = "2019";

$_SESSION["id_chat"] = $_GET["id_chat"];
$chat = $_SESSION["id_chat"];

$client_ip = "";
$attendant = "";
$client = "";
$start = "";
$final = "";
$rating = "";
$opening_time = "";
$transfer_time = "";
$attendant_time_after_transfer = "";

$customers_at_reception = 0;

$ch_1 = curl_init();
$ch_2 = curl_init();

$url = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/date/';
$url2 = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/'.$chat; 

curl_setopt($ch_1, CURLOPT_URL, $url);
curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch_2, CURLOPT_URL, $url2);
curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);

$mh = curl_multi_init();

curl_multi_add_handle($mh, $ch_1);
curl_multi_add_handle($mh, $ch_2);
  
$running = null;

do {
    curl_multi_exec($mh, $running);
} while ($running);

curl_multi_remove_handle($mh, $ch_1);
curl_multi_remove_handle($mh, $ch_2);
curl_multi_close($mh);
  
$response_1 = curl_multi_getcontent($ch_1);
$response_2 = curl_multi_getcontent($ch_2);

$data1 = json_decode($response_1);
$data = json_decode($response_2);

foreach ($data1 as $key => $value){
    if ($value->cod_chat == $chat){
      $client_ip = $value->cliente_ip;
      $attendant = $value->chat_atendente;
      $client = $value->cliente_nome;
      $start = $value->chat_inicio;
      $final = $value->chat_final;
      $rating = $value->chat_nota_atendimento;
    }

    if ($value->chat_atendente == "camila" && $value->departamento == "Camila"){
      $customers_at_reception++;
    }
}

function convertData($time){
  if ($time >= 60){
    $hour = floor($time / 60);
    $seconds = $time % 60;

    if (strlen($hour) < 2){
      $hour = "0" . $hour;
    }
    if (strlen($seconds) < 2){
      $seconds = "0" . $seconds;
    }

    return $hour . ':' . $seconds;
  } else {
    if (strlen($time) < 2){

      return "00:0" . $time;
    } else {

      return "00:" . $time;
    }
  }
}

?>