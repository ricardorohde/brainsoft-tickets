<?php

class apiPct
{
	private $totalCustomersAtReception;
	private $dataCustomersAtReception;

	public function getTotalCustomersAtReception()
	{
	  return $this->totalCustomersAtReception;
	}

	public function getDataCustomersAtReception()
	{
	  return $this->dataCustomersAtReception;
	}

	function __construct()
	{
		$this->setTimezone();
	}

	function setTimezone()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}

	function getCustomersAtReception()
	{
		$ch = curl_init();
		$url = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/date/';

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		$this->dataCustomersAtReception = json_decode($response);
	}

	function countCustomersAtReception()
	{
		$this->totalCustomersAtReception = 0;

		foreach ($this->dataCustomersAtReception as $key => $value) {
		    if ($value->chat_atendente == "camila" && $value->chat_final == NULL && $value->departamento == "Camila") {
		    	$this->totalCustomersAtReception++;
		    }
		}
	}

	function toStringTotalCustomersAtReception()
	{
		$totalToString = "Nenhum Cliente";

		if ($this->totalCustomersAtReception == 1) {
  			$totalToString = $this->totalCustomersAtReception . " Cliente";
  		} else {
  			$totalToString = $this->totalCustomersAtReception . " Clientes";
  		}
  		return $totalToString;
	}
}
