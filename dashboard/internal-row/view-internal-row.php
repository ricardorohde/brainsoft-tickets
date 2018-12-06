<?php 
  session_start();
  if (!isset($_SESSION['Queue'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }

  date_default_timezone_set('America/Sao_Paulo');

  class Order{
		function orderByQuantity($contagem){
			$queue = array();
			foreach($contagem as $numero => $vezes){
				if($vezes == 2){
					array_unshift($queue, $numero);
				}
			}

			foreach($contagem as $numero => $vezes){
				if($vezes == 1){
					array_unshift($queue, $numero);
				}
			}

			return $queue;
		}

		function orderByDate($id_finalized_queue, $connection){
			$queue_according_data = array();
			$dates = array();
			$position = 0;

			foreach ($id_finalized_queue as $id_attendant){
				$sql_finalized_queue_2 = $connection->getConnection()->prepare("SELECT registered_at FROM ticket 
					WHERE id_attendant = ? ORDER BY id DESC LIMIT 1");
				$sql_finalized_queue_2->execute(array($id_attendant['id_attendant'])); 
				$row_finalized_queue_2 = $sql_finalized_queue_2->fetch();

				$last_date = $row_finalized_queue_2['registered_at'];
				$final_date = strtotime($last_date);

				array_push($dates, $final_date);
			}

			asort($dates);

			foreach ($dates as $key => $date) {
				$dateString = date('Y-m-d H:i:s', $date);

				$sql_finalized_queue_2 = $connection->getConnection()->prepare("SELECT id_attendant FROM ticket 
					WHERE registered_at = ? ORDER BY id DESC LIMIT 1");
				$sql_finalized_queue_2->execute(array($dateString)); 
				$row_finalized_queue_2 = $sql_finalized_queue_2->fetch();

				$queue_according_data[$position] = $row_finalized_queue_2['id_attendant'];

				$position++;
			}
			
			return $queue_according_data;
		}

		function findUserInQueue($qtd_attendants, $new_queue_group, $queue_according_date){
			$finalized_queue_group_1 = array();

			for ($i = 0; $i <= $qtd_attendants + 1; $i++){ 
				if (!in_array($i+2, $new_queue_group)) { 
					$finded = 0;
					foreach ($queue_according_date as $key => $value){
						if($value[0] == $i+2 && $finded == 0){
							$finalized_queue_group_1[$key] = $i+2;
							$finded = 1;
						}
					}
				} 
			}

			ksort($finalized_queue_group_1);

			return $finalized_queue_group_1;
		}
	}
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Fila Interna</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="./vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="./css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="./css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="./css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <script type="text/javascript">
			var contador = '40';

			function startTimer(duration, display) {
			  var timer = duration, minutes, seconds;
			  setInterval(function() {
			    minutes = parseInt(timer / 60, 10)
			    seconds = parseInt(timer % 60, 10);

			    minutes = minutes < 10 ? "0" + minutes : minutes;
			    seconds = seconds < 10 ? "0" + seconds : seconds;

			    display.textContent = minutes + ":" + seconds;

			    if (--timer < 1) {
			      window.setTimeout('location.reload()');
			    }
			  }, 1000);
			}

			window.onload = function() {
			  var count = parseInt(contador), display = document.querySelector('#time');
			  startTimer(count, display);
			};
		</script>
	</head>
	<?php 
		$targets = array(
      "Billet" => "administrativo Administrativo fa-files-o",
      "Ticket" => "tickets Tickets fa-ticket",
      "User" => "usuarios Usuários fa-user-circle",
      "Registry" => "cartorios Cartórios fa-home",
      "Module" => "cadastros Módulos fa-caret-square-o-right",
      "Queue" => "fila-interna Fila fa-sort-amount-asc",
      "Authorization" => "autorizacoes Autorizações fa-caret-square-o-right",
      "Report" => "relatorios Relatórios fa-caret-square-o-right",
      "Logout" => "logout"
    );
	?>

	<body>
	  <?php include ("../navs/navbar.php");?>
	  <div class="root-page forms-page">
	  	<?php include ("../navs/header.php");?>
	  	<?php
	      /* TODOS OS CHATS ABERTOS*/
	      $elements_to_all_chats_open = "aberto";
	      $query = "SELECT id_module, t_group, id_chat, id_attendant as id, registered_at FROM ticket WHERE t_status = ? ORDER BY id_chat desc";
	      $chats = $prepareInstance->prepare($query, $elements_to_all_chats_open, "all");

	      /* QUANTIDADE DE FUNCIONÁRIOS CADASTRADOS GRUPO 1*/
	      $elements_to_attendants_group_1 = "nivel1";
	      $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ?";
	      $row_attendants_1 = $prepareInstance->prepare($query, $elements_to_attendants_group_1, "all");
	      $qtd_attendant_1 = $row_attendants_1[0]['total'];

	      /* QUANTIDADE DE FUNCIONÁRIOS HABILITADOS GRUPO 1*/
	      $elements_to_attendants_group_1_on_chat = ["nivel1", "yes"];
	      $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
	      $row_attendants_1_on_chat = $prepareInstance->prepare($query, $elements_to_attendants_group_1_on_chat, "all");
	      $qtd_attendant_1_on_chat = $row_attendants_1_on_chat[0]['total'];

	      /* GRUPO 1 */
	      $limit_initialize_1 = (int) $qtd_attendant_1*2;
	      $elements_to_initialize_queue_1 = ["aberto", "nivel1", $limit_initialize_1];
	      $query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
	      $row_initialized_queue_1 = $prepareInstance->prepare3Bind($query, $elements_to_initialize_queue_1, "all");

	      $limit_finalized_1 = (int) $qtd_attendant_1;
	      $elements_to_finalized_queue_1 = ["aberto", "nivel1", "yes", $limit_finalized_1];
	      $query = "SELECT DISTINCT id_attendant FROM ticket, employee WHERE 
					t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
				$row_id_finalized_queue_1 = $prepareInstance->prepare4Bind($query, $elements_to_finalized_queue_1, "all");

				/* QUANTIDADE DE FUNCIONÁRIOS CADASTRADOS GRUPO 2*/
				$element_to_attendants_group_2 = "nivel2";
				$query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ?";
				$row_attendants_2 = $prepareInstance->prepare($query, $element_to_attendants_group_2, "all");
				$qtd_attendant_2 = $row_attendants_2[0]['total'];

	      /* QUANTIDADE DE FUNCIONÁRIOS HABILITADOS GRUPO 2*/
	      $elements_to_attendants_group_2_on_chat = ["nivel2", "yes"];
	      $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
	      $row_attendants_1_on_chat = $prepareInstance->prepare($query, $elements_to_attendants_group_2_on_chat, "all");
	      $qtd_attendant_2_on_chat = $row_attendants_1_on_chat[0]['total'];

				/* GRUPO 2 */
				$limit_initialize_2 = (int) $qtd_attendant_2*2;
				$elements_to_initialize_queue_2 = ["aberto", "nivel2", $limit_initialize_2];
				$query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
				$row_initialized_queue_2 = $prepareInstance->prepare3Bind($query, $elements_to_initialize_queue_2, "all");

				$limit_finalized_2 = (int) $qtd_attendant_2;
				$elements_to_finalized_queue_2 = ["aberto", "nivel2", "yes", $limit_finalized_2];
				$query = "SELECT DISTINCT id_attendant FROM ticket, employee 
					WHERE t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
				$row_id_finalized_queue_2 = $prepareInstance->prepare4Bind($query, $elements_to_finalized_queue_2, "all");
			?>

	    <section class="forms">
	      <div class="container-fluid">
	        <header>
	          <div class="row">
	            <div class="col-sm-6 title">
	              <h1 class="h3 display">Fila Interna | <span>Atualização automática em <span id="time">...</span></span></h1>
	            </div>
	          </div>
	        </header>

	        <hr>

	        <div id="conteudo">
	        	<iframe class="iframe-queue" width='300px' height='23px' frameborder='0' src='/dashboard/test.php' SCROLLING="NO"></iframe>
	        	<hr>
	        	<h1>Disponibilidade Grupo 1</h1>  	
		    		<div class="row" id="internal-row">
		    			<!-- |INÍCIO| VERIFICAÇÃO E ORDENAMENTO FILA GRUPO 1 -->
		    			<?php 
		    				$new_queue_group_1 = array();
		    			 	
		    				$fila = array();
		    				foreach ($row_initialized_queue_1 as $row) {
		    					array_push($fila, $row['id_attendant']);
		    				} 
								$contagem = array_count_values($fila); 
							?>

							<?php 
								$order = new Order();
								
								$new_queue_group_1 = $order->orderByQuantity($contagem);	
								$queue_according_date = $order->orderByDate($row_id_finalized_queue_1, $connection);							
								$finalized_queue_group_1 = $order->findUserInQueue($qtd_attendant_1_on_chat, $new_queue_group_1, $queue_according_date);
							?>

							<?php $queue_merge_1 = array_merge($finalized_queue_group_1, $new_queue_group_1) ?>

					<!-- |FIM| VERIFICAÇÃO E ORDENAMENTO FILA GRUPO 1 -->

					<!-- |INÍCIO| EXIBIÇÃO FILA GRUPO 1 -->
					<?php if($queue_merge_1 != null) : ?>
						<?php 
							$group_one = $array = [
							    "4" => "Alex",
							    "2" => "Rodrigo"
							];
							$place_in_line_1 = 1;
						?>
						<table align="center">
							<tr>
								<?php foreach($queue_merge_1 as $new_queue) {
									echo "<th class='place_in_line'>" . $place_in_line_1 . "º </th>";

									$place_in_line_1++;
								}?>
							</tr>
							<tr>
								<?php foreach($queue_merge_1 as $new_queue) : ?>	
									<td class="colum_of_place">
										<div class="card mb-3 user<?=$new_queue?>" style="max-width: 18rem; float: left; margin-left: 3%;">
											<div class="card-header"><?=$group_one[$new_queue]; ?></div>
											<div class="card-body nivel1">
					            	<?php foreach ($chats as $chat) : ?>
					            		<?php if ($chat['t_group'] == "nivel1") : ?>
					            			<?php 
					            				$element_to_chat_number_and_ticket = $chat['id_chat'];
					            				$query = "SELECT id_chat FROM chat WHERE id = ?";
					            				$chat_number = $prepareInstance->prepare($query, $element_to_chat_number_and_ticket, "all");

					            				$query = "SELECT registered_at FROM ticket WHERE id_chat = ?"; 
					            				$ticket_time = $prepareInstance->prepare($query, $element_to_chat_number_and_ticket, "all");
					            			?>
			           		
					            			<?php if ($chat['id'] == $new_queue) : ?>
					            				<div>
						            				<?php 
						            					date_default_timezone_set('America/Sao_Paulo');
																	$initial_time = new DateTime(date('Y/m/d H:i:s', strtotime($ticket_time[0]['registered_at'])));
																	$actual_time = new DateTime();

																	$diff = $actual_time->diff($initial_time);
																	$minutos = ($diff->h*60) + $diff->i + ($diff->s/60) + ($diff->days*24*60);
						            				?>

						            				<button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
															   	<p><strong>Ticket: </strong><?= $chat_number[0]['id_chat'];?></p>
															   	<p><strong>Inicio: </strong><?= date('d/m/Y H:i:s', strtotime($ticket_time[0]['registered_at']));?></p>
															   	<button id='btn-modal' class='btn btn-primary' value='<?= $chat_number[0]['id_chat'];?>' onClick='redirectToTicket(this.value)'>Visualizar Ticket</button>
																	</div>"><?php echo $chat_number[0]['id_chat'];?></button>

							            			<a href="#"></a>
							            			<input type="hidden" name="startedTime<?php echo $rand?>" value="<?php $ticket_time[0]['registered_at']?>">

						            				<?php 
						            					$element_to_limit_time = $chat['id_module'];
						            					$query = "SELECT limit_time FROM ticket_module WHERE id = ?";
						            					$testt = $prepareInstance->prepare($query, $element_to_limit_time, "all"); 
						            				?>

						            				<div class="progress" title="<?= (int)$minutos?> minuto(s) de <?= $testt[0]['limit_time']?>">
																	<progress id="pg" value="<?= (int)$minutos?>" max="<?= $testt[0]['limit_time']?>"></progress> 
																</div>
						            			</div>
						            		<?php endif; ?>
					            		<?php endif; ?>
												<?php endforeach; ?>
											</div>
										</div>
									</td>
									<?php $place_in_line_1++; ?>
								<?php endforeach; ?>
							</tr>
						</table>
					<?php else : ?>
						<div class="col-md-12">
							<span>Atenção! Fila não iniciada (Atendentes Off ou nenhum ticket atribuído).</span>
						</div>
					<?php endif; ?>
					<!-- |FIM| EXIBIÇÃO FILA GRUPO 1 -->
				</div>
			<hr>

			<h1>Disponibilidade Grupo 2</h1>
			<div class="row" id="internal-row">
	    	<!-- |INÍCIO| VERIFICAÇÃO E ORDENAMENTO FILA GRUPO 2 -->
				<?php 
					$finalized_queue_group_2 = array();
					$new_queue_group_2 = array();
					$fila = array();
					foreach ($row_initialized_queue_2 as $row){
						array_push($fila, $row['id_attendant']);
					};
					$contagem = array_count_values($fila);
				?>

				<?php 
					$order = new Order();
					$new_queue_group_2 = $order->orderByQuantity($contagem);

					$queue_according_data = $order->orderByDate($row_id_finalized_queue_2, $connection);

					for ($i = 0; $i <= $qtd_attendant_2_on_chat; $i++){ 
						if (!in_array($i+5, $new_queue_group_2)){ 
							$finded = 0;
							foreach ($queue_according_data as $key => $value){ 
								if($value[0] == $i+5 && $finded == 0){
									$finalized_queue_group_2[$key] = $i+5;
									$finded = 1;
								}
							}
						} 
					}

					ksort($finalized_queue_group_2);
					
					$queue_merge_2 = array_merge($finalized_queue_group_2, $new_queue_group_2); 
				?>
				<!-- |FIM| VERIFICAÇÃO E ORDENAMENTO FILA GRUPO 2 -->

				<!-- |INÍCIO| EXIBIÇÃO FILA GRUPO 2 -->
				<?php if($queue_merge_2 != null) : ?>
					<?php 
						$group_two = $array = [
						    "5" => "Fernando",
						    "6" => "Eduardo",
						    "7" => "Filipe",
						    "8" => "Rafael"
						];
						$place_in_line_2 = 1;
					?>
					<table align="center">
						<tr>
							<?php foreach($queue_merge_2 as $new_queue) {
								echo "<th class='place_in_line'>" . $place_in_line_2 . "º </th>";

								$place_in_line_2++;
							}?>
						</tr>
						<tr>
							<?php foreach($queue_merge_2 as $new_queue) : ?>	
								<?php $position = 0; ?>
								<td class="colum_of_place">	
									<div class="card mb-3 user<?=$new_queue?>" style="max-width: 18rem; float: left; margin-left: 3%;">
										<div class="card-header"><?= $group_two[$new_queue]; ?></div>
										<div class="card-body nivel2">
				            	<?php foreach ($chats as $chat) : ?>
				            		<?php if ($chat['t_group'] == "nivel2") : ?>
				            			<?php 
				            				$element_to_chat_number_and_ticket = $chat['id_chat'];
				            				$query = "SELECT id_chat FROM chat WHERE id = ?";
				            				$chat_number = $prepareInstance->prepare($query, $element_to_chat_number_and_ticket, "all");

				            				$query = "SELECT registered_at FROM ticket WHERE id_chat = ?"; 
				            				$ticket_time = $prepareInstance->prepare($query, $element_to_chat_number_and_ticket, "all");
			            				?>
		           		
				            			<?php if ($chat['id'] == $new_queue) : ?>
				            				<div>
				            					<?php 
					            					date_default_timezone_set('America/Sao_Paulo');
																$initial_time = new DateTime(date('Y/m/d H:i:s', strtotime($ticket_time[0]['registered_at']))); 
																$actual_time = new DateTime();

																$diff = $actual_time->diff($initial_time);
																$minutos = ($diff->h*60) + $diff->i + ($diff->s/60) + ($diff->days*24*60);

																$rand = substr($chat_number[0]['id_chat'], -1) + substr($chat_number[0]['id_chat'], -3, 1);
					            				?>

					            				<button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
																<p><strong>Chat / Ticket: </strong><?= $chat_number[0]['id_chat'];?></p>
												   			<p><strong>Inicio do chat: </strong><?= date('d/m/Y H:i:s', strtotime($ticket_time[0]['registered_at']));?></p>
												   			<button id='btn-modal' class='btn btn-primary' value='<?= $chat_number[0]['id_chat'];?>' onClick='redirectToTicket(this.value)'>Visualizar Ticket</button>
																</div>"><?php echo $chat_number[0]['id_chat'];?></button>

						            			<a href="#"></a>
						            			<input type="hidden" name="startedTime<?php echo $rand?>" value="<?php $ticket_time[0]['registered_at']?>">

					            				<?php 
					            					$element_to_limit_time = $chat['id_module'];
					            					$query = "SELECT limit_time FROM ticket_module WHERE id = ?";
					            					$testt = $prepareInstance->prepare($query, $element_to_limit_time, "all"); 
					            				?>

					            				<div class="progress" title="<?= (int)$minutos?> minuto(s) de <?= $testt[0]['limit_time']?>">
																<progress id="pg" value="<?= (int)$minutos?>" max="<?= $testt[0]['limit_time']?>"></progress>
															</div>
														</div>
					            		<?php endif; ?>
				            		<?php endif; ?>
											<?php endforeach; ?>
										</div>
									</div>
								</td>
								<?php $position++; ?>
							<?php endforeach; ?>
						</tr>
					</table>
				<?php else : ?>
					<div class="col-md-12">
						<span>Atenção! Fila não iniciada (Atendentes Off ou nenhum ticket atribuído).</span>
					</div>
				<?php endif; ?>
				<!-- |FIM| EXIBIÇÃO FILA GRUPO 2 -->
			</div>

			<br>

			<h1>Atendimentos</h1>

			<?php
				$elements_to_get_data_attendants = ["nivel1", "nivel2"];
				$query = "SELECT id, name, on_chat FROM employee WHERE t_group = ? OR t_group = ? ORDER BY t_group, name";
				$attendants = $prepareInstance->prepare($query, $elements_to_get_data_attendants, "all"); 
			?>

			<div class="row" id="internal-row">
				<table id="report" class="table table-sm table-bordered" align="center">
					<tr>
						<td></td>
						<td>Atendente</td>
						<?php for ($i = 0; $i < 8; $i++) : ?>
							<?php if($i == 0) : ?>
								<td>Hoje</td>
							<?php elseif($i == 1) : ?>
								<td>Ontem</td>
							<?php else : ?>
								<td><?php echo date('d/m', strtotime('-' .$i. ' days'));?></td>
							<?php endif;?>
						<?php endfor; ?>
					</tr>
					<?php foreach ($attendants as $attendant) : ?>
						<tr>
							<td><?= $attendant['on_chat'] == "yes" ? "<img src='img/is-on.png'></img>" : "<img src='img/is-off.png'></img>" ?></td>
							<td><?= explode(" ", $attendant['name'])[0] ?></td>
							
							<?php for ($j = 0; $j < 8; $j++) : ?> 
								<?php 
									$actual_date = date('Y-m-d', strtotime('-' .$j. ' days'));

									$elements_to_total_calls = [$attendant['id'], $actual_date."%"];
									$query = "SELECT COUNT(*) as total FROM ticket WHERE id_attendant = ? AND finalized_at LIKE ?";
									$total_calls = $prepareInstance->prepare($query, $elements_to_total_calls, "all");

									$elements_to_total_pendant = [$attendant['id'], "pendente", $actual_date."%"];
									$query = "SELECT COUNT(*) as total FROM ticket WHERE id_attendant = ? AND t_status = ? AND registered_at LIKE ?";
									$total_pendant = $prepareInstance->prepare($query, $elements_to_total_pendant, "all");

		        			$total = $total_calls[0]['total'] + $total_pendant[0]['total'];
	        			?>
	      				<td><?= $total; ?></td>
							<?php endfor; ?>
						</tr>
					<?php endforeach ?>
					<tr>
						<td></td>
						<td><strong>TOTAL</strong></td>
						<?php for ($j = 0; $j < 8; $j++) : ?>
							<td id="<?= $j+2?>"></td>
						<?php endfor; ?>
					</tr>
				</table>
				<div>
					<div style="font-size: 10px">
						<img src='img/is-on.png' style="height: 10px;"></img> Online
						<img src='img/is-busy.png' style="height: 10px; margin-left: 20px;"></img> Backup
						<img src='img/is-trainer.png' style="height: 10px; margin-left: 20px;"></img> Treinamento
						<img src='img/is-off.png' style="height: 10px; margin-left: 20px;"></img> Offline
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
	            <p>Brainsoft &copy; 2017-2019</p>
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
	  <script src="./js/jquery-3.2.1.min.js"></script>
	  <script src="./../js/jquery.mask.js"></script>
	  <script src="./js/front.js"></script>
	  <script src="./js/internal-queue.js"></script>
	  <script src="./jquery-ui.js"></script>
	  <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
	  <script src="./vendor/jquery.cookie/jquery.cookie.js"> </script>
	  <script src="./js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
	  <script src="./vendor/jquery-validation/jquery.validate.min.js"></script>
	  <script src="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
	  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
  </body>
</html>