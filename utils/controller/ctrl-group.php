<?php

  include_once "../model/attendant.php";

  $attendantController = new AttendantController();
  $attendantController->verifyData();

  class AttendantController{

    function verifyData(){
      if(isset($_POST['valor'])){
        $this->findAttendantsByGroup($_POST['valor']);
      }
    }

    function findAttendantsByGroup($group){
      $attendant = new Attendant();
      $attendants = $attendant->findAttendants($group);

      $qtd_attendants = count($attendants);

      for ($i = 0; $i < $qtd_attendants; $i++) { 
          $id = $attendants[$i]['id'];
          $name = $attendants[$i]['name'];
          $option = utf8_encode("<option value='$id'>$name</option>");

          echo $option;
        }
    }

    function getInstance(){
      return new ClientController();
    }

  }
?>