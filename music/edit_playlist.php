<?php
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		echo 'HTTP/1.0 405 Method Not Allowed';
		echo '<br />';
		echo 'Allow: POST';
		die();
	}
	session_start();
	require_once("../config.inc.php");
	
	

	if (!empty($_POST)) {
		$oldname = $_POST['oldname'];
		$name = $_POST['name'];
		$user_id = $_POST['user_id'];
		$playlist_id = $_POST['playlist_id'];
		$nameerror = null;
		$valid = true;
		if (empty($name)) {
			$nameerror = "Please enter a playlist name";
			$valid = false;
		} elseif (strlen($name) > 50) {
			$nameerror = "Playlist name has exceeded character limit";
			$valid = false;
		}

		if ($valid) {

			$qh = $dbh->prepare("SELECT * FROM playlists WHERE name = ? AND user_id = ?");
			$qh->execute(array($name, $user_id));
			$count = $qh->rowCount();
			if ($count > 0 and $oldname != $name) {
				$nameerror = 'You already have a playlist with that name';
			}
			else {

				$qh = $dbh->prepare("UPDATE playlists SET name = ? WHERE playlist_id = ? AND user_id = ?");
				$qh->execute(array($name, $playlist_id, $user_id));

				header("Location: index.php?user_id=$user_id&playlistId=$playlist_id");
				die();
			}
		}
		require_once("edit_playlist_form.php");
	}
	
	?>
