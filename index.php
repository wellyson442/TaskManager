<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html  xml:lang="fr" lang="fr" dir="ltr">
  <head>
    <title>Task Manager</title>
	<link rel="stylesheet" href="./CSS/index.css">
	<script src="./JS/index.js" defer></script>
	<script src="./JS/jquery.3.6.0.js"></script>
</head>

<?php
$order_by = 'last_modification_date DESC';
if (isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
}

$checkedOne = ''; 
$checkedTwo = ''; 

if($order_by == 'last_modification_date DESC') $checkedOne = 'checked';
if($order_by == 'created_date ASC') $checkedTwo = 'checked';
?>

<body onload='renderProgress()' onclick='closemodal()'>
	<!-- Contenu de la page respectant la syntaxe XML. -->
	
	<header> 
		<p id='tip'> Create a new list by clicking on "New list" button </p> 
			<button class='close_tips buttons' id='close_button' onclick='nextTip(99)' onmouseover="changecolor('green', 'close_button', 'true')" onmouseout="changecolor('blue', 'close_button', 'true')"> Next Tip
				<span id='cross'> > </span> 
			</button>
		</header>

	<div class='separator'>  
		<button class='buttons' id='newList' onclick='newlist()' onmouseover="changecolor('green', 'newList')" onmouseout="changecolor('blue', 'newList')"> + New List
		</button>

		<fieldset class='order_by'>
			
			<div class='input_radio'>
				<legend>Order By:</legend>
				<input onclick="isselected('last_modification')" type="radio" id="last_modification" <?php echo $checkedOne ?>>
				<label>Last Modification</label>
			</div>

			<div class='input_radio'>
				<input onclick="isselected('created_date_order')" type="radio" id="created_date_order" <?php echo $checkedTwo ?>>
				<label>Created date order</label>
			</div>

			<div class='input_radio'>
				<button onclick="reorder()" class='reorder'>Reorder</button>
			</div>
		</fieldset>

	</div>

	<section id='section'>


<?php

