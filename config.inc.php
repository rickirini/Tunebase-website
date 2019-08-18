<?php
$config = array();
$config['db_host'] = 'localhost';
$config['db_user'] = 's3253481';
$config['db_pass'] = 'kuekoh9bai';
$config['db_name'] = 's3253481';


try {
	$dbh = new PDO("mysql:dbname=s3253481;host=localhost", 's3253481', 'kuekoh9bai');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}

catch(PDOException $e) {
	die($e->getMessage());
}


?>
