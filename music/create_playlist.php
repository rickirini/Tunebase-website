<?php
error_reporting(E_ALL);

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed <br />";
		echo "Allow: POST";
		die();
	}

	require_once("../config.inc.php");

	if (!empty($_POST)) {
		$nameerror = null;
		$playlist_id = $_POST['playlist_id'];
		$user_id = $_POST['user_id'];
		$name = trim($_POST["name"]);

		$valid = true;

		if (empty($name)) {
			$nameerror = "Please enter a name.";
			$valid = false;
		} elseif (strlen($name) > 75) {
			$nameerror = "Name has exceeded character limit.";
			$valid = false;
		}

		if ($valid) {

			$qh = $dbh->prepare("INSERT INTO playlists (user_id, name) VALUES (?, ?)");


			$qh->execute(array($user_id, $name));
			$playlist_id = $dbh->lastInsertId();



			header("Location: tunebase.php");
		}

	}

	require_once("create_playlist_form.php");
?>
