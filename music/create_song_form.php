<?php
session_start();

if ($_SESSION['user_id'] == "") {
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
		color: rgba(153, 122, 108, 1);
	}

	h1 {
		text-align: center;
	}

	.formcontainer {
		margin: 3.5em 3.5em;
	}

	.help-block {
		margin-bottom: -10px;
	}

	.btn {
		color: rgba(153, 122, 108, 1);
	}

</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">
				<h1>Add Song</h1>
				<form class="form-horizontal" action="create_song.php" method="post">
					<input type="hidden" name="song_id" value="<?= $song_id ?>">
					<input type="hidden" name="album_id" value="<?= $album_id ?>">
					<input type="hidden" name="artist_id" value="<?= $artist_id ?>">
					<input type="hidden" name="genre_id" value="<?= $genre_id ?>">
					<input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
					<div class="form-group <?= !empty($artisterror)?'has-error':'';?>">
						<label for="author">Artist</label>
					 	<input type="text" name="artist" class="form-control" id="artist" placeholder="Artist" value="<?= !empty($artist)?htmlspecialchars($artist):''; ?>">
					 	<?php if (!empty($artisterror)): ?>
					 		<span class="help-block"><?= $artisterror;?></span>
					 	<?php endif; ?>
					</div>
					<div class="form-group <?= !empty($titleerror)?'has-error':'';?>">
						<label for="title">Title</label>
					 	<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= !empty($title)?htmlspecialchars($title):''; ?>">
					 	<?php if (!empty($titleerror)): ?>
					 		<span class="help-block"><?= $titleerror;?></span>
					 	<?php endif; ?>
					</div>
					<div class="form-group <?= !empty($albumerror)?'has-error':'';?>">
						<label for="album">Album</label>
					 	<input type="text" name="album" class="form-control" id="album" placeholder="Album" value="<?= !empty($album)?htmlspecialchars($album):''; ?>">
					 	<?php if (!empty($albumerror)): ?>
					 		<span class="help-block"><?= $albumerror;?></span>
					 	<?php endif; ?>
					</div>
					<div class="form-group <?= !empty($genreerror)?'has-error':'';?>">
						<label for="genre">Genre</label>
					 	<input type="text" name="genre" class="form-control" id="genre" placeholder="Genre" value="<?= !empty($genre)?htmlspecialchars($genre):''; ?>">
					 	<?php if (!empty($genreerror)): ?>
					 		<span class="help-block"><?= $genreerror;?></span>
					 	<?php endif; ?>
					</div>
					<div class="form-group <?= !empty($yearerror)?'has-error':'';?>">
						<label for="year">Year</label>
						<input type="text" name="year" class="form-control" id="year" placeholder="Year" value="<?= !empty($year)&&is_numeric($year)?htmlspecialchars($year):''; ?>">
					 	<?php if (!empty($yearerror)): ?>
					 		<span class="help-block"><?= $yearerror;?></span>
					 	<?php endif; ?>
					</div>
					<div class="form-group formdiv">
					<input type="submit" class="btn btn-default" value="Create Song">
					<a class="btn btn-default" href="index.php?user_id=<?= $_SESSION['user_id'] ?>">Back</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>