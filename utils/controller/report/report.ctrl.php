<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";

class ReportController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $idClient;
    private $initialDate;
    private $finalDate;

    private $client;
    private $tickets;
    private $totalOfTickets;
    private $registryName;
    private $chats;
    private $totalMin;
    private $biggestTimeInConversation;
    private $module;
    private $ticketWithIdClient;
    private $topClient;
    private $topTicketOfClient;

    public function getIdClient()
    {
        return $this->idClient;
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    public function getInitialDate()
    {
        return $this->initialDate;
    }

    public function setInitialDate($initialDate)
    {
        $this->initialDate = $initialDate;
    }

    public function getFinalDate()
    {
        return $this->finalDate;
    }

    public function setFinalDate($finalDate)
    {
        $this->finalDate = $finalDate;
    }

    public function getRegistryName()
    {
        return $this->registryName['name'];
    }

    public function getTotalOfTickets()
    {
        return $this->totalOfTickets['total'];
    }

    public function getClientName()
    {
        return $this->client['name'];
    }

    public function getTotalMin()
    {
        return $this->totalMin;
    }

    public function getBiggestTime()
    {
        return $this->biggestTimeInConversation;
    }

    public function getModule()
    {
        return $this->module['description'];
    }

    public function getTopClients()
    {
        return $this->topClient;
    }

    public function getTopTicketOfClient()
    {
        return $this->topTicketOfClient;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->setTimezone();
    }

    public function setTimezone()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    function make()
    {
        $element = $this->getIdClient();
        $query = "SELECT name, id_registry FROM client WHERE id_credential = ?";
        $this->client = $this->prepareInstance->prepare($query, $element, "");

        $elements = [$this->client['id_registry'], "pendente"];
        $query = "SELECT id_client, id_module, id_attendant, id_chat, registered_at, finalized_at FROM ticket WHERE id_registry = ? AND t_status != ?";
        $this->tickets = $this->prepareInstance->prepare($query, $elements, "all");

        $elements = [$this->client['id_registry'], $this->initialDate, $this->finalDate];
        $query = "SELECT COUNT(*) as total FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ?";
        $this->totalOfTickets = $this->prepareInstance->prepare($query, $elements, "");

        $element = $this->client['id_registry'];
        $query = "SELECT name FROM registry WHERE id = ?";
        $this->registryName = $this->prepareInstance->prepare($query, $element, "");

        $elements = [$this->client['id_registry'], $this->initialDate, $this->finalDate];
        $query = "SELECT id_chat FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ?";
        $this->chats = $this->prepareInstance->prepare($query, $elements, "all");

        $this->totalMin = 0;
        $this->biggestTimeInConversation = 0;

        foreach ($this->chats as $key) {
            $element = $key['id_chat'];
            $query = "SELECT duration_in_minutes FROM chat WHERE id = ? ORDER BY duration_in_minutes";
            $chatInMinutes = $this->prepareInstance->prepare($query, $element, "");

            $this->totalMin += $chatInMinutes['duration_in_minutes'];
            if ($chatInMinutes['duration_in_minutes'] > $this->biggestTimeInConversation) {
                $this->biggestTimeInConversation = $chatInMinutes['duration_in_minutes'];
            }
        }

        $elements = [$this->client['id_registry'], $this->initialDate, $this->finalDate];
        $query = "SELECT count(*) as NrVezes, id_module FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ? GROUP BY id_module ORDER BY NrVezes DESC LIMIT 1";
        $moduleFromTicket = $this->prepareInstance->prepare($query, $elements, "");

        $element = $moduleFromTicket['id_module'];
        $query = "SELECT description FROM ticket_module WHERE id = ?";
        $this->module = $this->prepareInstance->prepare($query, $element, "");

        $elements = [$this->client['id_registry'], $this->initialDate, $this->finalDate];
        $query = "SELECT count(*) as NrVezes, id_client FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ? GROUP BY id_client ORDER BY NrVezes DESC LIMIT 10";
        $this->ticketWithIdClient = $this->prepareInstance->prepare($query, $elements, "all");

        $this->topClient = array("","","","","","","","","","");

        foreach ($this->ticketWithIdClient as $key => $value) {
            $element = $value['id_client'];
            $query = "SELECT name FROM client WHERE id = ?";
            $rowNameClient = $this->prepareInstance->prepare($query, $element, "");

            if ($key != 0) {
                $rowNameClient['name'] = ", ".($key+1)."º ".$rowNameClient['name'];
            } else {
                $rowNameClient['name'] = ($key+1)."º ".$rowNameClient['name'];
            }

            $this->topClient[$key] = $rowNameClient['name'];
        }

        //---------------------------------------
        $this->topTicketOfClient = array("","","","","","","","","","");
        foreach ($this->ticketWithIdClient as $key => $value) {
            $elements = [$value['id_client'], $this->initialDate, $this->finalDate];
            $query = "SELECT COUNT(*) as total FROM ticket WHERE id_client = ? AND registered_at BETWEEN ? AND ?";
            $rowTicketCountOfClient = $this->prepareInstance->prepare($query, $elements, "");

            $this->topTicketOfClient[$key] = $rowTicketCountOfClient['total'];
        }
    }

    function setSession($result)
    {
        if ($result == 1) {
            $_SESSION['resultSaveFiles'] = "<strong>Sucesso!</strong> Boleto(s) registrado(s) com êxito.";
        } else {
            $_SESSION['thereIsProblemInSaveFiles'] = "<strong>Erro 802!</strong> Problema ao salvar boleto(s) no banco de dados.";
        }
        header("Location:../dashboard/cadastros");
    }

    function convertData($time)
    {
        if ($time >= 60) {
            $hour = floor($time / 60);
            $minutes = $time % 60;

            if (strlen($hour) < 2) {
                $hour = "0" . $hour;
            }
            if (strlen($minutes) < 2) {
                $minutes = "0" . $minutes;
            }
            return $hour . ":" . $minutes . " (". $hour . " " . $this->pluralOrSingular($hour, "hora") . " e " . $minutes . " " . $this->pluralOrSingular($minutes, "minuto") .")";
        } else {
            if (strlen($time) < 2) {
                return "00:0" . $time . " (" . $time . " " . $this->pluralOrSingular($time, "minuto") .")";
            } else {
                return "00:" . $time . " (" . $time . " " . $this->pluralOrSingular($time, "minuto") . ")";
            }
        }
    }

    function pluralOrSingular($element, $word)
    {
        if($element > 1) {
          return $word . "s";
        } else {
          return $word;
        }
    }

    function makeDetailTicketTable()
    {
        $elements = [$this->client['id_registry'], $this->initialDate, $this->finalDate];
        $query   = "SELECT id, id_client, id_module, id_attendant, id_chat, registered_at FROM ticket WHERE id_registry = ? AND registered_at BETWEEN ? AND ?";
        return $this->prepareInstance->prepare($query, $elements, "all");
    }

    function getChat($chatId)
    {
        $element = $chatId;
        $query   = "SELECT id_chat FROM chat WHERE id = ?";
        $chat    = $this->prepareInstance->prepare($query, $element, "");
        return $chat['id_chat'];
    }

    function getClient($clientId)
    {
        $element = $clientId;
        $query   = "SELECT name FROM client WHERE id = ?";
        $client  = $this->prepareInstance->prepare($query, $element, "");
        return $client['name'];
    }

    function getModuleById($moduleId)
    {
        $element = $moduleId;
        $query   = "SELECT description, id_category FROM ticket_module WHERE id = ?";
        $module  = $this->prepareInstance->prepare($query, $element, "");
        return $module;
    }

    function getCategoryById($categoryId)
    {
        $element   = $categoryId;
        $query     = "SELECT description FROM category_module WHERE id = ?";
        $category  = $this->prepareInstance->prepare($query, $element, "");
        return $category['description'];
    }

    function getAttendantById($attendantId)
    {
        $element   = $attendantId;
        $query     = "SELECT name FROM employee WHERE id = ?";
        $employee  = $this->prepareInstance->prepare($query, $element, "");
        $firstName = explode(" ", $employee['name'])[0];
        return $firstName;
    }

    function getDurationOfChat($chatId)
    {
        $element   = $chatId;
        $query     = "SELECT duration_in_minutes FROM chat WHERE id = ?";
        $duration  = $this->prepareInstance->prepare($query, $element, "");
        return $this->convertData($duration['duration_in_minutes']);
    }

    function verifyPermission()
    {
        if (!isset($_SESSION['Report'.'_page_'.$_SESSION['login']])) {
          header("Location:/painel/conta");
        }
    }

    public static function getInstance()
    {
        if ( !self::$instance )
            self::$instance = new ReportController();

        return self::$instance;
    }
}
?>
