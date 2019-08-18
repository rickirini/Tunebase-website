<?php
session_start();
require_once("../config.inc.php");
?>
<!DOCTYPE html>
<?php

	if (!isset($_SESSION['user_id'])) {
		header("Location: ../users/index.php");
	} else {
		$user_id = $_SESSION['user_id'];
	}

?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Delete Song</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/index.css">

<style>

	body {
		background: rgba(153,122,108,1) url("../users/tunebasement.jpg") no-repeat fixed center;
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

	table {
		background: rgba(100,100,100,0.05);
	}


</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-xs-6 formcontainer">

				<?php
					$song_id = $_GET['song_id'];
					$qh = $dbh->prepare(" SELECT songs.song_id as song_id, songs.title, songs.year, artists.name as artist, genres.name as genre, songs.user_id, albums.title as album
																FROM songs
																INNER JOIN artist_song
																on songs.song_id = artist_song.song_id
																INNER JOIN artists
																on artist_song.artist_id = artists.artist_id
																INNER JOIN song_genre
																on songs.song_id = song_genre.song_id
																INNER JOIN genres
																on song_genre.genre_id = genres.genre_id
																INNER JOIN album_song
																on songs.song_id = album_song.song_id
																INNER JOIN albums
																on album_song.album_id = albums.album_id
																WHERE songs.song_id = :song_id");

						$qh->execute(array(':song_id' => $song_id));

						?>
				<h1>Delete Song</h1>
				<p>Are you sure you want to delete this song?</p>
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
						<form action="delete_song.php" method="post">
							<input type="hidden" name="song_id" value="<?= $song_id; ?>">
							<input type="hidden" name="user_id" value="<?= $user_id; ?>">
							<input type="submit" class="btn btn-default" value="Yes, delete">
						</form>
						<a class="btn btn-default" href="tunebase.php">Back</a>
					</div>
				</div>
			</div>
		</div>
</body>
</html>
