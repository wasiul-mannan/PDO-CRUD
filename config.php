<?php

$databaseHost = 'localhost';
$databaseName = 'cst8257';
$databaseUsername = 'PHPSCRIPT';
$databasePassword = '1234';

try {

	$dbConn = new PDO("mysql:host={$databaseHost};dbname={$databaseName}", $databaseUsername, $databasePassword);
	$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

	echo $e->getMessage();
}
