<?php
session_start();
require_once("../config.inc.php");
?>
<!DOCTYPE html>
<?php

	if (!isset($_SESSION['user_id'])) {
		header('Location: ../users/index.php');
	} else {
		$user_id = $_SESSION['user_id'];
	}

	$playlist_id = $_SESSION['playlist_id'];
	$name = $_SESSION['playlist_name'];
	
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Delete Playlist</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="../css/index.css">

<style>

	body {
		background: #fff url("../users/tunebasement.jpg") no-repeat fixed center;
		background-size:cover;
	}

	h1, p {
		text-align: center;
	}

	p {
		font-size: 1.2em;
	}

	.btn {
		float: middle;
	}



</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">
	
				<h1>Delete Playlist</h1>
				<p>Are you sure you want to delete playlist: <?= $name ?>?</p>
				<br />
										
				<div class="form-group formdiv">
					<form action="delete_playlist.php" method="post">
						<input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
						<input type="submit" class="btn btn-default" value="Yes, delete">
						<a class="btn btn-default" href="index.php?user_id=<?= $_SESSION['user_id'] ?>&playlistId=<?= $_SESSION['playlist_id']?>">Back</a>
					</form> 
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>