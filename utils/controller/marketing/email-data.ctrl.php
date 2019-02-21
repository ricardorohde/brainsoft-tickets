<?php 
require_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../employee/employee.ctrl.php";
require_once __DIR__ . "/../client/client.ctrl.php";
require_once __DIR__ . "/../registry/registry.ctrl.php";
require_once __DIR__ . "/../../model/emailMarketing.php";

$controller = new EmailDataController();
$controller->verifyDataReceived();

class EmailDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $employeeController;
    private $clientController;
    private $registryController;

    private $dataReceived;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->clientController = ClientController::getInstance();
        $this->registryController = RegistryController::getInstance();

        $this->dataReceived = $_POST;
    }

    public function verifyDataReceived()
    {
        if (isset($this->dataReceived['date'])) {
            $this->findHistory($this->dataReceived['date']);
        }
    }

    public function findHistory($date)
    {
        $email = new EmailMarketing($this, $this->prepareInstance);
        $email->setSentAt($date);
        $emails = $email->findAllByDate();
        $qtdEmails = count($emails);

        for ($i = 0; $i < $qtdEmails; $i++) {
            $employee = $this->employeeController->findByCredential($emails[$i]['id_sender']);
            $employeeFirstName = explode(" ", $employee['name']);
            $client = $this->clientController->findByCredential($emails[$i]['id_recipient']);
            $registry = $this->registryController->findById($client['id_registry']);

            $sender = $employeeFirstName[0];
            $recipient = $client['name'] . " | " . "<strong>" . $registry['name'] . "</strong>";
            $subject = $emails[$i]['subject'];
            $message = $emails[$i]['message'];
            $sentDate = date("d/m/Y", strtotime($emails[$i]['sent_at']));
            $sentTime = date("H:m:s", strtotime($emails[$i]['sent_at']));
            $class = $emails[$i]['status'] == 1 ? "class='table-success'" : "class='table-danger'";
            $status = $emails[$i]['status'] == 1 ? "Enviado" : "Falhou";
            $info = $emails[$i]['info'];

            $option = "<tr " . $class . ">
                        <th scope='row'>$status</th>
                        <td>$sender</td>
                        <td>$recipient</td>
                        <td>$subject</td>
                        <td><div style='height:35px; overflow:auto;' contenteditable>$message</div></td>
                        <td>$info</td>
                        <td>$sentDate às $sentTime</td>
                       </tr>";
            echo $option;
        }               
    }

    function verifyPermission()
    {
        if (!isset($_SESSION['Marketing'.'_page_'.$_SESSION['login']])) {
            header("Location:../");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new EmailDataController();
        }
        return self::$instance;
    }
}
