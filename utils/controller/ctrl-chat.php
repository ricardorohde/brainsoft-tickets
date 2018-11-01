<?php

   include_once __DIR__.'/../../utils/model/chat.php';

   $file = new ChatController();

   class ChatController {

      function registerCtrl($id, $opening_time, $final_time, $duration_in_minutes){
         $chat = new Chat($this->getInstance());

         return $chat->register($id, $opening_time, $final_time, $duration_in_minutes);
      }

      function searchIdCtrl($chat_id){
         $chat = new Chat($this->getInstance());

         return $chat->searchId($chat_id);
      }

      function searchChatIdCtrl($id){
         $chat = new Chat($this->getInstance());

         return $chat->searchChatId($id);
      }

      function updateCtrl($chat_id, $final_time, $duration_in_minutes){
         $chat = new Chat($this->getInstance());

         return $chat->update($chat_id, $final_time, $duration_in_minutes);
      }

      function setSession($result){
         session_start();
         if ($result == 1) {
            $_SESSION['resultSaveFiles'] = "<strong>Sucesso!</strong> Boleto(s) registrado(s) com êxito.";
         } else{
            $_SESSION['thereIsProblemInSaveFiles'] = "<strong>Erro 802!</strong> Problema ao salvar boleto(s) no banco de dados.";
         }
         
         header("Location:../dashboard/administrative.php");
      }

      function getInstance(){
         return new ChatController();
      }

   }
?>