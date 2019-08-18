<?php
error_reporting(E_ALL);
session_start();

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed <br />";
		echo "Allow: POST";
		die();
	}

	require_once("../config.inc.php");

		if(!empty($_POST['playlist_id'])) {
			$playlist_id = $_POST['playlist_id'];
		}

		if(!empty($_POST['song_id'])) {
			foreach($_POST['song_id'] as $selected) {
				$query = "SELECT playlist_id, song_id FROM playlist_song WHERE playlist_id = :playlist_id AND song_id = :song_id";
				$qh = $dbh->prepare($query);
				$qh->execute(array(':playlist_id' => $playlist_id, ':song_id' => $selected));
				$result = $qh->fetch();
				if (empty($result)) {
					$qh = $dbh->prepare("INSERT INTO playlist_song (playlist_id, song_id) VALUES (:playlist_id, :song_id)");
					$qh->bindParam(":playlist_id", $playlist_id);
					$qh->bindParam(":song_id", $selected);
					$qh->execute();

				}
			}
		}

			header("Location: index.php?user_id=".$_SESSION['user_id']."&playlistId=". $playlist_id);

?>
