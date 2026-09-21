<?php

$host = 'localhost';
$port = '3306'; // Database server port (default: 3306)
$db = 'estacionamiento';
$user = 'root';	// Change for your own user
$pass = ''; //Change for your own password

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
	PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES	=> false,
];

try{
	$pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e){
	die("Connection error");
}

