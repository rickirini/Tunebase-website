<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register a user</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="../css/index.css">
<style>

	body {
		font-size: 1.5em;
		background: url('tunebasement.jpg') no-repeat center center fixed;
		background-size: cover;
	}

	.formcontainer {
		border: 1px solid grey;
		margin: 5em 0 5em -2em;
	}

	h1 {
		font-size: 3em;
		text-align: center;
		color: grey;
	}

	.center {
		text-align: center;
	}

	.btn {
		color: grey;
	}

	.glyphicon {
		color: grey;
	}

</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-7 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">
				<h1>Register a new user account</h1>
				<br />
				<form class="form-horizontal" action="create.php" method="post">
		            <input type="hidden" name="user_id" value="<?= $user_id ?>"> 
				    <div class="form-group <?= !empty($nameerror)?'has-error':'';?>">
				        <div class="input-group">
						    <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
				        	<input type="text" name="name" class="form-control" id="name" placeholder="Username" value="<?= !empty($name)?htmlspecialchars($name):'';?>">
				        </div>
				        <?php if (!empty($nameerror)): ?>
				 		<span class="help-block"><?= $nameerror;?></span>
				 		<?php endif; ?>
				    </div>
				    <div class="form-group <?= !empty($emailerror)?'has-error':'';?>">
				        <div class="input-group">
						    <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
				        	<input type="text" name="user_email" class="form-control" id="email" placeholder="Email" value="<?= !empty($user_email)?htmlspecialchars($user_email):'';?>">
				        </div>
				        <?php if (!empty($emailerror)): ?>
				 		<span class="help-block"><?= $emailerror;?></span>
				 		<?php endif; ?>
				    </div>
				    <div class="form-group <?= !empty($passworderror)?'has-error':'';?>">
				        <div class="input-group">
						    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
				       		<input type="password" name="password" class="form-control" id="password" placeholder="Password" value="<?= !empty($password)&&($password == $password1)?htmlspecialchars($password):''; ?>">
				       	</div>
				       	<?php if (!empty($passworderror)): ?>
			 			<span class="help-block"><?= $passworderror;?></span>
			 			<?php endif; ?>
				    </div>
				    <div class="form-group <?= !empty($password1error)?'has-error':'';?>">
				        <div class="input-group">
						    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
				       		<input type="password" name="password1" class="form-control" id="password1" placeholder="Repeat password" value="<?= !empty($password1)&&($password == $password1)?htmlspecialchars($password1):''; ?>">
				       	</div>
				       	<?php if (!empty($password1error)): ?>
			 			<span class="help-block"><?= $password1error;?></span>
			 			<?php endif; ?>
				    </div>
				    <div class="form-group center">
				    <input type="submit" name="submit" id="submit" class="btn btn-default" value="Register" />
				    <a href='index.php' class='btn btn-default'>Back</a>
				    </div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>