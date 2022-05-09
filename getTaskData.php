<?php
//receiving client data
$clientData = json_decode($_POST['clientData'], true);
$taskid = $clientData['taskid'];
$listid = $clientData['listid'];


try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


//Preparing query to check if there's lists and task to show
$stmt = $dbh->prepare("SELECT * FROM tasks where id = $taskid;");

//executing query
$stmt->execute();

foreach ($stmt as $row) {
    $taskName = $row['name'];
	$status = $row['status'];
	$description = $row['description'];
	$start_date = $row['start_date'];
	$end_date = $row['end_date'];
}


//Preparing query to check if there's lists and task to show
$stmt = $dbh->prepare("SELECT * FROM lists where id = $listid;");

//executing query
$stmt->execute();

foreach ($stmt as $row) {
    $listName = $row['name'];
}


$JSONobject = array();
$JSONobject[] = array(
    "taskName" => $taskName, 
    "taskid" => $taskid, 
    "listid" => $listid, 
    "status" => $status, 
    "description" => $description, 
    "start_date" => $start_date, 
    "end_date" => $end_date, 
    "listName" => $listName
);

// Serialize
$serverData = json_encode($JSONobject);

// Serialize et envoie reponse
echo "$serverData";




//Set the PDO object to NULL.
$dbh = null;
?>