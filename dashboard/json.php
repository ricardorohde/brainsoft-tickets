<?php

$chat = $_GET["chat"];

$ch_1 = curl_init();
$ch_2 = curl_init();

$url = 'http://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/'; 
$url2 = 'http://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/'.$chat; 

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

?>

<html>
<head>
<style>
body {
    margin: 0 auto;
    max-width: 800px;
    padding: 0 20px;
}

.container {
    border: 2px solid #dedede;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}

.darker {
    border-color: #ccc;
    background-color: #ddd;
}

.container::after {
    content: "";
    clear: both;
    display: table;
}

.container img {
    float: left;
    max-width: 40px;
    width: 100%;
    margin-right: 10px;
    border-radius: 50%;
}

.container img.right {
    float: right;
    margin-left: 20px;
    margin-right:0;
}


.time-right {
    float: right;
    color: #aaa;
}

.time-left {
    float: left;
    color: #999;
}

p {
    margin-bottom: 0px;
    margin-top: 10px;
}
</style>
</head>
<body>
    
<?php
    foreach ($data1 as $key => $value){
        if ($value->cod_chat == $chat){
            echo "<h2>".$value->cod_chat."</h2>";
            
            echo "<p>Atendente: ".$value->chat_atendente."</p>";
            echo "<p>Inicio: ".$value->chat_inicio."</p>";
            echo "<p>Final: ".$value->chat_final."</p>";
            echo "<p>Cliente: ".$value->cliente_nome."</p>";
            echo "<p>Email: ".$value->cliente_email."</p>";
            echo "<p>Telefone: ".$value->cliente_telefone."</p>";
            echo "<p>Nota: ".$value->chat_nota_atendimento."</p>";
            
        }
    }
?>

<div>

<h2>Chat Messages</h2>

<?php foreach ($data as $key => $value): $date = strtotime($value->timestamp);?>
    <?php if ($value->tipo == "M"): $type = "Sistema";?>
        <div style="display: inline-block;">
            <p><?php 
                    echo date('d/m/Y', $date)." às ". date('h:i:s', $date);
                ?>
            </p>
        </div>
        <div style="display: inline-block;">
            <p><?php 
                    echo " <b>".$type."</b> ".$value->texto; 
                ?>
            </p>
        </div>
    <?php elseif ($value->tipo == "A"): $type = "Atendente";?>
        <p><?php echo "<b>".$type."</b> ".$value->texto; ?></p>
    <?php else: $type = "Cliente";?>
        <p><?php echo "<b>".$type."</b> ".$value->texto; ?></p>
    <?php endif; ?>
<?php endforeach; ?>

</div>
</body>
</html>