<?php
session_start();
require_once("../config.inc.php");
?>
<!DOCTYPE html>
<?php

  if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/index.php");
  } else {
    $user_id = $_SESSION['user_id'];
  }


    $stmt = $dbh->prepare(" SELECT *
                            FROM users
                            WHERE user_id = :userid");
    $stmt->bindParam(":userid", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
$user_name = $row['user_name'];
$user_email = $row['user_email'];

?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update your settings</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<style>
  body {
    background: #fff url("../users/tunebasement.jpg") no-repeat fixed center;
    background-size: cover;
  }

  h1 {
    text-align: center;
  }

  .glyphicon {
    color: grey;
  }



</style>
<body>

  <div class="container">
      <div class="row">
        <div class="col-md-7 col-md-offset-0 col-xs-6 col-xs-offset-1 formcontainer">
          <h1>Update User Settings</h1>
          <br />
          <form class="form-horizontal" action="update_users.php" method="post">
              <div class="form-group <?= !empty($user_nameerror)?'has-error':'';?>">
                <div class="input-group">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                    <input type="hidden" name="user_id" value="<?= !empty($_SESSION['user_id'])?htmlspecialchars($_SESSION['user_id']):'';?>">
                    <input type="text" name="user_name" class="form-control"  placeholder="username" value="<?= !empty($user_name)?htmlspecialchars($user_name):'';?>">
                  </div>

                  <?php if (!empty($user_nameerror)): ?>
              <span class="help-block"><?= $user_nameerror;?></span>
              <?php endif; ?>
              </div>

              <div class="form-group <?= !empty($user_emailerror)?'has-error':'';?>">
                <div class="input-group">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                    <input type="text" name="user_email" class="form-control" id="user_email" placeholder="email" value="<?= !empty($user_email)?htmlspecialchars($user_email):'';?>">
                  </div>
                  <?php if (!empty($user_emailerror)): ?>
              <span class="help-block"><?= $user_emailerror;?></span>
              <?php endif; ?>
              </div>

              <br />
            <div class="form-group formdiv">
            <input type="submit" name="submit" class="btn btn-default" value="Update" />
            <a class="btn btn-default" href="../music/index.php?user_id=<?= $_SESSION['user_id'] ?>">Back</a>
            </div>
         
            </form>

            <div class="formdiv2">
              <form class="form-group" action="delete_users.php" method="post">
                <input type="hidden" name="user_id" value="<?= !empty($_SESSION['user_id'])?htmlspecialchars($_SESSION['user_id']):'';?>">
                <input type="submit" name="submit" class="btn btn-default" value="Permanently delete account" />
              </form>
            </div>

      </div>
    </div>
  </div>

<?php
}
?>
</body>
</html>
