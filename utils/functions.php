<table class="table table-sm table-bordered" align="center">
	<tr>
		<td></td>
		<?php for ($i = 0; $i < 8; $i++) : ?> 
			<td><?php echo date('d/m', strtotime('-' .$i. ' days'));?></td>
		<?php endfor; ?>
	</tr>
	<?php foreach ($attendants as $attendant) : ?>
		<tr>
			<td><?= explode(" ", $attendant['name'])[0]?></td>
			
			<?php for ($j = 0; $j < 8; $j++) : ?> 
				<?php $actual_date = date('Y-m-d', strtotime('-' .$j. ' days'));
			
				$sql_total_calls = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM ticket WHERE id_attendant = ? AND finalized_at LIKE ?");
    			$sql_total_calls->execute(array($attendant['id'], "%".$actual_date."%")); $total_calls = 
    			$sql_total_calls->fetchAll();
				?>

    			<td><?= $total_calls[0]['total']; ?></td>
			<?php endfor; ?>
		</tr>
	<?php endforeach ?>
</table>