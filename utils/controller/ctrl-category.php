<?php

   include_once __DIR__.'/../../utils/model/category.php';

   $categoryModuleController = new CategoryModuleController();
   $categoryModuleController->verifyData();

   class CategoryModuleController {

      function verifyData(){
         if (isset($_POST['fromCategory'])){
            $categoryName = $_POST['fromCategory'];
            
            echo $this->findIdByName($categoryName);
         }

         if(isset($_POST['desc_category']) && isset($_POST['group'])){
            $this->registerCtrl($_POST['desc_category'], $_POST['group']);
         }
      }

      function registerCtrl($description, $group){
         $category = new CategoryModule();
         $category->setDescription($description);
         $category->setTGroup($group);

         $status = $category->register();

         if ($status == 1){
            $this->setSession("isOk");
         } else{
            $this->setSession("notOk");
         }
      }

      function findIdByName($name){
         $category = new CategoryModule();

         return $category->findIdByName($name);
      }

      function setSession($result){
         session_start();

         switch ($result) {
            case "isOk":
               unset($_SESSION['categoryOk']);
               $_SESSION['categoryOk'] = "<strong>Sucesso!</strong> Categoria cadastrada com êxito.";
               header("Location:../../dashboard/cadastros");
            break;
            case "notOk":
               unset($_SESSION['categoryNo']);
               $_SESSION['categoryNo'] = "<strong>Erro!</strong> Problema ao cadastrar categoria.";
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