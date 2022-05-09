<?php
//receiving client data
$clientData = json_decode($_POST['clientData'], true);
$listid = $clientData['listid'];
$listName = $clientData['listName'];


try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


$stmt = $dbh->prepare("UPDATE lists SET name = '$listName' WHERE id = '$listid';");


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