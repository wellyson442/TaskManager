<?php
//receiving client data
$clientData = json_decode($_POST['clientData'], true);
$element_to_trash = $clientData['element_to_trash'];
$id_element_to_trash = $clientData['id_element_to_trash'];


try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$today = date("Y-m-d H:i:s");

if($element_to_trash == 'list'){
    $stmt = $dbh->prepare("DELETE FROM lists WHERE id = $id_element_to_trash;");
    //executing query
    $stmt->execute();
    $stmt2 = $dbh->prepare("DELETE FROM tasks WHERE listID = $id_element_to_trash;");
    //executing query
    $stmt2->execute();
} else if($element_to_trash == 'task'){
    $stmt3 = $dbh->prepare("DELETE FROM tasks WHERE id = $id_element_to_trash;");
    //executing query
    $stmt3->execute();
}


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