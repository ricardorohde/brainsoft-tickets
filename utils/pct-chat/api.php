<?php

class ApiPct
{
	private $totalCustomersAtReception;
	private $dataCustomersAtReception;

	private $day;
	private $month;
	private $year;
	private $idChatInSession;
	private $ipClient;
	private $attendant;
	private $client;
	private $start;
	private $final;
	private $rating;
	private $transferedAt;
	private $dataOfAllChats;
	private $dataOfEspecificChat;

	public function getTotalCustomersAtReception()
	{
	  return $this->totalCustomersAtReception;
	}

	public function getDataCustomersAtReception()
	{
	  return $this->dataCustomersAtReception;
	}

	public function getDay() 
	{
		return $this->day;
	}

	public function getMonth() 
	{
		return $this->month;
	}

	public function getYear() 
	{
		return $this->year;
	}

	public function getIdChatInSession() 
	{
		return $this->idChatInSession;
	}

	public function getIpClient() 
	{
		return $this->ipClient;
	}

	public function getAttendant() 
	{
		return $this->attendant;
	}

	public function getClient()
	{
		return $this->client;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function getFinal()
	{
		return $this->final;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function getDataOfAllChats() 
	{
		return $this->dataOfAllChats;
	}

	public function getDataOfEspecificChat() 
	{
		return $this->dataOfEspecificChat;
	}

	function __construct()
	{
		$this->defineTimezone();
		$this->defineDay();
		$this->defineMonth();
		$this->defineYear();
	}

	function defineTimezone()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}

	function consultCustomersAtReception()
	{
		$ch = curl_init();
		$url = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats';

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
  		} 

  		if ($this->totalCustomersAtReception > 1) {
  			$totalToString = $this->totalCustomersAtReception . " Clientes";
  		}
  		return $totalToString;
	}

	function defineDay()
	{
		$this->day = date('d');
	}

	function defineMonth()
	{
		$this->month = date('m');
	}

	function defineYear()
	{
		$this->year = date('Y');
	}

	function assignIdChatInSession($id)
	{
		$_SESSION["id_chat"] = $id;
		$this->idChatInSession = $id;
	}

	function convertData($time)
	{
		if ($time >= 60) {
			$hour = floor($time / 60);
	    	$seconds = $time % 60;

	    	if (strlen($hour) < 2) {
	      		$hour = "0" . $hour;
	    	}
	    	if (strlen($seconds) < 2) {
	      		$seconds = "0" . $seconds;
	    	}
			return $hour . ':' . $seconds;
	  	} else {
	    	if (strlen($time) < 2) {
	      		return "00:0" . $time;
	    	} else {
	      		return "00:" . $time;
	    	}
	  	}
	}

	function consultAllChats($date)
	{
		$ch1 = curl_init();
		$ch2 = curl_init();

		$urlAllChats = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/date/' . $date;
		$urlEspecificChat = 'https://guilherme:aAoYdUycs71B5GfdfmqKRwaXUSr6iO50WiAuksHwbQzc7T4bH1eFVZvMBNqTG4px@brainsoft.meupct.com/api/chats/'.$this->idChatInSession; 

		curl_setopt($ch1, CURLOPT_URL, $urlAllChats);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch2, CURLOPT_URL, $urlEspecificChat);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

		$mh = curl_multi_init();

		curl_multi_add_handle($mh, $ch1);
		curl_multi_add_handle($mh, $ch2);
		  
		$running = null;

		do {
		    curl_multi_exec($mh, $running);
		} while ($running);

		curl_multi_remove_handle($mh, $ch1);
		curl_multi_remove_handle($mh, $ch2);
		curl_multi_close($mh);
		  
		$responseOfAllChats = curl_multi_getcontent($ch1);
		$responseOfEspecificChat = curl_multi_getcontent($ch2);

		$this->dataOfAllChats = json_decode($responseOfAllChats);
		$this->dataOfEspecificChat = json_decode($responseOfEspecificChat);
	}

	function putFeaturesOfChatInVariables()
	{
		foreach ($this->dataOfAllChats as $key => $value) {
		    if ($value->cod_chat == $this->idChatInSession) {
		    	$this->ipClient = $value->cliente_ip;
		      	$this->attendant = $value->chat_atendente;
		      	$this->client = $value->cliente_nome;
		      	$this->start = $value->chat_inicio;
		      	$this->final = $value->chat_final;
		      	$this->rating = $value->chat_nota_atendimento;
		    }
		}
	}
}
