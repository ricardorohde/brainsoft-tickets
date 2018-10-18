<?php

   include_once __DIR__.'/../../utils/model/module.php';

   $moduleController = new ModuleController();
   $moduleController->verifyData();

   class ModuleController{

      function verifyData(){
         if(isset($_GET['id'])){
            if(isset($_GET['type'])){
               $this->active($_GET['id']);
            } else{
               $this->delete($_GET['id']);
            }
         }
         
         if(isset($_POST['id']) && isset($_POST['valor'])){
            echo "<script>alert();</script>";
            $this->updateCtrl($_POST['id'], $_POST['valor']);
         }

         if (isset($_POST['newModule'])){
            $this->registerCtrl($_POST['module_desc'], $_POST['id_category'], $_POST['module_limit_time']);
         }
      }

      function registerCtrl($description, $id_category, $limit_time){
         $module = new Module();

         $status = $module->register($description, $id_category, $limit_time);

         if ($status == 1){
            $this->setSession("isOk");
         } else{
            $this->setSession("notOk");
         }
      }

      function findIdByNameAndCategory($name, $id_category){
         $module = new Module();

         return $module->findIdByNameAndCategory($name, $id_category);
      }

      function active($id){
         $module = new Module();

         $result = $module->active($id);

         $this->setSession($result);
      }

      function delete($id){
         $module = new Module();

         $result = $module->delete($id);

         $this->setSession($result);
      }

      function updateCtrl($id, $valor){
         $module = new Module();

         $module->update($id, $valor);
      }

      function setSession($result){
         session_start();

         switch ($result) {
            case "isOk":
               unset($_SESSION['moduleOk']);
               $_SESSION['moduleOk'] = "<strong>Sucesso!</strong> Módulo cadastrado com êxito.";
               header("Location:../../dashboard/cadastros");
            break;
            case "notOk":
               unset($_SESSION['moduleNo']);
               $_SESSION['moduleNo'] = "<strong>Erro!</strong> Problema ao cadastrar módulo.";
               header("Location:../../dashboard/cadastros");
            break;
            default:
               break;
         }
      }

      function getInstance(){
         return new CategoryModuleController();
      }

   }
?>