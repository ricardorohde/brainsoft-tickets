<?php

   include_once __DIR__.'/../../utils/model/role.php';

   $roleController = new RoleController();
   $roleController->verifyData();

   class RoleController {

      function verifyData(){
         if (isset($_GET['id'])){
            if (isset($_GET['type'])){
               $this->active($_GET['id']);
            } else{
               $this->delete($_GET['id']);
            }
         }

         if (isset($_POST['desc_role']) && $_POST['type_role']){
            $this->registerCtrl($_POST['desc_role'], $_POST['type_role']);
         }

          if (isset($_POST['typeUser']) && isset($_POST['userInformed'])){
            $this->display($_POST['typeUser'], $_POST['userInformed']);
          }
      }

      function display($typeUser, $userRole){
        $role = new Role();

        if($typeUser == "employee"){
          $role->setType(0);
          $roles = $role->show();
        } else{
          $role->setType(1);
          $roles = $role->show();
        }

        $info = "<option value=''>Selecione um cargo...</option>";
        echo $info;

        for ($i=0; $i < count($roles); $i++) { 
          $id = $roles[$i]['id'];
          $desc = $roles[$i]['description'] == "supportBrain" ? "Suporte" : $roles[$i]['description'];
          $status = $roles[$i]['description'] == $userRole ? "selected" : "";
          $option = "<option value='$id' $status>$desc</option>";
          echo $option;
        }
      }

      function registerCtrl($description, $typeUser){
         $role = new Role();

         $role->setDescription($description);
         if($typeUser == "employee"){
            $role->setType(0);
         } else{
            $role->setType(1);  
         }
         $result = $role->register();

         $this->setSession($result);
      }

      function active($id){
         $role = new Role();

         $role->setId($id);
         $result = $role->active();

         $this->setSession($result);
      }

      function delete($id){
         $role = new Role();

         $role->setId($id);
         $result = $role->delete();

         $this->setSession($result);
      }

      function setSession($status){
         session_start();

         unset($_SESSION['roleOk']);
         unset($_SESSION['roleNo']);

         switch ($status) {
           case "roleOk":
             $_SESSION['roleOk'] = "<strong>Sucesso!</strong> Cargo registrado com êxito.";
             header("Location:../../dashboard/cadastros");
             break;
           case "roleNo":
             $_SESSION['roleNo'] = "<strong>Erro!</strong> Houve um problema ao registrar este cargo.";
             header("Location:../../dashboard/cadastros");
             break;
           default:
             break;
         }
      }

      function getInstance(){
         return new RoleController();
      }

   }
?>