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
	$playlist_id = $_SESSION['playlist_id'];
	$user_id = $_SESSION['user_id'];


	$qh = $dbh->prepare("DELETE FROM playlist_song WHERE song_id = ? AND playlist_id = ?");
	$qh->execute(array($song_id, $playlist_id));

	
	header("Location: index.php?user_id=$user_id&playlistId=$playlist_id");
?>