try {
	include_once 'parametres.php';

	//trying to connect to DataBase
	$dbh = new PDO("mysql:host=localhost;dbname=$dbName", $user, $pass);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

//Preparing query to check if there's lists and task to show
$stmt = $dbh->prepare("SELECT * FROM lists");

//executing query
$stmt->execute();

//nombre de résultats
$count = $stmt->rowCount();

if($count >= 1){
	foreach ($stmt as $row) {
		$listID = $row['id'];
		$listName = $row['name'];

		echo "<div class='content-wrapper'>";
		
		echo "<div class='list-wrapper'>";
		echo "<div class='listName'>";
		echo "<div id='$listID'  class='trash trash_list' onclick='isToTrash($listID, 0)'> </div>";
		$input = "<input type='text' id='list_name_$listID' onfocusout=saveListName(". "'$listID'" .  ") value =" . "'$listName'". ">";
		echo $input;

		echo "<div class='progress_bar_border'>";
			echo "<div id='progress_bar_$listID' class='progress_bar'> <p id='porcent_$listID'> </p> </div>";
		echo "</div>";

			echo "</div>";
			
			echo "<div id='$listID' class='tabList'>";
			
			echo "<div class='newtaskDiv'>";
			echo " <textarea id='textarea_$listID' placeholder='Enter your task name here...' class='input_task' type='text'></textarea>"; 
			$expression = "<button class='newtask' onclick='addTask($listID)' id='task_$listID' onmouseover=" .  '"' . "changecolor('green', 'task_$listID')" . '" onmouseout="' . "changecolor('blue', 'task_$listID')" . '"> + New task
			</button>';
			
			
			echo $expression;
			echo "</div>";
		
		
			//Preparing query to get task from each list
			$stmt2 = $dbh->prepare("SELECT * FROM tasks where listID = $listID ORDER BY $order_by;");
		
			//executing query
			$stmt2->execute();

			$progress=0;
			$numberOfTasks=0;
		
		
			foreach ($stmt2 as $row2) {
				$taskID = $row2['id'];
				$taskListID = $row2['listID'];
				$taskName = $row2['name'];
				$status = $row2['status'];
				$description = $row2['description'];
				$start_date = $row2['start_date'];
				$end_date = $row2['end_date'];
				echo "<div class='task_container'>";
				
					echo "<div class='tasks' id='$taskID' onclick='modifyTask($listID, $taskID)'>";
						$style2 = 'text-decoration: none;';

						if($status == '0'){
							$style = 'background: url("http://localhost/taskManager/Images/created.png") no-repeat;';
						} else if($status == '1'){
							$style = 'background: url("http://localhost/taskManager/Images/inprogress.png") no-repeat;';
						} else if($status == '2'){
							$style = 'background: url("http://localhost/taskManager/Images/finished.png") no-repeat;'; 
							$style2 = 'text-decoration: line-through;';

							$progress+=1;
						} 
						
						echo "<p style='$style2'> $taskName </p>";
						echo "</div>";
						
						echo "<div class='status' onclick='chooseStatus(event, $taskID)' style='$style'> </div>";
						echo "<div id='$taskID' class='trash trash_task' onclick='isToTrash($taskID, 1)'> </div>";
						echo '</div>';
						
						
				
				$numberOfTasks+=1;
			}

			echo "<input type='hidden' id='$listID' class='progress_input' value='$progress/$numberOfTasks'>";
			echo "</div>";
			echo "</div>";
		echo "</div>";
	}
} else{
	echo "<p class='noLists'> No Lists added yet </p>";
}


//Set the PDO object to NULL.
$dbh = null;
?>


<div id='modal' class='modal'>
	<span id='modal_cross'
	onclick='hidemodal()'
	> &times; 
</span>
	<input id='modal_task_id_value' type="hidden" value='99999'>
	<input id='modal_list_id_value' type="hidden" value='99999'>

	<h1 id='message' class='modal_title'> You can modify your task properties here </h1>
	
	<div class='modal_task_and_name_div'>
		<h1 class='modal_task_and_name_title'> Task Name and Status </h1>
		<input id='modal_task_name_value' value='My new task'>
	</div>

	<div class='modal_description_div'>
		<h1 class='modal_description_title'> Description </h1>
		<textarea id='modal_description_value' placeholder='Enter some description here...' type='text'></textarea>
	</div>

	<div class='modal_dates' >
		<h1 class='modal_dates_title'> Dates </h1>
			<div class='modal_date'>
				<h1 class='modal_start_end_dates_title'> Start date </h1>
				<input id='modal_start_date_value' type="date" value="">
			</div>

			<div class='modal_date'>
				<h1 class='modal_start_end_dates_title'> End date </h1>
				<input id='modal_end_date_value' type="date" value="">
			</div>
	</div>

	<button id='modal_button_save' onclick='saveOrNot()'> Save </button>

</div>

<div id='confirm_save_popup' class='confirm_save'>
	<p> Save modifications ? </p>

	<button id='confirm_button' onclick='newOrUpdate()'> Save </button>
	<button id='cancel_button' onclick='dontSave()'> Cancel </button>
</div>


<div id='confirm_trash_popup' class='confirm_save'>
	<p id='msg'> Permanently delete this item ? </p>
	<input type="hidden" id='elementtotrash' value='nothing'>
	<input type="hidden" id='idtotrash' value='00000'>

	<button id='confirm_trash' onclick='trash()'> Delete </button>
	<button id='cancel_trash' onclick='notTrash()'> Cancel </button>
</div>

<div id='modal_status' class='modal_status'>
	<h1> Status </h1>
	<div onclick="changestatus('0');" class='status' style='background: url("http://localhost/taskManager/Images/created.png") no-repeat;'> <h3> Created </h3> </div>
		<br>
	<div onclick="changestatus('1');" class='status' style='background: url("http://localhost/taskManager/Images/inprogress.png") no-repeat;'> <h3> In progress </h3> </div>
		<br>
	<div onclick="changestatus('2');" class='status' style='background: url("http://localhost/taskManager/Images/finished.png") no-repeat;'> <h3> Finished </h3> </div>
		<br>

	<input type="hidden" id='status_taskid' value=''>
</div>
	
</section>
</body>
</html>