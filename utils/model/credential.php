<?php
include_once __DIR__ . '/../../common/config.php';
include_once __DIR__ . '/../controller/credential/credential.ctrl.php';
include_once __DIR__ . '/../../utils/services/credential/loginService.php';
include_once __DIR__ . '/../helper/session/flashMessageHelper.php';
include_once __DIR__ . '/../helper/session/sessionHelper.php';

class Credential
{
    private $prepareInstance;

    private $myController;
    private $credentialController;

    private $loginService;
    private $flashMessageHelper;
    private $sessionHelper;

    private $id;
    private $login;
    private $password;
    private $b_salt;

    public function getPrepareInstance()
    {
        return $this->prepareInstance;
    }

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    public function getMyController()
    {
        return $this->myController;
    }

    public function setMyController($myController)
    {
        $this->myController = $myController;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBSalt()
    {
        return $this->b_salt;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setBSalt($b_salt)
    {
        $this->b_salt = $b_salt;
    }

    public function getConn()
    {
        return $this->connection;
    }

    public function setConn($conn)
    {
        $this->connection = $conn;
    }

    function __construct($controller, $prepareInstance)
    {
        $this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
        $this->setConn(new ConfigDatabase());

        $this->credentialController = CredentialController::getInstance();

        $this->loginService = LoginService::getInstance($this->prepareInstance);
        $this->flashMessageHelper = FlashMessageHelper::getInstance();
        $this->sessionHelper = SessionHelper::getInstance();
    }

    public function verifyDataReceived($data)
    {
        if (isset($data['submit'])) {
            if ($data['submit'] == 'fromDoLogin' || $data['submit'] == 'submitFromIndex') {
                $this->login = $data['login'];
                $this->password = $data['password'];
                $this->checkLogin();
            }
        }
    }

    public function findById()
    {
        $element = $this->getId();
        $query = "SELECT login FROM credential WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findByLogin()
    {
        $element = $this->getLogin();
        $query = "SELECT id FROM credential WHERE login = ? ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function checkLogin()
    {
        $element = $this->login;
        $query = "SELECT * FROM credential WHERE login = ?";
        $result = $this->prepareInstance->prepare($query, $element, "");

        $bs_salt = "ftosniarbsistemas";
        $salted_hash = hash('sha256', $this->password . $bs_salt . $result['b_salt']);

        if ($result['password'] == $salted_hash) {
            $employeeData = $this->credentialController->statusOnChat($result['id']);

            $this->sessionHelper->storeInSession("id", $employeeData['id']);
            $this->sessionHelper->storeInSession("login", $result['id']);
            $this->sessionHelper->storeInSession("role", $employeeData['id_role']);

            $this->credentialController->verifyAuthorizations($result['id']);

            $this->flashMessageHelper->makeFlash(
                "loginSuccess",
                "success",
                "<strong>Sucesso!</strong> Usuário autenticado com sucesso.",
                "/painel"
            );
        } else {
            $this->flashMessageHelper->makeFlash(
                "loginFail",
                "danger",
                "<strong>Erro!</strong> Usuário e/ou Senha incorretos.",
                "/"
            );
        }
    }

    public function changePassword($newPass, $confirmationPass)
    {
        $currentPasswordIsCorrect = $this->checkActualPassword();
        $this->doTheChange($currentPasswordIsCorrect, $newPass, $confirmationPass);
    }

    public function changePasswordToClient($newPass, $confirmationPass)
    {
        $this->doTheChange(true, $newPass, $confirmationPass);
    }

    public function register()
    {
        $b_salt = $this->loginService->rand_string(20);
        $site_salt = "ftosniarbsistemas";
        $salted_hash = hash('sha256', $this->password . $site_salt . $b_salt);

        $elements = [$this->login, $salted_hash, $b_salt];
        $query = "INSERT INTO credential (`id`, `login`, `password`, `b_salt`) VALUES (NULL, ?, ?, ?)";
        return $this->prepareInstance->prepareStatus($query, $elements, "");
    }

    public function verifyIfExists()
    {
        $element = $this->login;
        $query = "SELECT COUNT(*) as total FROM credential WHERE login LIKE ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function doTheChange($currentPasswordIsCorrect, $newPass, $confirmationPass)
    {
        if ($currentPasswordIsCorrect && $this->loginService->isSamePassword($newPass, $confirmationPass)) {
            $b_salt = $this->loginService->rand_string(20);
            $site_salt = "ftosniarbsistemas";
            $salted_hash = hash('sha256', $newPass . $site_salt . $b_salt);

            $elements = [$salted_hash, $b_salt, $this->id];
            $query = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
            $this->verifyResult(
                $this->prepareInstance->prepareStatus($query, $elements, ""),
                "Senha alterada com êxito.",
                "Ocorreu um problema ao alterar a sua senha. Tente novamente."
            );
        } else {
            $this->flashMessageHelper->makeFlash(
                "changeStatus",
                "danger",
                "<strong>Erro!</strong> Nova Senha e Confirmação da Nova Senha necessitam ser iguais.",
                "/painel/conta"
            );
        }
    }

    private function checkActualPassword()
    {
        $element = $this->id;
        $query = "SELECT password, b_salt FROM credential WHERE id = ?";
        $credential = $this->prepareInstance->prepare($query, $element, "");

        $site_salt = "ftosniarbsistemas";
        $saltedHash = hash('sha256', $this->password . $site_salt . $credential['b_salt']);

        if ($credential['password'] == $saltedHash) {
            return true;
        } else {
            $this->flashMessageHelper->makeFlash(
                "changeStatus",
                "danger",
                "<strong>Erro!</strong> A senha atual não confere.",
                "/painel/conta"
            );
        }
    }

    private function checkIfEmailExists()
    {
        $element = $this->email;
        $query = "SELECT id FROM client WHERE email = ?";
        $client = $this->prepareInstance->prepare($query, $element, "");

        if (is_null($client) || $client == "") {
            $query = "SELECT id FROM employee WHERE email = ?";
            $employee = $this->prepareInstance->prepare($query, $element, "");

            if (is_null($employee) || $employee == "") {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    private function verifyResult($result, $success, $fail)
    {
        if ($result == 1) {
            $this->flashMessageHelper->makeFlash(
                "changeStatus",
                "success",
                "<strong>Sucesso!</strong> " . $success,
                "/painel/conta"
            );
        } else {
            $this->flashMessageHelper->makeFlash(
                "changeStatus",
                "danger",
                "<strong>Erro!</strong> " . $fail,
                "/painel/conta"
            );
        }
    }
}
