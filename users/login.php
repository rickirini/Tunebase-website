<!DOCTYPE html>
<?php
	require_once("../config.inc.php");
	require_once("password.inc.php");
	
	if (!empty($_POST)) {

		$nameerror = null;
		$passworderror = null;
		$name = $_POST['name'];
		$password = $_POST['password'];
		$valid = true;

		if (empty($name)) {
			$nameerror = 'Please enter a name';
			$valid = false;
		}

		if (empty($password)) {
			$passworderror = 'Please enter a password';
			$valid = false;
		}

		if ($valid) {

			try {
				$qh = $dbh->prepare("SELECT user_id, password_hash, user_name FROM users WHERE user_name = ?");
				$qh->execute(array($_POST['name']));

				$count = $qh->rowCount();

				if ($count > 0) {
					foreach ($qh as $row) {
						if (password_verify($_POST['password'], $row['password_hash'])) {
							session_start();
							$_SESSION['user_id'] = $row['user_id'];
							$_SESSION['user_name'] = $name;
							$user_id = $row['user_id'];
							header("Location: ../music/index.php?user_id=$user_id");
							die();
						} else {
							$passworderror = 'Wrong password';
							
						}

					} 
				} else {
					$passworderror = 'Username/password combination doesn\'t exist';
					$nameerror = ' ';
				}
				

				} catch (PDOException $e) {
					die('ERROR: ' . htmlspecialchars($e->getMessage()));
					
				}
		}
	
	}
	require_once('login_form.php');
?>