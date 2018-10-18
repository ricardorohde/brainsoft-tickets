<?php

	include_once("../model/city.php");

	$postReceived = $_POST; 

	if (isset($postReceived['valor'])){
		$state = $postReceived['valor'];

		$city = new City();
		$cities = $city->findCities($state);

		$info = utf8_encode("<option value=''>Selecione uma cidade...</option>");
    	echo $info;

    	for ($i=0; $i < count($cities); $i++) { 
    		$id = $cities[$i]['id'];
    		$desc = $cities[$i]['description'];
    		$option = "<option value='$id'>$desc</option>";
    		echo $option;
    	}
	}

?>