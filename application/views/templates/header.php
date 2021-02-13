<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>AWC Salons - Abingdon & Witney College Student Salon Bookings</title>
</head>

<body>
	<!--Nav Bar-->
	<nav class='navbar navbar-expand-lg navbar-dark bg-primary navbar-fixed-top' id="navbar">
		<a class='navbar-brand' href='<?php echo base_url(); ?>'>AWC Salons</a>

		<div class="navbar-collapse collapse show" id="navbar-main" style="">
			<ul class='navbar-nav mr-auto' id='navbar-container'>

			<?php if(!$this->session->userdata('logged_in')) : ?>
				<li class="nav-item">
					<a class="nav-link" id='login' href='<?php echo base_url(); ?>users/login'>
						Log In
						<span class="sr-only" >(current)</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='register' href='<?php echo base_url(); ?>users/register'>
						Sign Up
						<span class="sr-only" >(current)</span>
					</a>
				</li>
			<?php endif; ?>

			<?php if($this->session->userdata('logged_in')) : ?>
				<li class="nav-item">
					<a class="nav-link" id='officials' href='<?php echo base_url(); ?>salon_calendar'>
						Calendar
						<span class="sr-only" >(current)</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='logout' href='<?php echo base_url(); ?>users/logout'>
						Log Out
						<span class="sr-only" >(current)</span>
					</a>
				</li>
			<?php endif; ?>

			</ul>
		</div>
	</nav>

	<br>
	<div class="container">
		<?php
			// Flash Messages:

            if($this->session->flashdata('user_registered')){
            echo '<div class="card text-white bg-success mb-3" style="max-width: 100%;">
                    <div class="card-body">
                    <h4 class="card-title">Success</h4>
                    <p class="card-text">'.$this->session->flashdata('user_registered').'</p>
                    </div>
            </div>';
            } 

            if($this->session->flashdata('user_success')){
                    echo '<p class="alert alert-success">'.$this->session->flashdata('user_success').'</p>';
            }

            if($this->session->flashdata('user_warning')){
                    echo '<p class="alert alert-warning">'.$this->session->flashdata('user_warning').'</p>';
            }

            if($this->session->flashdata('user_failed')){
                    echo '<p class="alert alert-danger">'.$this->session->flashdata('user_failed').'</p>';
            }
		?>
