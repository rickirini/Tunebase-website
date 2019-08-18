<?php
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed";
		echo "<br />";
		echo "Allow: POST";
		die();
	}
	require_once("../config.inc.php");
	session_start();
	$playlist_id = $_SESSION['playlist_id'];
	$user_id = $_SESSION['user_id'];


	$qh = $dbh->prepare("DELETE FROM playlist_song WHERE playlist_id = ?");
	$qh->execute(array($playlist_id));
	$qh = $dbh->prepare("DELETE FROM playlists WHERE playlist_id = ? AND user_id = ?");
	$qh->execute(array($playlist_id, $user_id));
	
	header("Location: index.php?user_id=$user_id");
?>
