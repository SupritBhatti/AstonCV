<?php

$db_host = 'localhost';
$db_name = 'astoncv';
$username = 'root';
$password = '';

try {
	$db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	error_log($ex->getMessage()); // logs privately to server
    echo("Failed to connect to the database.");
	exit;
}
?>