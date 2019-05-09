<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../chat/chat.ctrl.php";
include_once __DIR__ . "/../category/category.ctrl.php";
include_once __DIR__ . "/../module/module.ctrl.php";
require_once __DIR__ . "/../../model/ticket.php";

$controller = new TicketDataController();
$controller->verifyData();

class TicketDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $clientController;
    private $employeeController;
    private $chatController;
    private $categoryController;
    private $moduleController;

    private $currentAttendant;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();

        $this->setTimezone();
        $this->clientController = ClientController::getInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->chatController = ChatController::getInstance();
        $this->categoryController = CategoryController::getInstance();
        $this->moduleController = ModuleController::getInstance();
    }

    function setTimezone()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    function verifyData()
    {
        if (isset($_POST['finishTicket'])) {
            $data = $_POST;

            $this->registerCtrl($data, 1);
        } else if (isset($_POST['submit'])) {
            $data = $_POST;
            $this->registerCtrl($data, 0);
        } else {
            header("Location:/painel/tickets");
        }
    }

    function registerCtrl($data, $finish)
    {
        $this->currentAttendant = $this->employeeController->findByName($data['target-attendant']);

        $id_who_opened = $_SESSION['login'];
        isset($data['is_repeated']) ? $is_repeated = 1 : $is_repeated = 0;

        $id_registry = $this->findIdRegistry($data['client']);
        $attendantToFind = $this->currentAttendant['id'] == 1 && $data['target-attendant'] == 1 ? $data['attendant'] : $this->currentAttendant['id'];
        $id_chat_found = $this->chatController->findByIdAndIdAttendant($data['id_chat'], $attendantToFind);
        $id_category = $this->categoryController->findIdByDescription($data['selected_category']);
        $id_module = $this->moduleController->findIdByDescriptionAndCategory($data['selected_module'], $id_category);

        if ($id_chat_found == 0) {
            $id_chat = $this->chatController->new($data['id_chat'], $data['opening_time'], $data['final_time'], $data['duration_in_minutes'])[0]['last'];

            $ticket = new Ticket($this, $this->prepareInstance);
            $ticket->setIdRegistry($id_registry);
            $ticket->setIdClient($data['client']);
            $ticket->setPriority($data['priority']);
            $ticket->setStatus($data['status']);
            $ticket->setSource($data['source']);
            $ticket->setType($data['type']);
            $ticket->setGroup($data['group']);
            $ticket->setIdModule($id_module);
            $ticket->setIdAttendant($data['attendant']);
            $ticket->setResolution($data['resolution']);
            $ticket->setIsRepeated($is_repeated);
            $ticket->setidWhoOpened($id_who_opened);
            $ticket->setIdChat($id_chat);
            $ticket->register();

            $this->setSession("new");
        } elseif ($finish == 1) {
            $this->finishTicket(
                $id_chat_found,
                $id_registry,
                $data['client'],
                $data['priority'],
                $data['status'],
                $data['source'],
                $data['type'],
                $data['group'],
                $id_module,
                $data['attendant'],
                $data['resolution'],
                $is_repeated,
                date("Y/m/d H:i:s")
            );
        } else {
            $this->chatController->update($data['id_chat'], $data['final_time'], $data['duration_in_minutes']);

            $this->updateCtrl(
                $id_chat_found,
                $id_registry,
                $data['client'],
                $data['priority'],
                $data['status'],
                $data['source'],
                $data['type'],
                $data['group'],
                $id_module,
                $data['attendant'],
                $data['resolution'],
                $is_repeated
            );
        }
    }

    function updateCtrl($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setIdChat($id_chat_found);
        $ticket->setIdRegistry($id_registry);
        $ticket->setIdClient($client);
        $ticket->setPriority($priority);
        $ticket->setStatus($status);
        $ticket->setSource($source);
        $ticket->setType($type);
        $ticket->setGroup($group);
        $ticket->setIdModule($module);
        $ticket->setIdAttendant($attendant);
        $ticket->setResolution($resolution);
        $ticket->setIsRepeated($is_repeated);
        $result = $ticket->update();

        $this->setSession("update");
        die();
    }

    function finishTicket($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated, $finalized_at)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setIdChat($id_chat_found);
        $ticket->setIdRegistry($id_registry);
        $ticket->setIdClient($client);
        $ticket->setPriority($priority);
        $ticket->setStatus("solucionado");
        $ticket->setSource($source);
        $ticket->setType($type);
        $ticket->setGroup($group);
        $ticket->setIdModule($module);
        $ticket->setIdAttendant($attendant);
        $ticket->setResolution($resolution);
        $ticket->setIsRepeated($is_repeated);
        $ticket->setFinalizedAt($finalized_at);
        $ticket->setIdWhoClosed($_SESSION['login']);
        $result = $ticket->finish();

        $this->setSession("finish");
        die();
    }

    private function findIdRegistry($idClient)
    {
        return $this->clientController->findIdRegistryByIdClient($idClient);
    }

    function setSession($action)
    {
        switch ($action) {
            case "new":
                unset($_SESSION['ticketStatus']);
                $_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket cadastrado com êxito.";
                header("Location:../../../painel/tickets");
                break;
            case "update":
                unset($_SESSION['ticketStatus']);
                $_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket alterado com êxito.";
                header("Location:../../../painel/tickets");
                break;
            case 'finish':
                unset($_SESSION['ticketStatus']);
                $_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket finalizado com êxito.";
                header("Location:../../../painel/tickets");
                break;
            default:
                break;
        }
    }

    function thereIsInputEmpty()
    {
        $_SESSION['thereIsProblemInTicket'] = "<strong>Erro!</strong> Preencha todos os campos para registrar.";
        header("Location:../painel/view_ticket.php");
        die();
    }

    function verifyPermission()
    {
        if (!isset($_SESSION['Ticket' . '_page_' . $_SESSION['login']])) {
            header("Location:../painel");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TicketDataController();
        }
        return self::$instance;
    }
}
