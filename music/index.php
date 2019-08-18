<?php
session_start();
require_once("../config.inc.php");
?>

<?php
	if ($_SESSION['user_id'] == "" or $_SESSION['user_id'] != $_GET['user_id']) {
		header("Location: ../users/index.php");
	}

	if (empty($_GET['playlistId'])) {
		$title = 'No playlist was selected';
	}

	$biggesthot = "";
	$biggesthotartist = "";
	$biggesthottitle = "";


	if(isset($_POST['submit'])) {
		move_uploaded_file($_FILES['file']['tmp_name'], "pictures/".$_FILES['file']['name']);
		chmod("pictures/".$_FILES['file']['name'], 0777);
		$qh = $dbh->prepare("UPDATE users SET image = :file WHERE user_id = :user_id");
		$qh->execute(array(':file' => $_FILES['file']['name'], ':user_id' => $_SESSION['user_id']));

	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>TuneBasement</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
<script src="https://use.fontawesome.com/1eff727cd1.js"></script>
<link rel="stylesheet" type="text/css" href="../css/index.css">

<style>

	.btn {
		color: #3E4551 !important;
	}

</style>


</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<img src="Tunebasement.jpg" width="50px" class="image">
				<a class="navbar-brand" href="#">TuneBasement</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">

						<?php

							if (!empty($_SESSION['user_id'])) {
						?>
								<li><a href="../users/update_users_form.php?user_id=<?= $_SESSION['user_id'] ?>">User settings</a></li>
								<li><a href="../users/logout.php">

								Logout
								</a></li>
						<?php } ?>

				</ul>

			</div>
		</div>
	</nav>


	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">

					<li class="active"><a href="#">Overview<span class="sr-only">current</span></a></li>

					<li><a href="tunebase.php">All songs</a></li>
					<li><a href="create_song_form.php">Add new song</a></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li><a href="create_playlist_form.php">Create playlist</a></li>
					<?php
						require_once("../config.inc.php");
						$query = "SELECT distinct * FROM playlists WHERE user_id = ?";
						$qh = $dbh->prepare($query);
						$result = $qh->execute(array($_SESSION['user_id']));

						foreach ($qh as $row) {
							$playlist_id = $row['playlist_id'];
							$name = $row['name'];
							?><li><a <?php if (isset($_GET['playlistId']) && $_GET['playlistId'] == $row['playlist_id']) {
								echo 'style="background-color:#F02E2E;color:#fff;"';
							} ?> href="?user_id=<?= $_SESSION['user_id']?>&playlistId=<?= $playlist_id ?>"><?= htmlspecialchars($name); ?>
								</a></li>
						<?php
						}
					?>

				</ul>
				<?PHP
				//most popular (hot) song
				$stmt_hotlikes = $dbh->prepare("  SELECT DISTINCT a.song_id, songs.title as title, artists.name as artist,
																					(SELECT COUNT(*) FROM like_song WHERE like_status = 1 and song_id = a.song_id) as likes,
																					(SELECT COUNT(*) FROM like_song WHERE like_status = 2 and song_id = a.song_id) as dislikes,
																					(SELECT COUNT(*) FROM like_song WHERE song_id = a.song_id) as TotalCount
																					FROM like_song a
																					INNER JOIN songs
																					ON a.song_id = songs.song_id
																					INNER JOIN artist_song
																					ON a.song_id = artist_song.song_id
																					INNER JOIN artists
																					ON artist_song.artist_id = artists.artist_id
																				 ");

				$stmt_hotlikes->execute();
				$counterhot = $stmt_hotlikes->rowCount();

				$biggestsaldo = 0;
				$lowestsaldo = 0;
				$biggesthottitle = "";
				$biggesthotartist = "";
				$biggestcoldtitle = "";
				$biggestcoldartist = "";


				foreach ($stmt_hotlikes as $row) {
					$hotsaldo = $row['likes'];
					$coldsaldo = $row['dislikes'];
					$balance = $hotsaldo - $coldsaldo;

					//highest balance of likes
					if ($balance >= $biggestsaldo) {
							$biggestsaldo = $balance;
							$biggesthottitle = $row['title'];
							$biggesthotartist = $row['artist'];
					}

					//lowest balance of likes
					if ($balance <= $lowestsaldo) {
							$lowestsaldo = $balance;
							$biggestcoldtitle = $row['title'];
							$biggestcoldartist = $row['artist'];
					}

				}

				//printing output
				print("</br>");

				print("<b>Hottest song of the moment:</b></br>".$biggesthottitle." by ".$biggesthotartist."! </br></br>");
				print("With a score of ".$biggestsaldo." like(s)!</br>");

				print("</br></br>");

				print("<b>Coldest song of the moment:</b></br>".$biggestcoldtitle." by ".$biggestcoldartist."! </br></br>");
				print("With a score of ".abs($lowestsaldo)." dislike(s)!</br>");
				?>

			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-8 col-md-offset-2 main">
				<h1 class="page-header">My Profile</h1>
				<div class="row welcome">
					<div class="col-sm-2 col-md-1 col-xl-1">
					<?php
						$qh = $dbh->prepare("SELECT distinct user_name, user_email, image FROM users WHERE user_id = :user_id");

						$qh->execute(array(':user_id' => $_SESSION['user_id']));

					foreach($qh as $row) {
						if($row['image'] == "") {
							?>
							<img class="imageupload" width='150' height='150' src='pictures/default1.jpg'>
							<?php
						} else {
							?>
							<img class="imageupload" width='150' height='150' src="pictures/<?= $row['image']?>">
							<?php
						}
					}
					?>
					<form action="" method="post" class="form-group upload" enctype="multipart/form-data">
						<input id="upload-photo" type="file" name="file">
						<label id="upload-photo-label" for="upload-photo">Browse</label>
						<input type="submit" class="btn btn-default submitbutton" name="submit" value="Submit">
					</form>
					</div>
					<?php
					$user_name = $row['user_name'];
					$email = $row['user_email'];
					$message = 'Good to see you';


					?>

					<div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 details">
					<p id="welcome"><?php echo $message . ", " . $user_name . "!" ?></p>

					<h3 class="page-header"> Your details</h3>
					<p class="username"><span class="bold"> Username: </span><?= htmlspecialchars($user_name); ?></p>
					<p class="email"><span class="bold"> Email: </span><?= htmlspecialchars($email); ?></p>
					</div>

				</div>



				<?php

					if (isset($_GET["playlistId"]))  /* && in_array($_GET["playlistId"], $playlists)) */ {
						$qh = $dbh->prepare(" SELECT  artists.name as artist, songs.title as title, songs.year as year, albums.title as album, genres.name as genre, songs.song_id as song_id, playlists.name as p_name
																	from playlist_song
																	inner join playlists
																	on playlist_song.playlist_id = playlists.playlist_id
																	inner join songs
																	on songs.song_id = playlist_song.song_id
																	inner join artist_song
																	on songs.song_id = artist_song.song_id
																	inner join artists
																	on artist_song.artist_id = artists.artist_id
																	inner join song_genre
																	on songs.song_id = song_genre.song_id
																	inner join genres
																	on song_genre.genre_id = genres.genre_id
																	inner join album_song
																	on songs.song_id = album_song.song_id
																	inner join albums
																	on album_song.album_id = albums.album_id
																	WHERE playlists.playlist_id = :playlist_id");

						$qh->execute(array(':playlist_id' => $_GET['playlistId']));
						$count = $qh->rowCount();

						$qh1 = $dbh->prepare("SELECT distinct name FROM playlists WHERE playlist_id = ? AND user_id = ?");
						$qh1->execute(array($_GET['playlistId'], $_GET['user_id']));
						$count1 = $qh1->rowCount();
						if ($count1 > 0) {
							foreach($qh1 as $row) {
								$playlistName = $row['name'];
								$_SESSION['playlist_id'] = $_GET['playlistId'];
								$_SESSION['playlist_name'] = $row['name'];
							}
						}
						?>
				<h2 class="sub-header"><?php if ($_GET['playlistId'] != "" and $count1 > 0) {
									echo $playlistName;
								} else {
									echo 'No playlist selected';
								}

									?>
									<a href="edit_playlist_form.php"><span class="glyphicon glyphicon-pencil"></span></a>
									<a href="delete_playlist_form.php"><span class="glyphicon glyphicon-trash"></span></a>
									</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>

							<tr>
								<th>Artist</th>
								<th>Song</th>
								<th>Album</th>
								<th>Year</th>
								<th>Genre</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if ($count > 0) {
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
										<td><a class="btn btn-default" href="delete_from_playlist_form.php?song_id=<?= $song_id ?>">Delete</a></td>
										<tr>
								<?php
									}
								} else {
									echo "<tr><td colspan='4'>No songs to display</td></tr>";
								}
							}
								?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



</body>
</html>
