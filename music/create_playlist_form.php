<?php 
session_start();

if (empty($_SESSION['user_id'])) {
	header("Location: ../users/index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add song</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="../css/index.css">

<style>

	body {
		background: #fff url("../users/tunebasement.jpg") no-repeat fixed center;
		background-size: cover;
	}

	h1 {
		text-align: center;
	}


	.help-block {
		margin-bottom: -10px;
	}

</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-xs-offset-1 col-md-6 col-md-offset-0 formcontainer">

				<h1>Add Playlist</h1>
				<form class="form-horizontal" action="create_playlist.php" method="post">
					<input type="hidden" name="playlist_id" value="<?= $playlist_id ?>">					
					<input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
				    <div class="form-group <?= !empty($nameerror)?'has-error':'';?>">
						<label for="name">Playlist name</label>
					 	<input type="text" name="name" class="form-control" id="name" placeholder="Playlist name" value="<?= !empty($name)?htmlspecialchars($name):''; ?>">
					 	<?php if (!empty($nameerror)): ?>
					 		<span class="help-block"><?= $nameerror;?></span>
					 	<?php endif; ?>
					</div>
				
				
					<div class="form-group formdiv">
					<input type="submit" class="btn btn-default" value="Create Playlist">
					<a class="btn btn-default" href="index.php?user_id=<?= $_SESSION['user_id'] ?>">Back</a>
					</div> 
				</form> 
			</div>
		</div>
	</div>
</body>
</html>