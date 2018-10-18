<?php

   //include_once __DIR__.'/../../utils/model/module.php';
   include_once("../../commom/config.php");

   $authController = new AuthController();
   $authController->verifyData();

   class AuthController{

      protected $connection;

      public function getConn(){
        return $this->connection;
      }
      public function setConn($conn){
        $this->connection = $conn;
      }

      function __construct() {
         $this->setConn(new ConfigDatabase());
      }

      function verifyData(){
         if(isset($_POST['user'])){
            $this->make($_POST['user']);
         }
      }

      function make($user){
         $sql_pages = $this->getConn()->getConnection()->prepare("SELECT id, name FROM page");
         $sql_pages->execute(); $pages = $sql_pages->fetchAll();

         $sql_pages_authorized = $this->getConn()->getConnection()->prepare("SELECT DISTINCT page.name FROM page, authorization_user_page WHERE authorization_user_page.id_user = ? AND authorization_user_page.id_page = page.id AND authorization_user_page.access = ?");
         $sql_pages_authorized->execute(array($user, "yes")); $pages_authorized = $sql_pages_authorized->fetchAll();

         $authorized_pages = array();
         foreach ($pages_authorized as $page_authorized) {
            array_push($authorized_pages, $page_authorized['name']);
         }

         foreach ($pages as $page){
            $check = "";
            if(in_array($page['name'], $authorized_pages)){
               $check = "checked";
            }

            $option = "<input id='checkBox' type='checkbox' value='".$page['id']."' ".$check.">".$page['name']."<br>";

            echo $option;
         }
      }

      function getInstance(){
         return new AuthController();
      }
   }
?>