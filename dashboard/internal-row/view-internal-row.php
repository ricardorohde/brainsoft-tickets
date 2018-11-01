<?php 
  session_start();
  if (!isset($_SESSION['internal_queue_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }

  date_default_timezone_set('America/Sao_Paulo');
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
			var contador = '30';

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
		$target_adm = "administrativo";
		$target_ticket = "tickets"; 
		$target_user = "usuarios";
		$target_registry = "cartorios";
		$target_registration_forms = "cadastros";
		$target_internal_queue = "fila-interna";
		$target_authorization = "autorizacoes";
		$target_report = "relatorios";
		
		$target_logout = "logout";
	?>

	<body>
	  <?php include ("../navs/navbar.php");?>
	  <div class="root-page forms-page">
	  	<?php include ("../navs/header.php");?>
	  	<?php
	      	/* TODOS OS CHATS ABERTOS*/
	      $sql_chats = $connection->getConnection()->prepare("SELECT id_module, t_group, id_chat, id_attendant as id, registered_at FROM ticket 
	      	WHERE t_status != ? AND t_status != ? AND t_status != ? ORDER BY id_chat desc");
	      $sql_chats->bindValue(1, "solucionado");
	      $sql_chats->bindValue(2, "fechado");
	      $sql_chats->bindValue(3, "pendente");
	      $sql_chats->execute(); 
	      $chats = $sql_chats->fetchAll();

	      /* QUANTIDADE DE FUNCIONÁRIOS CADASTRADOS GRUPO 1*/
	      $sql_count_attendant_1 = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM employee WHERE t_group = ?");
	      $sql_count_attendant_1->bindValue(1, "nivel1");
	      $sql_count_attendant_1->execute(); 
	      $row_count_attendant_1 = $sql_count_attendant_1->fetchAll();
	      $qtd_attendant_1 = $row_count_attendant_1[0]['total'];

	      /* QUANTIDADE DE FUNCIONÁRIOS HABILITADOS GRUPO 1*/
	      $sql_count_attendant_1_on_chat = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?");
	      $sql_count_attendant_1_on_chat->bindValue(1, "nivel1");
	      $sql_count_attendant_1_on_chat->bindValue(2, "yes");
	      $sql_count_attendant_1_on_chat->execute(); 
	      $row_count_attendant_1_on_chat = $sql_count_attendant_1_on_chat->fetchAll();
	      $qtd_attendant_1_on_chat = $row_count_attendant_1_on_chat[0]['total'];

	      /* GRUPO 1 */
	      $sql_initialize_queue_1 = $connection->getConnection()->prepare("SELECT id_attendant FROM ticket 
	      	WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :cont");
	      $sql_initialize_queue_1->bindValue(':status', "aberto", PDO::PARAM_STR);
	      $sql_initialize_queue_1->bindValue(':group', "nivel1", PDO::PARAM_STR);
	      $sql_initialize_queue_1->bindValue(':cont', (int) $qtd_attendant_1*2, PDO::PARAM_INT);
				$sql_initialize_queue_1->execute(); 
				$row_initialized_queue_1 = $sql_initialize_queue_1->fetchAll();

				$sql_id_finalized_queue_1 = $connection->getConnection()->prepare("SELECT DISTINCT id_attendant FROM ticket, employee WHERE 
					t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :cont");
	      $sql_id_finalized_queue_1->bindValue(':status', "aberto", PDO::PARAM_STR);
	      //$sql_id_finalized_queue_1->bindValue(':status2', "solucionado", PDO::PARAM_STR);
	      //$sql_id_finalized_queue_1->bindValue(':status3', "pendente", PDO::PARAM_STR);
	      $sql_id_finalized_queue_1->bindValue(':group', "nivel1", PDO::PARAM_STR);
	      $sql_id_finalized_queue_1->bindValue(':active', "yes", PDO::PARAM_STR);
	      $sql_id_finalized_queue_1->bindValue(':cont', (int) $qtd_attendant_1, PDO::PARAM_INT);
				$sql_id_finalized_queue_1->execute(); 
				$row_id_finalized_queue_1 = $sql_id_finalized_queue_1->fetchAll();

				/* QUANTIDADE DE FUNCIONÁRIOS CADASTRADOS GRUPO 2*/
	      $sql_count_attendant_2 = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM employee WHERE t_group = ?");
	      $sql_count_attendant_2->bindValue(1, "nivel2");
	      $sql_count_attendant_2->execute(); 
	      $row_count_attendant_2 = $sql_count_attendant_2->fetchAll();
	      $qtd_attendant_2 = $row_count_attendant_2[0]['total'];

	      /* QUANTIDADE DE FUNCIONÁRIOS HABILITADOS GRUPO 2*/
	      $sql_count_attendant_2_on_chat = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?");
	      $sql_count_attendant_2_on_chat->bindValue(1, "nivel2");
	      $sql_count_attendant_2_on_chat->bindValue(2, "yes"); 
	      $sql_count_attendant_2_on_chat->execute();
	      $row_count_attendant_2_on_chat = $sql_count_attendant_2_on_chat->fetchAll();
	      $qtd_attendant_2_on_chat = $row_count_attendant_2_on_chat[0]['total'];

				/* GRUPO 2 */
				$sql_initialize_queue_2 = $connection->getConnection()->prepare("SELECT id_attendant FROM ticket 
					WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :cont");
				$sql_initialize_queue_2->bindValue(':status', "aberto", PDO::PARAM_STR);
				$sql_initialize_queue_2->bindValue(':group', "nivel2", PDO::PARAM_STR);
				$sql_initialize_queue_2->bindValue(':cont', (int) $qtd_attendant_2*2, PDO::PARAM_INT);
				$sql_initialize_queue_2->execute();  
				$row_initialized_queue_2 = $sql_initialize_queue_2->fetchAll();

				$sql_finalized_queue_2 = $connection->getConnection()->prepare("SELECT DISTINCT id_attendant FROM ticket, employee 
					WHERE t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :cont");
				$sql_finalized_queue_2->bindValue(':status', "aberto", PDO::PARAM_STR);
	      $sql_finalized_queue_2->bindValue(':group', "nivel2", PDO::PARAM_STR);
	      $sql_finalized_queue_2->bindValue(':active', "yes", PDO::PARAM_STR);
	      $sql_finalized_queue_2->bindValue(':cont', (int) $qtd_attendant_2, PDO::PARAM_INT);
				$sql_finalized_queue_2->execute(); 
				$row_id_finalized_queue_2 = $sql_finalized_queue_2->fetchAll();
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
		    			 	$finalized_queue_group_1 = array();
		    				$fila = array();
		    				foreach ($row_initialized_queue_1 as $row) {
		    					array_push($fila, $row['id_attendant']);
		    				} 
								$contagem = array_count_values($fila); 
							?>

							<?php 
								$queue_according_data = array();
								$maior = "0000-00-00 00:00:01";
								foreach ($row_id_finalized_queue_1 as $id_attendant){
									$sql_finalized_queue_1 = $connection->getConnection()->prepare("SELECT id_attendant, registered_at FROM ticket WHERE id_attendant = ? ORDER BY id DESC LIMIT 1");
									$sql_finalized_queue_1->execute(array($id_attendant['id_attendant'])); 
									$row_finalized_queue_1 = $sql_finalized_queue_1->fetch();

									$last_date = $row_finalized_queue_1['registered_at'];
									$final_date = strtotime($last_date);

									if ($final_date > strtotime($maior)){
										$maior = $last_date;
										array_push($queue_according_data, $row_finalized_queue_1['id_attendant']);
									} else{
										array_unshift($queue_according_data, $row_finalized_queue_1['id_attendant']);
									}
								}
							?>

							<?php 
								$order = new Order($finalized_queue_group_1);
								$new_queue_group_1 = $order->orderByQuantity($contagem);

								class Order{
									public $has_last;
									public $qtd_last;
									public $qtd_elements_in_finalized_queue;

									function __construct($finalized_queue_group){
										$this->has_last = 0;
										$this->qtd_last = 0;
										$this->qtd_elements_in_finalized_queue = count($finalized_queue_group);
									}

									function orderByQuantity($contagem){
										$queue = array();
										foreach($contagem as $numero => $vezes){
											if($vezes == 2){
												$this->has_last = 1;
												$this->qtd_last = $this->qtd_last + 1;
												array_unshift($queue, $numero);
											}
										}

										foreach($contagem as $numero => $vezes){
											if($vezes == 1){
												if($this->has_last == 0){
													array_unshift($queue, $numero);
												} else{
													$last_position_available = $this->qtd_elements_in_finalized_queue - $this->qtd_last;
													array_splice($queue, $last_position_available, 0, $numero);
												}
											}
										}

										return $queue;
									}
								}
								
							?>

							<?php 
								for ($i = 0; $i <= $qtd_attendant_1_on_chat; $i++){ 
									if (!in_array($i+2, $new_queue_group_1)) { 
										$finded = 0;
										foreach ($queue_according_data as $key => $value){
											if($value[0] == $i+2 && $finded == 0){
												$finalized_queue_group_1[$key] = $i+2;
												$finded = 1;
											}
										}
									} 
								}

								ksort($finalized_queue_group_1);

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
					            				$sql_chat_number = $connection->getConnection()->prepare("SELECT id_chat FROM chat WHERE id = ?");
					            				$sql_chat_number->execute(array($chat['id_chat'])); 
					            				$chat_number = $sql_chat_number->fetchAll(); 

					            				$sql_ticket_time = $connection->getConnection()->prepare("SELECT registered_at FROM ticket WHERE id_chat = ?"); 
					            				$sql_ticket_time->execute(array($chat['id_chat'])); 
					            				$ticket_time = $sql_ticket_time->fetchAll();
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
							            			<input type="hidden" name="startedTime<?php echo $rand?>" value="<?php $chat_number[0]['opening_time']?>">

						            				<?php 
							            				$test = $connection->getConnection()->prepare("SELECT limit_time FROM ticket_module WHERE id = ?"); 
							            				$test->execute(array($chat['id_module'])); 
							            				$testt = $test->fetchAll(); 
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

					$has_last = false;
					$qtd_last = 0;
					$qtd_elements_in_finalized_queue = count($finalized_queue_group_2);
				?>

				<?php 
					$queue_according_data = array();
					$maior = "0000-00-00 00:00:01";

					foreach ($row_id_finalized_queue_2 as $id_attendant){
						$sql_finalized_queue_2 = $connection->getConnection()->prepare("SELECT id_attendant, registered_at FROM ticket 
							WHERE id_attendant = ? ORDER BY id DESC LIMIT 1");
						$sql_finalized_queue_2->execute(array($id_attendant['id_attendant'])); 
						$row_finalized_queue_2 = $sql_finalized_queue_2->fetch();

						$last_date = $row_finalized_queue_2['registered_at'];
						$final_date = strtotime($last_date);

						if ($final_date > strtotime($maior)){
							$maior = $last_date;
							array_push($queue_according_data, $row_finalized_queue_2['id_attendant']);
						} else{
							array_unshift($queue_according_data, $row_finalized_queue_2['id_attendant']);
						}
					}

					$order = new Order($finalized_queue_group_2);
					$new_queue_group_2 = $order->orderByQuantity($contagem);

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
				            				$sql_chat_number = $connection->getConnection()->prepare("SELECT id, id_chat, opening_time FROM chat WHERE id = ?"); 
				            				$sql_chat_number->execute(array($chat['id_chat'])); 
				            				$chat_number = $sql_chat_number->fetchAll(); 
			            				?>
		           		
				            			<?php if ($chat['id'] == $new_queue) : ?>
				            				<div>
				            					<?php 
					            					date_default_timezone_set('America/Sao_Paulo');
																$initial_time = new DateTime(date('Y/m/d H:i:s', strtotime($chat_number[0]['opening_time']))); 
																$actual_time = new DateTime();

																$diff = $actual_time->diff($initial_time);
																$minutos = ($diff->h*60) + $diff->i + ($diff->s/60) + ($diff->days*24*60);

																$rand = substr($chat_number[0]['id_chat'], -1) + substr($chat_number[0]['id_chat'], -3, 1);
					            				?>

					            				<button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
																<p><strong>Chat / Ticket: </strong><?= $chat_number[0]['id_chat'];?></p>
												   			<p><strong>Inicio do chat: </strong><?= date('d/m/Y H:i:s', strtotime($chat_number[0]['opening_time']));?></p>
												   			<button id='btn-modal' class='btn btn-primary' value='<?= $chat_number[0]['id_chat'];?>' onClick='redirectToTicket(this.value)'>Visualizar Ticket</button>
																</div>"><?php echo $chat_number[0]['id_chat'];?></button>

						            			<a href="#"></a>
						            			<input type="hidden" name="startedTime<?php echo $rand?>" value="<?php $chat_number[0]['opening_time']?>">

					            				<?php 
					            					$test = $connection->getConnection()->prepare("SELECT limit_time FROM ticket_module WHERE id = ?"); 
					            					$test->execute(array($chat['id_module'])); 
					            					$testt = $test->fetchAll(); 
					            				?>

					            				<div class="progress" title="<?= (int)$minutos?> minuto(s) de <?= $testt[0]['limit_time']?>">
																<progress id="pg" value="<?= (int)$minutos?>" max="<?= $testt[0]['limit_time']?>"></progress>
															</div>

															<?php 
																$sql_horario = $connection->getConnection()->prepare("SELECT registered_at FROM ticket 
																	WHERE id_chat = ? ORDER BY id DESC LIMIT 1");
																$sql_horario->execute(array($chat_number[0]['id'])); 
																$result_horario = $sql_horario->fetch();

																$horario = date("H:i:s", strtotime($result_horario['registered_at']));
															?>

															<p><?= $horario ?></p>
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
				$sql_attendants = $connection->getConnection()->prepare("SELECT id, name, on_chat FROM employee 
					WHERE t_group = ? OR t_group = ? ORDER BY t_group, name");
	      $sql_attendants->execute(array("nivel1", "nivel2")); 
	      $attendants = $sql_attendants->fetchAll();
			?>

			<div class="row" id="internal-row">
				<table class="table table-sm table-bordered" align="center">
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
							<td><?= explode(" ", $attendant['name'])[0]?></td>
							
							<?php for ($j = 0; $j < 8; $j++) : ?> 
								<?php 
								$actual_date = date('Y-m-d', strtotime('-' .$j. ' days'));
								$sql_total_calls = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM ticket 
									WHERE id_attendant = ? AND finalized_at LIKE ?");
	        			$sql_total_calls->execute(array($attendant['id'], $actual_date."%")); 
	        			$total_calls = $sql_total_calls->fetchAll();

	        			$sql_total_pendant = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM ticket 
	        				WHERE id_attendant = ? AND t_status = ? AND registered_at LIKE ?");
	        			$sql_total_pendant->execute(array($attendant['id'], "pendente", $actual_date."%")); 
	        			$total_pendant = $sql_total_pendant->fetchAll();

	        			$total = $total_calls[0]['total'] + $total_pendant[0]['total'];
	        		?>
	      				<td><?= $total; ?></td>
							<?php endfor; ?>
						</tr>
					<?php endforeach ?>
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