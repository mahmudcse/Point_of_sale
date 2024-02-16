<html>

	<head>

	 	<link href="<?php echo base_url(). "assets/css/layout.css"?>" rel="stylesheet" type="text/css">	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="<?php echo base_url();?>assets/assets/jquery/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/assets/jquery/jquery-ui.min.css"/>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        
		<title>Login Page | Point of Sale</title>

	</head>

	<body class="loginbody">

		<div id="loginbox">
			<section id="content">
	
				<?php echo form_open('login/authenticate');?>
	
				<h1>Login Form</h1>
				<div class="container">
					<div class="row top-buffer">
						<div class="col-md-5">
							<input type="text" class="form-control"  placeholder="Username" required="required" id="username" name="username"/>
						</div>
						
					</div>
					<div class="row  top-buffer">
						<div class="col-md-5">
							<input type="password" class="form-control" placeholder="Password" required="required" id="password" name="password"/>
						</div>
					</div>
					<div class="row  top-buffer">
						<div class="col-md-4">
							<button class="btn btn-default" name="submit"> Login </button>
						</div>
					</div>
					
				</div>
	
			<?php echo form_close();?>
	
		</section>
		<section style="margin-top: 50px;color:white;">
			<div class="container">
				<div class="row  top-buffer">
					<div class="col-md-12">
						<small><strong>Developed and Maintained by NetSOFT Ltd. (01824412272)</strong></small>
					</div>
				</div>
			</div>
		</section>
	
		</div> 
	</body>

</html>