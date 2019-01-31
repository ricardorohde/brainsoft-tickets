<?php
include_once("../model/employee.php");

$employeeController = new EmployeeController();
$employeeController->verifyData();

class EmployeeController
{
    function verifyData()
    {
        if (isset($_POST['valor']) && !empty($_POST['valor'])) {
            $this->findAttendantsByGroup($_POST['valor']);
        } 
    }

    function registerCtrl($data)
    {
        $name = utf8_decode($data['name']);
  
        $employee = new Employee($this->getInstance());

        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $employee->setTGroup($data['group']);
        $employee->setIdCredential($data[0]);
        $employee->setIdRole($data['role']);
        $result = $employee->register();

        $this->verifyResult("register", $result);
    }

    function updateCtrl($data)
    {
        $employee = new Employee($this->getInstance());

        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $employee->setTGroup($data['group']);
        $employee->setIdRole($data['role']);
        $employee->setId($data['id_user']);
        $employee->update();

        $this->verifyResult("update", $result);
    }

    function findAttendantsByGroup($group)
    {
        $employee = new Employee($this->getInstance());

        $employee->setOnChat("yes");
        $employee->setTGroup($group);
        $employees = $employee->findAttendants();
  
        $qtd_attendants = count($employees);

        for ($i = 0; $i < $qtd_attendants; $i++) { 
            $id = $employees[$i]['id'];
            $name = $employees[$i]['name'];
            $option = utf8_encode("<option value='$id'>$name</option>");

            echo $option;
        }
    }

    function isOnChat($idCredential, $status)
    {
        $employee = new Employee($this->getInstance());

        $employee->setOnChat($status);
        $employee->setIdCredential($idCredential);
        $statusChat = $employee->turnOn();

        $this->verifyResult("onChat", $statusChat);
    }

    function verifyResult($action, $result)
    {
        switch ($action) {
            case "register":
                if ($result == 1) {
                    $this->setSession('newOk');
                } else {
                    $this->setSession('newNo');
                }
                break;
            case "update":
                if ($result == 1) {
                    $this->setSession('updateOk');
                } else {
                    $this->setSession('updateNo');
                }
                break;
            case "onChat":
                if ($result == 1) {
                    $this->setSession("onChatOk");
                } else {
                    $this->setSession("onChatNo");
                }
                break;
            default:
                break;
        }
    }

    function setSession($status)
    {
        session_start();

        unset($_SESSION['userOk']);
        unset($_SESSION['userNo']);

        switch ($status) {
            case "newOk":
                $_SESSION['userOk'] = "<strong>Sucesso!</strong> Funcionário registrado com êxito.";
                header("Location:../../dashboard/usuarios");
                break;
            case "updateOk":
                $_SESSION['userOk'] = "<strong>Sucesso!</strong> Funcionário atualizado com êxito.";
                header("Location:../../dashboard/usuarios");
                break;
            case "onChatOk":
                $_SESSION['onChatOk'] = "<strong>Sucesso!</strong> Você está operante na fila interna.";
                header("Location:../../dashboard");
                break;
            case "newNo":
                $_SESSION['userNo'] = "<strong>Erro!</strong> Houve um problema ao registrar este funcionário.";
                header("Location:../../dashboard/usuarios");
                break;
            case "updateNo":
                $_SESSION['userNo'] = "<strong>Erro!</strong> Houve um problema ao atualizar este funcionário.";
                header("Location:../../dashboard/usuarios");
                break;
            case "onChatNo":
                $_SESSION['onChatNo'] = "<strong>Aviso!</strong> Você não está operante na fila interna.";
                header("Location:../../dashboard");
                break;
            default:
                break;
        }
    }

    function getInstance()
    {
        return new EmployeeController();
    }
}