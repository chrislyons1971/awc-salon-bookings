<!--Login Form-->
<br><br>
<div class="mini-container">
	<div class="jumbotron">
		<h2 class="text-center"> <?= $title ?> </h2> <br>

		<?php echo validation_errors(); ?>
		<?php echo form_open('users/login'); ?>

		<h3 class="text-center" style="margin-bottom: 1rem"> <?= $subtitle ?> </h3> 

		<div class="form-group">
			<input type="text" class="form-control" name="username" placeholder="Enter Username" required autofocus>
		</div>

		<div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Enter Password" required autofocus>
		</div>
		<button class="btn btn-primary btn-lg btn-block" type="submit">Log In</button>

		<?php echo form_close(); ?>
	</div>
</div>
<br><br><br><br>