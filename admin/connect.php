<?php

	$dsn	= 'mysql:host=localhost;dbname=Labrary';
	$user	= 'root';
	$pass	= '';
	$option = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	);

	try {
		$bdd = new PDO($dsn, $user,$pass, $option);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch  (PDOException $e) {
		echo "Faileeeeeed" . $e->getMessage();
	}

?>