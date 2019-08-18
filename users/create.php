<!DOCTYPE html>
<?php

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo "HTTP/1.0 405 Method Not Allowed <br />";
		echo "Allow: POST";
		die();
	}
	
	if (!empty($_POST)) {
		require_once("../config.inc.php");
		require_once("password.inc.php");
		$password = $_POST['password'];
		$password1 = $_POST['password1'];
		$user_email = $_POST['user_email'];
		$name = $_POST['name']; 
		$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$nameerror = null;
		$passworderror = null;
		$password1error = null;
		$emailerror = null;
		$valid = true;

		if (empty($name)) {
			$nameerror = "Please enter a username";
			$valid = false;
		} elseif (strlen($name) > 20) {
			$nameerror = "Your username is too long";
			$valid = false;
		}

		if (empty($password)) {
			$passworderror = "Please enter a password";
			$valid = false;
		} 

	 	if (empty($password1)) {
			$password1error = "Please repeat the password";
			$valid = false;
		} 

		if (!empty($password) && !empty($password1)) {
			if ($password != $password1) {
				$passworderror = ' ';
				$password1error = "Passwords don't match";
				$valid = false;
			} elseif (strlen($password) < 8) {
				$passworderror = ' ';
				$password1error = "Password should be at least 8 characters long";
				$valid = false;
			} elseif (!preg_match('/[0-9]/', $password)) {
				$passworderror = ' ';
				$password1error = "Password should contain at least one digit";
				$valid = false;
			}
		}

		if (empty($user_email)) {
                $emailerror = "Please enter an email address.";
                $valid = false;

        } elseif (strlen($user_email) > 45) {
                $emailerror = "Email has exceeded character limit.";
                $valid = false;
        }

		if ($valid) {

			$query = "SELECT * FROM users WHERE user_name = :username";
			$qh = $dbh->prepare($query);
			$qh->execute(array(':username' => $_POST['name']));
			$result = $qh->fetch();
			if ($result) {
				$nameerror = "Username already exists";
			} else {
				try {
					$qh = $dbh->prepare("INSERT INTO users (user_name, password_hash, user_email) VALUES (?, ?, ?)");
					$qh->execute(array($_POST['name'], $password_hash, $user_email));
						header("Location: login_form.php");
				} catch (PDOException $e) {
					die("ERROR: {$e->getMessage()}");
				}

			}
				
		}
	}	
	require_once("create_form.php");
?>

			
				
