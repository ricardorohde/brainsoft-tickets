<?php

   include_once("../../common/config.php");

   $file = new FileController();
   $file->verifyData();

   class FileController {

      protected $connection;
      protected $registryController;
      
      function __construct() {
         $this->connection = new ConfigDatabase();
      }

      function verifyData() {
         if(isset($_FILES['fileUpload'])) {
            $this->saveFile();
         }

         if(isset($_POST['paid_date']) ){
            $this->deleteFile($_POST['id_of_file'], $_POST['paid_date']);
         }

         if(isset($_POST['date_when_expire'])){
            $this->saveExpirationDate($_POST['date_when_expire'], $_POST['id_of_file']);
         }
      }

      function saveFile(){
         $id_registry = $_POST['id_registry'];
         $name_registry = $_POST['registryListToAdm'];

         date_default_timezone_set("Brazil/East"); //Definindo timezone padrão

         $name     = $_FILES['fileUpload']['name']; //Atribui uma array com os nomes dos arquivos à variável
         $tmp_name = $_FILES['fileUpload']['tmp_name']; //Atribui uma array com os nomes temporários dos arquivos à variável

         $name_of_registry = strtolower($name_registry);
         $name_of_registry_trimmed = str_replace(" ","",$name_of_registry);

         $allowedExts = array(".jpg", ".png", ".pdf"); //Extensões permitidas

         $dir = '../../dashboard/administrative-files/'; //Diretório para uploads

         for($i = 0; $i < count($tmp_name); $i++) { //passa por todos os arquivos
         
            $ext = strtolower(substr($name[$i],-4));

            if(in_array($ext, $allowedExts)) {//Pergunta se a extensão do arquivo, está presente no array das extensões permitidas

               $name_of_raw_file = strtolower(substr($name[$i],0,-4));
               
               $new_name = $name_of_registry_trimmed . "-" . $name_of_raw_file . $ext;

               move_uploaded_file($_FILES['fileUpload']['tmp_name'][$i], $dir.$new_name); //Fazer upload do arquivo
            
               $sql = $this->connection->getConnection()->prepare("INSERT INTO `administrative_file` (`id`, `id_registry`, `path_to_file`, `status`) VALUES (NULL, ?, ?, ?)");
               $result = $sql->execute(array($id_registry, $new_name, "ativo"));
            }
         }
         //$this->setSession($result);
      }

      function deleteFile($id, $paid_date) {
         include_once('../model/administrative-file.php');

         $file = new File();
         $file->delete($id, $paid_date);
      }

      function saveExpirationDate($expirationDate, $id){
         include_once('../model/administrative-file.php');

         $file = new File();
         $file->setExpirationDate($expirationDate, $id);
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

   }
?>