<!--Inspect Booking-->
<br><br>
<div class="mini-container">
	<div class="jumbotron">
	<table class="table table-hover day" style="font-size:1.2rem">
		<tr class="table-<?php if (!$is_today) echo 'primary'; else echo 'info';?>">
			<th></th>
			<th><h3>Booking</h3></th>
		</tr>

		<tr>
			<th>Reference ID:</th>
			<td><?=$booking_id?></td>
		</tr>

		<tr>
			<th>Date:</th>
			<td><?=$fulldate?></td>
		</tr>
		<tr>
			<th>Time:</th>
			<td><?=$booking_data['timeslot']?></td>
		</tr>

		<tr>
			<th>Client:</th>
			<td><?=$booking_data['client']?></td>
		</tr>

		<tr>
			<th>Student:</th>
			<td><?=$booking_data['student']?></td>			
		</tr>
	</table>
	</div>
</div>
<br><br><br><br>