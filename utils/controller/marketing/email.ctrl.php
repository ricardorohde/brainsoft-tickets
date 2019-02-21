<?php 
require_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../state/state.ctrl.php";
require_once __DIR__ . "/../client/client.ctrl.php";
require_once __DIR__ . "/../../model/emailMarketing.php";

class EmailController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function new($idSender, $idRecipient, $subject, $message, $status, $info)
    {
        $emailMarketing = new EmailMarketing($this, $this->prepareInstance);
        $emailMarketing->setIdSender($idSender);
        $emailMarketing->setIdRecipient($idRecipient);
        $emailMarketing->setSubject($subject);
        $emailMarketing->setMessage($message);
        $emailMarketing->setStatus($status);
        $emailMarketing->setInfo($info);
        return $emailMarketing->register();
    }

    public function findAllClients()
    {
        $clientController = new ClientController();
        $clients = $clientController->findAllEmailNotNull();
        $qtdClients = count($clients);

        for ($i = 0; $i < $qtdClients; $i++) {
            $id = $clients[$i]['id'];
            $name = $clients[$i]['name'];
            $login = $clients[$i]['login'];
            $email = $clients[$i]['email'];
            $registry = $clients[$i]['registry'];
            $option = "<tr>
                        <th scope='row'>$name</th>
                        <td>$login</td>
                        <td>$email</td>
                        <td>$registry</td>
                        <td>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radio$i' value='send' checked>
                              <label class='form-check-label' for='inlineRadio$i'>Sim</label>
                            </div>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radioNo$i' value='noSend'>
                              <label class='form-check-label' for='inlineRadioNo$i'>Não</label>
                            </div>
                        </td>
                        <input type='hidden' name='id[]' value='$id' />
                        <input type='hidden' name='client[]' value='$name' />
                        <input type='hidden' name='login[]' value='$login' />
                        <input type='hidden' name='email[]' value='$email' />
                        <input type='hidden' name='registry[]' value='$registry' />
                       </tr>";

            echo $option;
        }
    }

    public function findAllStates()
    {
        $stateController = new StateController();
        $states = $stateController->findAllStates();
        $qtdStates = count($states);

        echo "<option value='chooseState'>Selecione um estado...</option>";

        for ($i = 0; $i < $qtdStates; $i++) { 
            $id = $states[$i]['id'];
            $description = $states[$i]['description'];
            $option = "<option value='$id'>$description</option>";

            echo $option;
        }
    }

    public function findAllClientsOfState($state)
    {
        $clientController = new ClientController();
        $clients = $clientController->findAllByStateEmailNotNull($state);
        $qtdClients = count($clients);

        for ($i = 0; $i < $qtdClients; $i++) {
            $id = $clients[$i]['id'];
            $name = $clients[$i]['name'];
            $login = $clients[$i]['login'];
            $email = $clients[$i]['email'];
            $registry = $clients[$i]['registry'];
            $option = "<tr>
                        <th scope='row'>$name</th>
                        <td>$login</td>
                        <td>$email</td>
                        <td>$registry</td>
                        <td>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radio$i' value='send' checked>
                              <label class='form-check-label' for='inlineRadio$i'>Sim</label>
                            </div>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radioNo$i' value='noSend'>
                              <label class='form-check-label' for='inlineRadioNo$i'>Não</label>
                            </div>
                        </td>
                        <input type='hidden' name='id[]' value='$id' />
                        <input type='hidden' name='client[]' value='$name' />
                        <input type='hidden' name='login[]' value='$login' />
                        <input type='hidden' name='email[]' value='$email' />
                        <input type='hidden' name='registry[]' value='$registry' />
                       </tr>";

            echo $option;
        }
    }

    public function findAllClientsOfRegistry($registry)
    {
        $clientController = new ClientController();
        $clients = $clientController->findAllByRegistryEmailNotNull($registry);
        $qtdClients = count($clients);

        for ($i = 0; $i < $qtdClients; $i++) {
            $id = $clients[$i]['id'];
            $name = $clients[$i]['name'];
            $login = $clients[$i]['login'];
            $email = $clients[$i]['email'];
            $registry = $clients[$i]['registry'];
            $option = "<tr>
                        <th scope='row'>$name</th>
                        <td>$login</td>
                        <td>$email</td>
                        <td>$registry</td>
                        <td>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radio$i' value='send' checked>
                              <label class='form-check-label' for='inlineRadio$i'>Sim</label>
                            </div>
                            <div class='form-check form-check-inline'>
                              <input class='form-check-input' type='radio' name='radio$i' id='radioNo$i' value='noSend'>
                              <label class='form-check-label' for='inlineRadioNo$i'>Não</label>
                            </div>
                        </td>
                        <input type='hidden' name='id[]' value='$id' />
                        <input type='hidden' name='client[]' value='$name' />
                        <input type='hidden' name='login[]' value='$login' />
                        <input type='hidden' name='email[]' value='$email' />
                        <input type='hidden' name='registry[]' value='$registry' />
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
            self::$instance = new EmailController();
        }
        return self::$instance;
    }
}
