<!DOCTYPE html>
<?php

error_reporting(-1);
ini_set("display_errors", 1);

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "HTTP/1.0 405 Method Not Allowed <br />";
    echo "Allow: POST";
    die();
    }
    
    require_once ('../config.inc.php');


    if ( !empty($_POST)) {
        $user_nameerror = null;
        $user_emailerror = null;

        $user_name = $_POST['user_name'];
        $user_id = $_POST['user_id'];
        $user_email = $_POST['user_email'];

        $valid = true;

      		if (empty($user_name)) {
      			$user_nameerror = "Please enter a username";
      			$valid = false;
      		} elseif (strlen($user_name) > 20) {
      			$user_nameerror = "Your username is too long";
      			$valid = false;
      		}

  		    if (empty($user_email)) {
                  $user_emailerror = "Please enter an email address.";
                  $valid = false;

          } elseif (strlen($user_email) > 45) {
                  $user_emailerror = "Email has exceeded character limit.";
                  $valid = false;
          }

          if ($valid) {

  				$stmtx = $dbh->prepare("SELECT *
                                  FROM users
                                  WHERE user_name = :username");
  				$stmtx->bindParam(':username', $user_name);
  				$stmtx->execute();
  				$result = $stmtx->fetch();

      				if ($result) {
      					$user_nameerror = "Username already exists";
      				}

              else {
                  $stmt = $dbh->prepare(" UPDATE users
                                          SET user_name = :username, user_email = :useremail
                                          WHERE user_id = :userid");
                  $stmt->bindParam(':username', $user_name);
              		$stmt->bindParam(':useremail', $user_email);
              		$stmt->bindParam(':userid', $user_id);
              		$stmt->execute();
                  header("Location: ../music/index.php?user_id=$user_id");
              }

          }

          require_once ('./update_users_form.php');
      }
      
?>
