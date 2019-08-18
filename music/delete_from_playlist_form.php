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
	
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Delete Song from Playlist</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="../css/index.css">

<style>

	body {
		background: #fff url("../users/tunebasement.jpg") no-repeat fixed center;
		background-size: cover;
	}

	h1, p {
		text-align: center;
	}

	p {
		font-size: 1.2em;
	}

	.table-bordered td, .table-bordered th {
		border: 1px solid rgba(153, 122, 108, 0.6) !important;
	}

	form, a {
		display: inline;
	}

</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">

				<?php
					$song_id = $_GET['song_id'];
					$qh = $dbh->prepare("SELECT distinct artists.name as artist, songs.title as title, songs.year as year, albums.title as album, genres.name as genre, songs.song_id as song_id FROM songs INNER JOIN album_song ON songs.song_id = album_song.song_id INNER JOIN albums ON album_song.album_id = albums.album_id INNER JOIN artist_album ON albums.album_id = artist_album.album_id INNER JOIN artists ON artist_album.artist_id = artists.artist_id INNER JOIN artist_song ON artists.artist_id = artist_song.artist_id INNER JOIN song_genre ON songs.song_id = song_genre.song_id INNER JOIN genres ON song_genre.genre_id = genres.genre_id WHERE songs.song_id = :song_id");

						$qh->execute(array(':song_id' => $song_id));
						
						?>
				<h1>Delete Song</h1>
				<p>Are you sure you want to delete this song from your playlist?</p>
				<br />
				<table class="table table-striped">
						<thead>

							<tr>
								<th>Artist</th>
								<th>Song</th>
								<th>Album</th>
								<th>Year</th>
								<th>Genre</th>
							</tr>
						</thead>
						<tbody>
							<?php
								
									foreach($qh as $row) {
										$artist = $row['artist'];
										$title = $row['title'];
										$year = $row['year'];
										$album = $row['album'];
										$genre = $row['genre'];
										$song_id = $row['song_id'];
								?>
										<tr>
										<td><?= htmlspecialchars($artist) ?></td>
										<td><?= htmlspecialchars($title) ?></td>
										<td><?= htmlspecialchars($year) ?></td>
										<td><?= htmlspecialchars($album) ?></td>
										<td><?= htmlspecialchars($genre) ?></td>	
										<tr>
								<?php
									}
								?>
						
						</tbody>
					</table>
				
					<div class="form-group formdiv">
						<form action="delete_from_playlist.php" method="post">
							<input type="hidden" name="song_id" value="<?= $song_id; ?>">
							<input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
							<input type="submit" class="btn btn-default" value="Yes, delete">
						</form> 
						<a class="btn btn-default" href="index.php?user_id=<?= $_SESSION['user_id'] ?>&playlistId=<?= $_SESSION['playlist_id']?>">Back</a>
					</div>
				</div>
			
		</div>
	</div>
</body>
</html>