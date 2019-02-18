<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Ticket Detalhado</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
   
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/email.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link type="text/css" href="../css/jquery-ui.css" rel="stylesheet"/>

    <link rel="shortcut icon" href="favicon.png">
  </head>
  
  <body>
    <?php include ("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php"); ?>  
      <section class="forms">
        <div class="container-fluid">
          <header>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                	<form id="sendEmail" action="send.php" method="POST" enctype="multipart/form-data">
                		<div class="form-group row">
                			<label class="col-sm-1 pt-2 form-control-label">Assunto</label>
                			<div class="col-sm-6">
                				<input type="text" name="subject" id="subject" class="form-control">
                			</div>
                		</div>
                		<div class="form-group row">
                			<label class="col-sm-1 pt-2 form-control-label">Destino</label>
                     		<div class="col-sm-3">
                     			<select name="destiny" class="form-control" id="destiny" required>
                     				<option value="choose" selected="">Selecione o destinatário...</option>
									<option value="all">Todos</option>
									<option value="state">Estado</option>
									<option value="registry">Cartório</option>
								</select>
                     		</div>
                		</div>
                		<div class="form-group row allStates hide">
	                		<div class="offset-sm-1 col-sm-3 select">
		                        <select class="form-control" name="allStates" id="allStates" required>
		                            <option value="">Selecione um estado...</option>
		                        </select>
	                      	</div>
                      	</div>
                      	<div class="form-group row divSetRegistry hide">
	                      	<div class="offset-sm-1 col-sm-3 select">
	                        	<input type="text" name="setRegistry" id="setRegistry" class="form-control" required><span class="help-block-none">Informe o cartório do cliente.</span>
	                      	</div>
	                    </div>
                      	<div class="form-group row tableClients hide">
	                		<div class="offset-sm-1 col-sm-11" style="height: 250px; overflow: auto;">
		                        <table name="tableWithData" class="table table-sm table-hover">
									<thead>
										<tr>
										  <th scope="col">Cliente</th>
										  <th scope="col">Usuário</th>
										  <th scope="col">Email</th>
										  <th scope="col">Cartório</th>
										  <th scope="col">Enviar</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
	                      	</div>
                      	</div>
                		<div class="form-group row">
                			<label class="col-sm-1 pt-2 form-control-label">Anexo(s):</label>
                			<div class="col-sm-3">
                				<input type="file" name="file[]" multiple="multiple" class="multi" accept="png|jpg|pdf|txt"/>
                			</div>
                		</div>
                		<div class="form-group row">
                			<label class="col-sm-1 pt-2 form-control-label">Mensagem</label>
                			<div class="col-sm-11">
                				<textarea name="message" id="message" rows="10" cols="80"></textarea>
                			</div>
                		</div>
                		<div class="form-group row">
                			<div class="offset-sm-11 col-sm-1">
                				<button type="submit" name="submit" class="btn btn-primary btnAction">Enviar!</button>
                			</div>
                		</div>
			        </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <footer class="main-footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <p>Your company &copy; 2017-2019</p>
            </div>
            <div class="col-sm-6 text-right">
              <p>Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- Javascript files-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script src="../jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../js/marketing/controls.js"></script>
    <script src="../vendor/marketing/multiFile/jquery.MultiFile.min.js" type="text/javascript"></script>
    <script src="../vendor/marketing/ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace('message'); </script>
    
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>
