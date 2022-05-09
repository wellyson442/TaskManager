<?php
try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


$stmt = $dbh->prepare("INSERT INTO lists (id, name) VALUES (NULL, 'New list');");


//executing query
$stmt->execute();

//Set the PDO object to NULL.
$dbh = null;

header('location: http://localhost/taskManager/index.php?order_by=last_modification_date');
?>