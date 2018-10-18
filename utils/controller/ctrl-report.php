<?php
  include_once __DIR__.'/../../commom/config.php';

  $reportController = new ReportController($_POST['makeReport'], $_POST['initial_date'], $_POST['final_date']);
  $reportController->verifyData();

  class ReportController{
    protected $idClient;
    protected $initialDate;
    protected $finalDate;

    protected $connection;

    public function getIdClient(){
        return $this->idClient;
    }
    public function setIdClient($idClient){
        $this->idClient = $idClient;
    }
    public function getInitialDate(){
        return $this->initialDate;
    }
    public function setInitialDate($initialDate){
        $this->initialDate = $initialDate;
    }
    public function getFinalDate(){
        return $this->finalDate;
    }
    public function setFinalDate($finalDate){
        $this->finalDate = $finalDate;
    }
    public function getConn(){
        return $this->connection;
    }
    public function setConn($conn){
        $this->connection = $conn;
    }

    function __construct($idClient, $initialDate, $finalDate) {
      $this->setIdClient($idClient);
      $this->setInitialDate($initialDate." 00:00:01");
      $this->setFinalDate($finalDate." 23:59:59");
      $this->setConn(new ConfigDatabase());
    }

    function verifyData(){
      if (isset($_POST['makeReport'])){
        $this->make();
      }
    }

    function make(){
      $id = $this->getIdClient();

      $sql = $this->connection->getConnection()->prepare("SELECT name, id_registry FROM client WHERE id = ?");
      $sql->execute(array($this->getIdClient())); $client = $sql->fetch(); $id_r = $client['id_registry'];

      $sql_count_tickets = $this->connection->getConnection()->prepare("SELECT COUNT(*) as total
        FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ?");
      $sql_count_tickets->execute(array($id_r, $this->getInitialDate(), $this->getFinalDate())); $qtd_tickets = $sql_count_tickets->fetch(); 
      $total_tickets = $qtd_tickets['total'];

      $sql_name_registry = $this->connection->getConnection()->prepare("SELECT name FROM registry WHERE id = ?");
      $sql_name_registry->execute(array($id_r)); $name_of_registry = $sql_name_registry->fetch();

      $sql_id_chats = $this->connection->getConnection()->prepare("SELECT id_chat FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ?");
      $sql_id_chats->execute(array($id_r, $this->getInitialDate(), $this->getFinalDate())); $id_chats = $sql_id_chats->fetchAll();

      $total_minutes = 0;
      $biggest_time = 0;
      foreach ($id_chats as $key) {
        $sql_minutes = $this->connection->getConnection()->prepare("SELECT duration_in_minutes FROM chat WHERE id = ? ORDER BY duration_in_minutes");
        $sql_minutes->execute(array($key['id_chat'])); $sql_in_minutes = $sql_minutes->fetch();

        $total_minutes += $sql_in_minutes['duration_in_minutes'];
        if($sql_in_minutes['duration_in_minutes'] > $biggest_time){
          $biggest_time = $sql_in_minutes['duration_in_minutes'];
        }
      }

      $sql_ticket_id_module = $this->connection->getConnection()->prepare("SELECT count(*) as NrVezes, id_module FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ? GROUP BY id_module ORDER BY NrVezes DESC LIMIT 1");
      $sql_ticket_id_module->execute(array($id_r, $this->getInitialDate(), $this->getFinalDate())); $row_ticket_id_module = $sql_ticket_id_module->fetch();

      $sql_ticket_module = $this->connection->getConnection()->prepare("SELECT description FROM ticket_module WHERE id = ?");
      $sql_ticket_module->execute(array($row_ticket_id_module['id_module'])); $row_ticket_module = $sql_ticket_module->fetch();

      $sql_ticket_id_client = $this->connection->getConnection()->prepare("SELECT count(*) as NrVezes, id_client FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ? GROUP BY id_client ORDER BY NrVezes DESC LIMIT 3");
      $sql_ticket_id_client->execute(array($id_r, $this->getInitialDate(), $this->getFinalDate())); $row_ticket_id_client = $sql_ticket_id_client->fetchAll();

      $top3_client = array("","","");

      foreach ($row_ticket_id_client as $key => $value) {
        $sql_client = $this->connection->getConnection()->prepare("SELECT name FROM client WHERE id = ?");
        $sql_client->execute(array($value['id_client'])); $row_sql_client = $sql_client->fetch();
      
        if($key != 0){
          $row_sql_client['name'] = ", ".($key+1)."º ".$row_sql_client['name'];
        } else{
          $row_sql_client['name'] = ($key+1)."º ".$row_sql_client['name'];
        }

        $top3_client[$key] = $row_sql_client['name'];
      }

      //---------------------------------------
      $top3_ticket_client = array("","","");
      foreach ($row_ticket_id_client as $key => $value) {
        $sql_ticket_of_client = $this->connection->getConnection()->prepare("SELECT COUNT(*) as total
          FROM ticket WHERE id_client = ? AND registered_at BETWEEN ? AND ?");
        $sql_ticket_of_client->execute(array($value['id_client'], $this->getInitialDate(), $this->getFinalDate())); $row_ticket_count_client = $sql_ticket_of_client->fetch();

        $top3_ticket_client[$key] = $row_ticket_count_client['total'];
      }

      date_default_timezone_set('America/Sao_Paulo');
      if($qtd_tickets['total'] > 0){
        $option = "
          <br>
          <br>
          <h1 id='title_of_report'>Relatório de Atentimentos - ".$name_of_registry['name']."</h1>
          <span id='filter_period'>Período: ".date('d/m/Y', strtotime($_POST['initial_date'])).' à '.date('d/m/Y', strtotime($_POST['final_date']))."</span>
          <span id='requester_of_report'>Solicitante: ".$client['name']."</span>
          <span id='date_of_report'>Formalizado em: ".date('d/m/Y H:i:s')."</span>
          <br>
          <div class='table-responsive'>
            <table id='example' class='table'>
              <tbody>
                <tr>
                  <th scope='row'>Total de chats realizados</th>
                  <td>".$total_tickets."</td>
                </tr>
                <tr>
                  <th scope='row'>Maior tempo em conversação</th>
                  <td>".$this->convertData($biggest_time)."</td>
                </tr>
                <tr>
                  <th scope='row'>Tempo total em conversação</th>
                  <td>".$this->convertData($total_minutes)."</td>
                </tr>
                <tr>
                  <th scope='row'>Módulo com maior incidência</th>
                  <td>".$row_ticket_module['description']."</td>
                </tr>
                <tr>
                  <th scope='row'>Funcionários requerentes (Top 3)</th>
                  <td>".$top3_client[0]."".$top3_client[1]."".$top3_client[2]."</td>
                </tr>
              </tbody>
            </table>
          </div>
          <br><br>
          <div>
            <h4>Detalhamento</h4><br>
            <div class='table-responsive'>
              <table id='details' class='table'>
                <tbody>
                  <tr>
                    <th>Funcionário</th>
                    <th>Número de chats requisitados</th>
                  </tr>
                  <tr>
                    <td scope='row'>".substr($top3_client[0], 3)."</td>
                    <td>".$top3_ticket_client[0]."</td>
                  </tr>
                  <tr>
                    <td scope='row'>".substr($top3_client[1], 5)."</td>
                    <td>".$top3_ticket_client[1]."</td>
                  </tr>
                  <tr>
                    <td scope='row'>".substr($top3_client[2], 7)."</td>
                    <td>".$top3_ticket_client[2]."</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          ";
      } else{
        $option = "
          <br>
          <br>
          <h1 id='title_of_report'>Relatório de Atentimentos - ".$name_of_registry['name']."</h1>
          <span id='filter_period'>Período: ".date('d/m/Y', strtotime($_POST['initial_date'])).' à '.date('d/m/Y', strtotime($_POST['final_date']))."</span>
          <span id='requester_of_report'>Solicitante: ".$client['name']."</span>
          <span id='date_of_report'>Formalizado em: ".date('d/m/Y H:i:s')."</span>
          <br>
          <div class='table-responsive'>
            <table id='example' class='table'>
              <tbody>
                <tr>
                  <th scope='row'>Não há dados para o período selecionado</th>
                </tr>
              </tbody>
            </table>
          </div>
          ";
      }

      echo $option;
    }

    function setSession($result){
      session_start();
      if ($result == 1) {
        $_SESSION['resultSaveFiles'] = "<strong>Sucesso!</strong> Boleto(s) registrado(s) com êxito.";
      } else{
        $_SESSION['thereIsProblemInSaveFiles'] = "<strong>Erro 802!</strong> Problema ao salvar boleto(s) no banco de dados.";
      }
       
      header("Location:/novo-site/dashboard/cadastros");
    }

    function convertData($time){
      if ($time >= 60){
        $hour = floor($time / 60);
        $minutes = $time % 60;

        if (strlen($hour) < 2){
          $hour = "0" . $hour;
        }
        if (strlen($minutes) < 2){
          $minutes = "0" . $minutes;
        }

        return $hour . ":" . $minutes . " (". $hour . " horas e " . $minutes ." minutos)";
      } else {
        if (strlen($time) < 2){

          return "00:0" . $time . " (" . $time . " minutos)";
        } else {

          return "00:" . $time . " (" . $time . " minutos)";
        }
      }
    }

    function getInstance(){
      return new ReportController();
    }
  }
?>