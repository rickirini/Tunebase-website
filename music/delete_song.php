<?php
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed";
		echo "<br />";
		echo "Allow: POST";
		die();
	}
	require_once("../config.inc.php");
	session_start();
	$song_id = $_POST["song_id"];
	$user_id = $_SESSION["user_id"];


	$qh = $dbh->prepare("SELECT distinct artists.name as artist, artists.artist_id as artist_id, songs.title as title, songs.year as year, albums.title as album, albums.album_id as album_id, genres.name as genre, genres.genre_id as genre_id, songs.song_id as song_id FROM songs INNER JOIN album_song ON songs.song_id = album_song.song_id INNER JOIN albums ON album_song.album_id = albums.album_id INNER JOIN artist_album ON albums.album_id = artist_album.album_id INNER JOIN artists ON artist_album.artist_id = artists.artist_id INNER JOIN artist_song ON artists.artist_id = artist_song.artist_id INNER JOIN song_genre ON songs.song_id = song_genre.song_id INNER JOIN genres ON song_genre.genre_id = genres.genre_id WHERE songs.song_id = ?");
	$qh->execute(array($song_id));
	foreach($qh as $row) {
		$artist = $row['artist'];
		$artist_id = $row['artist_id'];
		$title = $row['title'];
		$year = $row['year'];
		$genre = $row['genre'];
		$genre_id = $row['genre_id'];
		$album = $row['album'];
		$album_id = $row['album_id'];
	}

	$qh = $dbh->prepare("DELETE FROM artist_song WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("DELETE FROM album_song WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("DELETE FROM song_genre WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("DELETE FROM like_song WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("DELETE FROM songs WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("DELETE FROM playlist_song WHERE song_id = ?");
	$qh->execute(array($song_id));

	$qh = $dbh->prepare("SELECT * FROM album_song WHERE album_id = ?");
	$qh->execute(array($album_id));
	$count = $qh->rowCount();
	if ($count < 1) {
			$qh = $dbh->prepare("DELETE FROM artist_album WHERE album_id = ?");
			$qh->execute(array($album_id));
			$qh = $dbh->prepare("DELETE FROM albums WHERE album_id = ?");
			$qh->execute(array($album_id));
	}

	$qh = $dbh->prepare("SELECT * FROM artist_song WHERE artist_id = ?");
	$qh->execute(array($artist_id));
	$count = $qh->rowCount();
	if ($count < 1) {
			$qh = $dbh->prepare("DELETE FROM artists WHERE artist_id = ?");
			$qh->execute(array($artist_id));
	}

	header("Location: tunebase.php");
?>
