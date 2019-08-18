<?php
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		echo 'HTTP/1.0 405 Method Not Allowed';
		echo '<br />';
		echo 'Allow: POST';
		die();
	}
	require_once("../config.inc.php");

	if (!empty($_POST)) {
		$artisterror = null;
		$titleerror = null;
		$yearerror = null;
		$albumerror = null;
		$genreerror = null;
		$artist = trim($_POST['artist']);
		$title = trim($_POST['title']);
		$year = trim($_POST['year']);
		$album = trim($_POST['album']);
		$genre = trim($_POST['genre']);
		$song_id = $_POST['song_id'];
		$user_id = $_POST['user_id'];


		$old_artist_id = $_POST['old_artist_id'];
		$update_artist_id = $_POST['update_artist_id'];

		$old_album_id = $_POST['old_album_id'];
		$update_album_id = $_POST['update_album_id'];

		$old_genre_id = $_POST['old_genre_id'];
		$update_genre_id = $_POST['update_genre_id'];

/* debug
print($old_album_id);
print($update_album_id);
print($old_artist_id);
print($update_artist_id);
print($old_genre_id);
print($update_genre_id);
print($user_id);
*/

		$valid = true;
		$notreplace = false;


		if (empty($title)) {
			$titleerror = "Please enter a title";
			$valid = false;
		} elseif (strlen($title) > 50) {
			$titleerror = "Title has exceeded character limit";
			$valid = false;
		}

		if (empty($year)) {
			$yearerror = "Please enter a year";
			$valid = false;
		} elseif (!is_numeric($year)) {
			$yearerror = "Year should be a number";
			$valid = false;
		} elseif (strlen($year) > 4) {
			$yearerror = "Oops that's a little too far into the future, don't you think?";
			$valid = false;
		}



		if ($valid) {

			$query = "SELECT songs.title, songs.year, albums.title, artists.name, genres.name FROM songs INNER JOIN album_song ON songs.song_id = album_song.song_id INNER JOIN albums ON album_song.album_id = albums.album_id INNER JOIN artist_album ON albums.album_id = artist_album.album_id INNER JOIN artists ON artist_album.artist_id = artists.artist_id INNER JOIN artist_song ON artists.artist_id = artist_song.artist_id INNER JOIN song_genre ON songs.song_id = song_genre.song_id INNER JOIN genres ON song_genre.genre_id = genres.genre_id WHERE songs.title = :stitle AND songs.year = :year AND albums.title = :atitle AND artists.name = :artist AND genres.name = :genre AND songs.song_id != :song_id";
			$qh = $dbh->prepare($query);
			$qh->execute(array(':stitle' => $title, ':year' => $year, ':atitle' => $album, ':artist' => $artist, ':genre' => $genre, ':song_id' => $song_id));
			$result = $qh->fetch();


			$qh = $dbh->prepare("UPDATE songs
													 SET title = :title, year = :year
													 WHERE song_id = :song_id
													 AND user_id = :user_id");

			$qh->bindParam(":song_id", $song_id);
			$qh->bindParam(":user_id", $user_id);
			$qh->bindParam(":title", $title);
			$qh->bindParam(":year", $year);
			$qh->execute();



			$qh = $dbh->prepare("UPDATE artist_song
													 SET artist_id = :update_artist_id
													 WHERE song_id = :song_id
													 AND artist_id = :artist_id");

			$qh->bindParam(":song_id", $song_id);
			$qh->bindParam(":artist_id", $old_artist_id);
			$qh->bindParam(":update_artist_id", $update_artist_id);
			$qh->execute();



			$qh = $dbh->prepare("UPDATE album_song
													 SET album_id = :update_album_id
													 WHERE song_id = :song_id
													 AND album_id = :album_id");

			$qh->bindParam(":song_id", $song_id);
			$qh->bindParam(":album_id", $old_album_id);
			$qh->bindParam(":update_album_id", $update_album_id);
			$qh->execute();

			$qh = $dbh->prepare("UPDATE song_genre
													 SET genre_id = :update_genre_id
													 WHERE song_id = :song_id
													 AND genre_id = :genre_id");

			$qh->bindParam(":song_id", $song_id);
			$qh->bindParam(":genre_id", $old_genre_id);
			$qh->bindParam(":update_genre_id", $update_genre_id);
			$qh->execute();



			}



			header("Location: tunebase.php");
		}


	require_once("update_song_form.php");
?>
