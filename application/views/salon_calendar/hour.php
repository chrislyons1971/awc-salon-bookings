<!--Inspect Salon Sessions in Class-->
<br>
<?php
$n=0;
foreach ($workspaces as $workspace)
	if($workspace['Workspace_Type'] == $type
			&& $workspace['Salon_Room_Name'] == $salon_room)
		$n++;
?>

<table class="table table-hover text-center day" style="font-size:1.5rem">
	<tr class="table-<?php if (!$is_today) echo 'primary'; else echo 'info';?>">
		<th colspan="<?php echo $n+1 ?>"><h3>Salon Sessions on <?=$fulldate?></h3></th>
	</tr>

	<tr class="table-dark" style="color: black">
		<th>Timeslot</th>
		<?php
		//show workspaces 
		foreach ($workspaces as $workspace)
			if($workspace['Workspace_Type'] == $type && $workspace['Salon_Room_Name'] == $salon_room)
				echo '<th style="padding-left:2px;padding-right:2px;">'.$workspace['Workspace_ID'].'</th>';
		?>
	</tr>

    <?php for ($i=0; $i<60; $i+=15) {  ?>
		<tr class="table-secondary">
			<th><?php if($i==0) echo $hour.':00'; else echo $hour.':'.$i; ?></th>

			<?php // FILTER BOOKED WORKSPACES OF TYPE
			foreach ($workspaces as $workspace){
				if($workspace['Workspace_Type'] == $type && $workspace['Salon_Room_Name'] == $salon_room){
					if(isset($sessions[$i]) && isset($sessions_workspace[$i])
							&& $sessions_workspace[$i]['workspace'] == $workspace['Workspace_ID']
							&& $sessions_workspace[$i]['timeslot'] == $i)
						echo '<td style="padding-left:2px;padding-right:2px; "><a class="table-success" href="'.$cs_id.'/'.$sessions_workspace[$i]['booking'].'">'.$sessions_workspace[$i]['student'].'</a></td>';
					else echo '<td style="padding-left:2px;padding-right:2px; "><br></td>';
				}
			}
			?>

		</tr>
	<?php } ?>
	<tr class="table-primary">
		<td><br></td>
		<td colspan="<?php echo $n-1 ?>" ><a class="table-success" href="<?=$cs_id?>/add">Book Salon Session</a></td>
		<td><br></td>
	</tr>
</table>


<br><br><br>