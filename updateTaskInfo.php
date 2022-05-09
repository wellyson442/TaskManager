<?php
//receiving client data
$clientData = json_decode($_POST['clientData'], true);
$newtask = $clientData['newtask'];
$taskID = $clientData['taskID'];
$listID = $clientData['listID'];
$taskName = $clientData['taskName'];
$description = $clientData['description'];
$start_date = $clientData['start_date'];
$end_date = $clientData['end_date'];


try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$today = date("Y-m-d H:i:s");

if($newtask == 'true'){
    $stmt = $dbh->prepare("INSERT INTO tasks (id, listID, name, status, description, start_date, end_date, last_modification_date, created_date) VALUES (NULL, '$listID', '$taskName', '0', '$description', '$start_date', '$end_date', '$today', '$today');");
} else{
    $stmt = $dbh->prepare("UPDATE tasks SET name='$taskName', status='1', description='$description', start_date='$start_date', end_date='$end_date', last_modification_date='$today' WHERE id='$taskID' AND listID='$listID'");
}


//executing query
$stmt->execute();

//nombre de résultats
$count = $stmt->rowCount();


$JSONobject = array();

if($count >= 1){
    $JSONobject[] = array(
        "success" => "1"
    );
} else{
    $JSONobject[] = array(
        "success" => "0"
    );
}

// Serialize
$serverData = json_encode($JSONobject);

// Serialize et envoie reponse
echo "$serverData";




//Set the PDO object to NULL.
$dbh = null;
?>