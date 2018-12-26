<?php 
	date_default_timezone_set('America/Sao_Paulo');

  include_once __DIR__.'/../../utils/controller/ctrl-queue.php';
	$queueController = QueueController::getInstance(); 	
	$queueController->verifyPermission();
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

    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="./css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="./css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="shortcut icon" href="favicon.png">

    <script type="text/javascript">
			var contador = '60';

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

	<body>
	  <?php include ("../navs/navbar.php");?>
	  <div class="root-page forms-page">
	  	<?php include ("../navs/header.php");?>
	  	<?php 
	  		$queueController->setPrepareInstance($prepareInstance); 
	  		$queueController->openChats();

	  		$queueController->attendantsAbleOnGroup1();
	  		$queueController->makeQueueToGroup1();

	  		$queueController->attendantsAbleOnGroup2();
	  		$queueController->makeQueueToGroup2();

	  		$queueGroup1 = $queueController->getOrderedQueue(1);
	  		$queueGroup2 = $queueController->getOrderedQueue(2);
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
		    		
					<?php if($queueGroup1 != null) : ?>
						<?php 
							$group_one       = $queueController->attendantsOnGroup("nivel1");
							$place_in_line_1 = 1;
						?>
						<table align="center">
							<tr>
								<?php foreach($queueGroup1 as $new_queue) {
									echo "<th class='place_in_line'>" . $place_in_line_1 . "º </th>";

									$place_in_line_1++;
								}?>
							</tr>
							<tr>
								<?php foreach($queueGroup1 as $new_queue) : ?>	
									<td class="colum_of_place">
										<div class="card mb-3 user<?=$new_queue?>" style="max-width: 18rem; float: left; margin-left: 3%;">
											<div class="card-header"><?=$group_one[$new_queue]; ?></div>
											<div class="card-body nivel1">
					            	<?php foreach ($queueController->getAllOpenChats() as $chat) : ?>
					            		<?php if ($chat['t_group'] == "nivel1") : ?>
					            			<?php 
					            				$chat_number = $queueController->chatNumberToUseInLink($chat['id_chat']);
				            					$ticket_time = $queueController->timeOfTicket($chat['id_chat']);
					            			?>
			           		
					            			<?php if ($chat['id'] == $new_queue) : ?>
					            				<div>
						            				<?php $minutos = $queueController->progressBar($ticket_time[0]['registered_at']); ?>

						            				<button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
															   	<p><strong>Ticket: </strong><?= $chat_number[0]['id_chat'] ?></p>
															   	<p><strong>Inicio: </strong><?= date('d/m/Y H:i:s', strtotime($ticket_time[0]['registered_at'])) ?></p>
															   	<button id='btn-modal' class='btn btn-primary' value='<?= $chat_number[0]['id_chat'];?>' onClick='redirectToTicket(this.value, <?= $new_queue ?>)'>Visualizar Ticket</button>
																	</div>"><?= $chat_number[0]['id_chat'];?></button>

							            			<a href="#"></a>
							            			<input type="hidden" name="startedTime<?= $rand ?>" value="<?php $ticket_time[0]['registered_at']?>">

						            				<?php $time = $queueController->limitTimeToFinish($chat['id_module']); ?>

						            				<div class="progress" title="<?= (int)$minutos?> minuto(s) de <?= $time[0]['limit_time']?>">
																	<progress id="pg" value="<?= (int)$minutos?>" max="<?= $time[0]['limit_time']?>"></progress> 
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
				</div>
			<hr>
			<h1>Disponibilidade Grupo 2</h1>
			<div class="row" id="internal-row">
				<?php if($queueGroup2 != null) : ?>
					<?php 
						$group_two       = $queueController->attendantsOnGroup("nivel2");
						$place_in_line_2 = 1;
					?>
					<table align="center">
						<tr>
							<?php foreach($queueGroup2 as $new_queue) {
								echo "<th class='place_in_line'>" . $place_in_line_2 . "º </th>";

								$place_in_line_2++;
							}?>
						</tr>
						<tr>
							<?php foreach($queueGroup2 as $new_queue) : ?>	
								<?php $position = 0; ?>
								<td class="colum_of_place">	
									<div class="card mb-3 user<?=$new_queue?>" style="max-width: 18rem; float: left; margin-left: 3%;">
										<div class="card-header"><?= $group_two[$new_queue]; ?></div>
										<div class="card-body nivel2">
				            	<?php foreach ($queueController->getAllOpenChats() as $chat) : ?>
				            		<?php if ($chat['t_group'] == "nivel2") : ?>
				            			<?php 
				            				$chat_number = $queueController->chatNumberToUseInLink($chat['id_chat']);
				            				$ticket_time = $queueController->timeOfTicket($chat['id_chat']);
			            				?>
		           		
				            			<?php if ($chat['id'] == $new_queue) : ?>
				            				<div>
				            					<?php $minutos = $queueController->progressBar($ticket_time[0]['registered_at']); ?>

					            				<button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
																<p><strong>Chat / Ticket: </strong><?= $chat_number[0]['id_chat'];?></p>
												   			<p><strong>Inicio do chat: </strong><?= date('d/m/Y H:i:s', strtotime($ticket_time[0]['registered_at']));?></p>
												   			<button id='btn-modal' class='btn btn-primary' value='<?= $chat_number[0]['id_chat'];?>' onClick='redirectToTicket(this.value, <?= $new_queue ?>)'>Visualizar Ticket</button>
																</div>"><?= $chat_number[0]['id_chat'] ?></button>

						            			<a href="#"></a>
						            			<input type="hidden" name="startedTime<?= $rand ?>" value="<?php $ticket_time[0]['registered_at']?>">

					            				<?php $time = $queueController->limitTimeToFinish($chat['id_module']); ?>

					            				<div class="progress" title="<?= (int)$minutos?> minuto(s) de <?= $time[0]['limit_time']?>">
																<progress id="pg" value="<?= (int)$minutos?>" max="<?= $time[0]['limit_time']?>"></progress>
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
								<td><?= date('d/m', strtotime('-' .$i. ' days'));?></td>
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

									$elements_to_total_calls = [$attendant['id'], $actual_date."%", "pendente", $actual_date."%"];
									$query = "SELECT COUNT(*) as total FROM ticket WHERE 
										id_attendant = ? AND (finalized_at LIKE ? OR (t_status = ? AND registered_at LIKE ?))";

									$total_calls = $prepareInstance->prepare($query, $elements_to_total_calls, "");
	        			?>
	      				<td><?= $total_calls['total']; ?></td>
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
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
	  <script src="./js/jquery-3.2.1.min.js"></script>
	  <script src="./js/front.js"></script>
	  <script src="./js/queue.js"></script>
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