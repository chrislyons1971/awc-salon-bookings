<!--Register Form-->

<div class="mini-container">
	<div class="jumbotron">
		<h2 class="text-center"> <?= $title ?> </h2>
		<br>
		<?php echo validation_errors(); ?>
		<?php echo form_open('users/register'); ?>

		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" placeholder="j.smith1" required autofocus>
		</div>

		<div class="form-group">
			<label for="forename">Forename</label>
			<input type="text" class="form-control" name="forename" placeholder="John" required autofocus>
		</div>

		<div class="form-group">
			<label for="surname">Surname</label>
			<input type="text" class="form-control" name="surname" placeholder="Smith" required autofocus>
		</div>

		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" class="form-control" name="email" placeholder="j.smith1@abingdon-witney.ac.uk" required autofocus>
		</div>

		<div class="form-group">
			<label for="password">Set Password</label>
			<input type="password" class="form-control" name="password" placeholder="Remember to use letters and numbers" required autofocus>
		</div>

		<div class="form-group">
			<label for="password2">Confirm Password</label>
			<input type="password" class="form-control" name="password2" required autofocus>
		</div>
		<div class="form-group">
			<label for="isadmin">Select Privilege Level</label>
			<select class="custom-select" name="isadmin">
				<option value="1">Customer</option>
				<option value="2">Teacher</option>
				<option value="3">Technician</option>
			</select>
		</div>

		<!--div class="form-check disabled">
			<label for="isadmin" class="form-check-label">
				<input type="checkbox" class="form-check-input">
				Allow admin privileges for account?
			</label>
		</div-->
		<br>
		<button type="submit" class="btn btn-primary btn-lg">Submit</button>

		<?php echo form_close(); ?>
	</div>
</div>
<br>