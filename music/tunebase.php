<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$user_id = $_SESSION['user_id'];

	if ($_SESSION['user_id'] == "") {
		header("Location: ../users/index.php");
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tunebase</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
<script src="https://use.fontawesome.com/1eff727cd1.js"></script>
<link rel="stylesheet" type="text/css" href="../css/index.css">
<style>

	h1 {
		text-align: center;
		font-size: 3em;
		padding-top: 30px;
		padding-bottom: 20px;
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
					<li><a href="index.php?user_id=<?= $_SESSION['user_id'] ?>">Home</a></li>
					<li><a href="../users/update_users_form.php?user_id=<?= $_SESSION['user_id'] ?>">User settings</a></li>
						<?php

							if (!empty($_SESSION['user_id'])) {
						?>
								<li><a href="../users/logout.php">
								
								Logout
								</a></li>

						<?php } ?>

				</ul>
				<form class="navbar-form pull-left" method="get" action=<?php print(htmlspecialchars("")) ?>>
		          <?php
		          $defaultsearchvalue = "Author, title, year etc.";
		          ?>
	          		<div class="input-group" align="left">
			          <input type="text" class="searchbar form-control" value="<?php print($defaultsearchvalue) ?>" onfocus="if (this.value=='<?php print($defaultsearchvalue) ?>') this.value='';" name="search_keyword" size="35"/>
			          <div class="input-group btn">
			          <button type="submit" class="btn btn-default searchbutton"> <span class="glyphicon glyphicon-search"></span> Search </button>
			          </div>
	          		</div>
	     		 </form>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-sm-2 col-md-2 sidebar">
				<ul class="nav nav-sidebar">

					<li class="active"><a href="#">All songs<span class="sr-only">current</span></a></li>

					<li><a href="create_song_form.php">Add new song</a></li>

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
							} ?> href="index.php?user_id=<?= $_SESSION['user_id']?>&playlistId=<?= $playlist_id ?>"><?= htmlspecialchars($name); ?>
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


			<div class="col-sm-12 col-sm-offset-1 col-md-12 col-md-offset-1 main">
				<h1>The Tunebase</h1>
				<form method="post" action="add_to_playlist.php">
					<div class="form-group`table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Artist<a href="?orderBy=artists.name&orderType=ASC">
									<span class="glyphicon glyphicon-arrow-up"></span></a><a href="?orderBy=artists.name&orderType=DESC"><span class="glyphicon glyphicon-arrow-down"></span></a></th>
									<th>Title <a href="?orderBy=songs.title&orderType=ASC">
									<span class="glyphicon glyphicon-arrow-up"></span></a><a href="?orderBy=songs.title&orderType=DESC"><span class="glyphicon glyphicon-arrow-down"></span></a></th>
									<th>Year<a href="?orderBy=songs.year&orderType=ASC">
									<span class="glyphicon glyphicon-arrow-up"></span></a><a href="?orderBy=songs.year&orderType=DESC"><span class="glyphicon glyphicon-arrow-down"></span></a></th>
									<th>Album<a href="?orderBy=albums.name&orderType=ASC">
									<span class="glyphicon glyphicon-arrow-up"></span></a><a href="?orderBy=albums.name&orderType=DESC"><span class="glyphicon glyphicon-arrow-down"></span></a></th>
									<th>Genre<a href="?orderBy=genres.name&orderType=ASC">
									<span class="glyphicon glyphicon-arrow-up"></span></a><a href="?orderBy=genres.name&orderType=DESC"><span class="glyphicon glyphicon-arrow-down"></span></a></th>
									<th>Checkbox</th>
									<th>Rating</th>
									<th>Update/Delete</th>
							</thead>
							<tbody>
								<?php
									require_once("../config.inc.php");
									$orderBy = array("artists.name", "songs.title", "songs.year", "albums.title", "genres.name");
									$orderType = array("DESC", "ASC");
									$orderByAttribute = "artists.name";
									$type = "ASC";

									if (isset($_GET["orderBy"]) && in_array($_GET["orderBy"], $orderBy)) {
										$orderByAttribute = $_GET["orderBy"];
									}

									if (isset($_GET["orderType"]) && in_array($_GET["orderType"], $orderType)) {
										$type = $_GET["orderType"];
									}


									if(isset($_GET['search_keyword']) and ($_GET['search_keyword'] != "Author, title, year etc.")) {
											$keyword = htmlspecialchars($_GET['search_keyword']);

											//search
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
																						WHERE (songs.title LIKE :keyword OR albums.title LIKE :keyword OR songs.year LIKE :keyword OR artists.name LIKE :keyword OR genres.name LIKE :keyword)
																						ORDER BY ".$orderByAttribute." ".$type."
																						");


						          $qh->bindValue(':keyword', '%'. $keyword .'%', PDO::PARAM_STR);
											$qh->execute();

											$count = $qh->rowCount();

										}

									else {

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
																						ORDER BY ".$orderByAttribute." ".$type."
																						");
											$qh->execute(array());

											$count = $qh->rowCount();

									}

									if ($count > 0) {
										foreach($qh as $row) {
											$artist = $row['artist'];
											$title = $row['title'];
											$year = $row['year'];
											$album = $row['album'];
											$genre = $row['genre'];
											$song_id = $row['song_id'];
											$user_id = $row['user_id'];

										?>
											<tr>
												<td><?= htmlspecialchars($artist) ?></td>
												<td><?= htmlspecialchars($title) ?></td>
												<td><?= htmlspecialchars($year) ?></td>
												<td><?= htmlspecialchars($album) ?></td>
												<td><?= htmlspecialchars($genre) ?></td>
												<td><input type="checkbox" name="song_id[]" value="<?= htmlspecialchars($song_id)?>"></td>


													</form>
														<?php
																if (isset($_POST['like'])) {

																	# get user's song likes
																	$stmt_getlikes = $dbh->prepare("SELECT *
																																	FROM like_song
																																	WHERE song_id = :songid
																																	AND user_id = :userid");
																	$stmt_getlikes->bindValue(':songid', $_POST['hiddensongid'], PDO::PARAM_INT);
																	$stmt_getlikes->bindValue(':userid', test_input($_SESSION['user_id']), PDO::PARAM_INT);
																	$stmt_getlikes->execute();
																	$count_getlikes = $stmt_getlikes->rowCount();
																	$stmt_getlikes->fetchAll(PDO::FETCH_ASSOC);

																		if ($count_getlikes > 0) {
																			$stmt = $dbh->prepare("UPDATE like_song
																														 SET like_status = :likestatus
																														 WHERE like_song.song_id = :songid
																														 AND like_song.user_id = :userid");
																			$stmt->bindValue(':songid', test_input($_POST['hiddensongid']), PDO::PARAM_INT);
																			$stmt->bindValue(':userid', test_input($_SESSION['user_id']), PDO::PARAM_INT);
																			$stmt->bindValue(':likestatus', test_input($_POST['like']), PDO::PARAM_INT);
																			$stmt->execute();
																		}
																		else {
																			$stmt = $dbh->prepare ("INSERT into like_song
																															(song_id, user_id, like_status)
																															VALUES (:songid, :userid, :likestatus)");

																			$stmt->bindValue(':userid', test_input($_SESSION['user_id']), PDO::PARAM_INT);
																			$stmt->bindValue(':songid', test_input($_POST['hiddensongid']), PDO::PARAM_INT);
																			$stmt->bindValue(':likestatus', test_input($_POST['like']), PDO::PARAM_INT);
																			$stmt->execute();
																		}
																}
														?>

													<form method="post" action="">
														<td>
															<?php
																	# select likes
																	$stmt_likes = $dbh->prepare("SELECT *
																															 FROM like_song
																															 WHERE song_id = :id
																															 AND like_status = 1");
											            $stmt_likes->bindValue(':id', $song_id, PDO::PARAM_INT);

												          $stmt_likes->execute();
												          $count_likes = $stmt_likes->rowCount();
												          $result_likes = $stmt_likes->fetchAll(PDO::FETCH_ASSOC);

																	# select dislikes
																	$stmt_dislikes = $dbh->prepare("SELECT *
																                                  FROM like_song
																																  WHERE song_id = :id
																																  AND like_status = 2");
											            $stmt_dislikes->bindValue(':id', $song_id, PDO::PARAM_INT);

												          $stmt_dislikes->execute();
												          $count_dislikes = $stmt_dislikes->rowCount();
												          $result_dislikes = $stmt_dislikes->fetchAll(PDO::FETCH_ASSOC);

															?>

														<table class="table table-unbordered table-curved">
															<td>
																	<center><button type="submit" name="like" value="1" class="like"></button></center>
																<center><?php	if ($count_likes > 0) {print ($count_likes);} else { print("0");}?></center>
															</td>
															<td>
																<center><button type="submit"  class="dislike" name="like" value="2" ></button></center>
																<center><?php	if ($count_dislikes > 0) {print ($count_dislikes);} else { print("0");}?></center>
															</td>
													</table>
															<input type="hidden" name="hiddensongid" value="<?= $song_id ?>">
													</form>

														</td>
														<td>
													<?php
													if($_SESSION['user_id'] == $row['user_id']) {
														?>

															<a href="delete_song_form.php?song_id=<?= $song_id ?>" class="btn btn-default delete" type="submit" >Delete</a>
															<a href="update_song_form.php?song_id=<?= $song_id ?>" class="btn btn-default update" type="submit">Update</a>

													<?php
													}
												?>
												</td>
											</tr>
								<?php
										}
								}
								else {
									echo "<tr><td colspan='8'>No songs to display</td></tr>";
								}

								?>
							</tbody>
						</table>
						<select class="form-control" name="playlist_id">
							<?php
								$qh = $dbh->prepare("SELECT distinct name, playlist_id FROM playlists WHERE user_id = ?");
											$qh->execute(array($_SESSION['user_id']));
											$count = $qh->rowCount();

											if ($count > 0) {
												foreach($qh as $row) {
													$name = $row['name'];
													$playlist_id = $row['playlist_id'];
													?>
													<option name="playlist_id" value="<?= $playlist_id ?>"><?= htmlspecialchars($name); ?></option>
													<?php
												}
											}
											else {
												?> <option>No playlists to display</option><?php
											}
							?>
						</select>
					</div>

					<?php
							if (isset($keyword)) {	?>
								<div class="backbutton">
									<a href="../music/tunebase.php" class="btn btn-default delete" type="submit" >Back to all songs</a>
								</div>
					<?PHP
							}
					?>

					<div class="form-group">
						<button type="submit" name="playlist" class="btn btn-default playlist space bottompad" style="float:right">Add to Playlist</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</body>
</html>
