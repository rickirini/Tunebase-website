<?php
error_reporting(E_ALL);

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed <br />";
		echo "Allow: POST";
		die();
	}

	require_once("../config.inc.php");

	if (!empty($_POST)) {
		$artisterror = null;
		$titleerror = null;
		$yearerror = null;
		$albumerror = null;
		$genreerror = null;


    	$song_id = "";
		$genre_id = "";
		$user_id = $_POST['user_id'];
		$artist = trim($_POST["artist"]);
		$title = trim($_POST["title"]);
		$year = trim($_POST["year"]);
		$genre = trim($_POST["genre"]);
		$album = trim($_POST["album"]);
		$valid = true;

		if (empty($artist)) {
			$artisterror = "Please enter an artist.";
			$valid = false;
		} elseif (strlen($artist) > 75) {
			$artisterror = "Artist has exceeded character limit.";
			$valid = false;
		}

		if (empty($title)) {
			$titleerror = "Please enter a title.";
			$valid = false;
		} elseif (strlen($title) > 50) {
			$titleerror = "Title has exceeded character limit.";
			$valid = false;
		}

		if (empty($album)) {
			$albumerror = "Please enter an album.";
			$valid = false;
		} elseif (strlen($album) > 75) {
			$albumerror = "Album has exceeded character limit.";
			$valid = false;
		}

		if (empty($genre)) {
			$genreerror = "Please enter a genre.";
			$valid = false;
		} elseif (strlen($genre) > 45) {
			$genreerror = "Genre has exceeded character limit.";
			$valid = false;
		}

		if (empty($year)) {
			$yearerror = "Please enter a year.";
			$valid = false;
		} elseif (!is_numeric($year)) {
			$yearerror = "Year should be a number.";
			$valid = false;
		} elseif (strlen($year) > 4) {
			$yearerror = "Oops that's a little too far into the future, don't you think?";
			$valid = false;
		}

		if ($valid) {

			$query = "SELECT songs.title, songs.year, albums.title, artists.name, genres.name FROM songs INNER JOIN album_song ON songs.song_id = album_song.song_id INNER JOIN albums ON album_song.album_id = albums.album_id INNER JOIN artist_album ON albums.album_id = artist_album.album_id INNER JOIN artists ON artist_album.artist_id = artists.artist_id INNER JOIN artist_song ON artists.artist_id = artist_song.artist_id INNER JOIN song_genre ON songs.song_id = song_genre.song_id INNER JOIN genres ON song_genre.genre_id = genres.genre_id WHERE songs.title = :stitle AND songs.year = :year AND albums.title = :atitle AND artists.name = :artist AND genres.name = :genre";
			$qh = $dbh->prepare($query);
			$qh->execute(array(':stitle' => $title, ':year' => $year, ':atitle' => $album, ':artist' => $artist, ':genre' => $genre));
			$result = $qh->fetch();
			if ($result) {
				$yearerror = "Oops, song already exists";
				$genreerror = " ";
				$albumerror = " ";
				$titleerror = " ";
				$artisterror = " ";
			}
			else {

			$qh = $dbh->prepare("INSERT INTO songs (user_id, title, year) VALUES (:user_id, :title, :year)");
			$qh->bindParam(":user_id", $user_id);
			$qh->bindParam(":title", $title);
			$qh->bindParam(":year", $year);
			$qh->execute();
			$song_id = $dbh->lastInsertId();

			$qh = $dbh->prepare("INSERT INTO albums (title, year) VALUES (:title, :year)");
			$qh->bindParam(":title", $album);
			$qh->bindParam(":year", $year);
			$qh->execute();
			$album_id = $dbh->lastInsertId();

			$query = "SELECT * FROM artists WHERE name = :name";
			$qh = $dbh->prepare($query);
			$qh->execute(array(':name' => $artist));
			$result = $qh->fetch();
			if ($result) {
				foreach($result as $results) {
					$artist_id = $result['artist_id'];
				}
			}
			else {
				$qh = $dbh->prepare("INSERT INTO artists (name) VALUES (:name)");
				$qh->bindParam(":name", $artist);
				$qh->execute();
				$artist_id = $dbh->lastInsertId();
			}

			$query = "SELECT * FROM genres WHERE name = :name";
			$qh = $dbh->prepare($query);
			$qh->execute(array(':name' => $genre));
			$result = $qh->fetch();
			if ($result) {
				foreach($result as $results) {
					$genre_id = $result['genre_id'];
				}
			}
			else {
				$qh = $dbh->prepare("INSERT INTO genres (name) VALUES (:name)");
				$qh->bindParam(":name", $genre);
				$qh->execute();
				$genre_id = $dbh->lastInsertId();
			}

			

			print($song_id);
			print($genre_id);
			print($artist_id);
			print($album_id);

			$qh = $dbh->prepare("INSERT INTO song_genre (song_id, genre_id) VALUES (:song_id, :genre_id)");
			$qh->bindParam(":song_id", $song_id, PDO::PARAM_INT);
			$qh->bindParam(":genre_id", $genre_id, PDO::PARAM_INT);
			$qh->execute();


			$qh = $dbh->prepare("INSERT INTO album_song (album_id, song_id) VALUES (:album_id, :song_id)");
			$qh->bindParam(":album_id", $album_id, PDO::PARAM_INT);
			$qh->bindParam(":song_id", $song_id, PDO::PARAM_INT);
			$qh->execute();


			$qh = $dbh->prepare("INSERT INTO artist_album (artist_id, album_id) VALUES (:artist_id, :album_id)");
			$qh->bindParam(":artist_id", $artist_id, PDO::PARAM_INT);
			$qh->bindParam(":album_id", $album_id, PDO::PARAM_INT);
			$qh->execute();

			$qh = $dbh->prepare("INSERT INTO artist_song (artist_id, song_id) VALUES (:artist_id, :song_id)");
			$qh->bindParam(":artist_id", $artist_id, PDO::PARAM_INT);
			$qh->bindParam(":song_id", $song_id, PDO::PARAM_INT);
			$qh->execute();



			header("Location: tunebase.php");
		}}
	}

	require_once("create_song_form.php");
?>
