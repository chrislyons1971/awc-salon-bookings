<!--Inspect Class Sessions on Day-->
<br>
<?php $n=0; foreach ($salons as $salon) $n++; ?>

<table class="table table-hover text-center day" style="font-size:1.5rem">
	<tr class="table-<?php if (!$is_today) echo 'primary'; else echo 'info';?>">
		<th><br></th>
		<th colspan="<?php echo $n-1 ?>"><h3>Class Sessions on <?=$fulldate?></h3></th>
		<th><small><?php if (!$is_today) echo '<br>'; else echo 'TODAY';?></small></th>
	</tr>
	<tr class="table-dark" style="color: black">
		<th>Time</th>
		<?php foreach ($salons as $salon) echo '<th>'.$salon['Salon_Room_Name'].'<br><small>('.$salon['Salon_Type'].')</small></th>'; ?>
	</tr>

    <?php for ($i=8; $i<22; $i++) { ?>
		<tr class="table-secondary">
			<th><?php echo $i.':00' ?></th>

			<?php 
			foreach ($salons as $salon) {
				if(isset($sessions[$i]) && isset($sessions_room[$i])
						&& $sessions_room[$i] == $salon['Salon_Room_Name']) {
					echo '<td><a class="table-success" href="'.$day.'/'.$sessions[$i]['Class_Session_ID'].'">Booked by Class '.$sessions[$i]['Class_Name'].'</a></td>';
				} else { 
					echo '<td><br></td>';
				}			
			}
			?>

		</tr>
	<?php } ?>
	<tr class="table-primary">
		<td><br></td>
		<td colspan="<?php echo $n-1 ?>" ><a class="table-success" href="add">Add Class Session</a></td>
		<td><br></td>
	</tr>
</table>


<br><br><br>