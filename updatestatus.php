<?php
//receiving client data
$clientData = json_decode($_POST['clientData'], true);
$taskid = $clientData['taskid'];
$status = $clientData['status'];


try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$today = date("Y-m-d H:i:s");

$stmt = $dbh->prepare("UPDATE tasks SET status = $status WHERE id = $taskid; 
						UPDATE tasks SET last_modification_date=$today WHERE id = $taskid;");

//executing query
$stmt->execute();

$JSONobject = array();

$JSONobject[] = array(
    "success" => "1"
);

// Serialize
$serverData = json_encode($JSONobject);

// Serialize et envoie reponse
echo "$serverData";


//Set the PDO object to NULL.
$dbh = null;
?>