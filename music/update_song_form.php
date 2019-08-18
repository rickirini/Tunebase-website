<?php
	session_start();
	require_once("../config.inc.php");
	if (!isset($_SESSION['user_id'])) {
		header("Location: ../users/index.php");
	} else {
		$user_id = $_SESSION['user_id'];
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update song</title>
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

	.formcontainer {
		margin: 3.5em 3.5em
	}


</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-xs-6 formcontainer">
				<h1>Update song</h1>
				<?php

					$song_id = $_GET['song_id'];


					$qh = $dbh->prepare("SELECT artists.name as artist, artists.artist_id as artist_id, albums.album_id as album_id, genres.genre_id as genre_id, songs.title as title, songs.year as year, albums.title as album, genres.name as genre, songs.song_id as song_id
															 FROM artist_song
															 INNER JOIN artists ON artist_song.artist_id = artists.artist_id
															 INNER JOIN songs ON artist_song.song_id = songs.song_id
															 INNER JOIN song_genre ON artist_song.song_id = song_genre.song_id
															 INNER JOIN genres ON song_genre.genre_id = genres.genre_id
															 INNER JOIN album_song ON artist_song.song_id = album_song.song_id
															 INNER JOIN albums ON album_song.album_id = albums.album_id
															 INNER JOIN artist_album ON albums.album_id = artist_album.album_id
															 WHERE songs.song_id = :song_id");


					$qh->execute(array(':song_id' => $song_id));

					$count = $qh->rowCount();

					$user_id = $_SESSION['user_id'];

					foreach($qh as $row) {
						$artist = $row['artist'];
						$title = $row['title'];
						$album = $row['album'];
						$genre = $row['genre'];
						$old_artist_id = $row['artist_id'];
						$old_album_id = $row['album_id'];
						$old_genre_id = $row['genre_id'];
						$year = $row['year'];
					}
				?>
				<form action="update_song.php" method="post">
					<input type="hidden" name="song_id" value="<?= $song_id ?>">
					<input type="hidden" name="user_id" value="<?= $user_id ?>">


						<!-- menu item -->
							<?php

									$stmt = $dbh->prepare(" SELECT artists.name as artistname, albums.title as albumtitle, artists.artist_id as artistid, albums.album_id as albumid
					                                FROM artists
																					INNER JOIN artist_album ON artists.artist_id = artist_album.artist_id
																					INNER JOIN albums ON artist_album.album_id = albums.album_id");

				          $stmt->execute();
				          $count = $stmt->rowCount();
				          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


									$stmt2 = $dbh->prepare("SELECT *
					                                FROM genres");

				          $stmt2->execute();
				          $count = $stmt2->rowCount();
				          $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);


							?>
						<label>Artist</label>
						<br/>
					    <select name="update_artist_id">
						    <?php
						       foreach($result as $update) {
										 ?>
										 <option <?php if($row['artist']==$update['artistname']) echo "selected=\"selected\""; ?> value="<?php echo $update['artistid']; ?>">
										     <?php echo $update['artistname']; ?>
										 </option>
										<?PHP
						       }
						    ?>
							</select>

						<br/>
						<br />
						<label>Album</label>
						<br />
					    <select name="update_album_id">
						    <?php
						       foreach($result as $update) {
										 ?>
										 <option <?php if($row['album']==$update['albumtitle']) echo "selected=\"selected\""; ?> value="<?php echo $update['albumid']; ?>">
										     <?php echo $update['albumtitle']; ?>
										 </option>
										<?PHP
						       }
						    ?>
							</select>

						<br />
						<br />

						<label>Genre</label>
						<br />
							<select name="update_genre_id">
						    <?php
						       foreach($result2 as $update2) {
										 ?>
										 <option <?php if($row['genre']==$update2['name']) echo "selected=\"selected\""; ?>  value="<?php echo $update2['genre_id']; ?>">
										     <?php echo $update2['name']; ?>
										 </option>
										<?PHP
						       }
						    ?>
							</select>


						<input type="hidden" name="old_artist_id" value="<?= !empty($old_artist_id)?htmlspecialchars($old_artist_id):''; ?>">
						<input type="hidden" name="old_album_id" value="<?= !empty($old_album_id)?htmlspecialchars($old_album_id):''; ?>">
						<input type="hidden" name="old_genre_id" value="<?= !empty($old_genre_id)?htmlspecialchars($old_genre_id):''; ?>">

					<br />
					<br />
					<div class="form-group <?= !empty($titleerror)?'has-error':'';?>">
						<label for="title">Title</label>
				 		<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= !empty($title)?htmlspecialchars($title):''; ?>">
				 		<?php if (!empty($titleerror)): ?>
					 		<span class="help-block"><?= $titleerror;?></span>
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
					<input type="submit" class="btn btn-default" value="Edit Song">
					<a class="btn btn-default" href="tunebase.php">Back</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
