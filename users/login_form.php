<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
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

	h1 {
		text-align: center;
		font-size: 3em;
		color: grey;
	}

	.center {
		text-align: center;
		margin: 0 auto;
	}

	.formcontainer {
		border: 1px solid grey;
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
			<div class="col-md-6 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">
				<h1>Login</h1>
				<br />
				<form class="form-horizontal" action="login.php" method="post">
				    <div class="form-group <?= !empty($nameerror)?'has-error':'';?>">
				    	<div class="input-group">
				    	 	<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
					        <input type="text" name="name" class="form-control" id="name" placeholder="Username" value="<?= !empty($name)?htmlspecialchars($name):'';?>">
				        </div>
				        <?php if (!empty($nameerror)): ?>
				 		<span class="help-block"><?= $nameerror;?></span>
				 		<?php endif; ?>
				    </div>
				    <div class="form-group <?= !empty($passworderror)?'has-error':'';?>"> 
				        <div class="input-group">
				    	 	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
				        <input type="password" name="password" class="form-control" id="password" placeholder="Password" value="<?= !empty($password)?htmlspecialchars($password):'';?>">
				        </div>
				        <?php if (!empty($passworderror)): ?>
			 			<span class="help-block"><?= $passworderror;?></span>
			 			<?php endif; ?>
				    </div>
				    <br />
				    <div class="form-group center">
				    <input type="submit" name="submit" class="btn btn-default" value="Submit" />
				    <a href='index.php' class='btn btn-default'>Back</a>
				    </div>
				</form>	
			</div>
		</div>
	</div>
</body>
</html>