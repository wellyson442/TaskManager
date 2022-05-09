var actualIndex = 0;
var clickonBoddy = 0;
var intervalId = window.setInterval(function(){
    nextTip(actualIndex);
    actualIndex +=1;
    if(actualIndex ==4) actualIndex = 0;
  }, 10000);

  function nextTip(localIndex){
      if(localIndex == 99){
          actualIndex += 1;
          if(actualIndex ==4) actualIndex = 0;
          localIndex = actualIndex;
        } 
        var tips = {
            0: 'Create a new list by clicking on "New list" button ', 
            1: "Modify your list's name by clicking on it",
            2: 'Create new task on writing it name in the field below and clicking "new task" button',
            3: 'Modify your task on double clicking on it'
        };
        
    document.getElementById('tip').textContent = tips[localIndex];
  }

//change button's color on hover
function changecolor(color, id, isCross) {

    

    if(color == 'green' && isCross){
        document.getElementById(id).style.color = "#00844E";
        document.getElementById(id).style.borderColor = "#00844E";

        document.getElementById('cross').style.color = "#00844E";
        document.getElementById('cross').style.borderColor = "#00844E";
    } else if(color == 'blue' && isCross) {
        document.getElementById(id).style.color = "#3454D1";
        document.getElementById(id).style.borderColor = "#3454D1";

        document.getElementById('cross').style.color = "#3454D1";
        document.getElementById('cross').style.borderColor = "#3454D1";
    } else if(color == 'green' && !isCross){
        document.getElementById(id).style.color = "#00844E";
        document.getElementById(id).style.borderColor = "#00844E";
    } else if(color == 'blue' && !isCross){
        document.getElementById(id).style.color = "#3454D1";
        document.getElementById(id).style.borderColor = "#3454D1";
    }
}


function saveListName(id){
    var listName = document.getElementById('list_name_'+id).value;

    //creating tab with the values to send to server
    var JSONObject = {'listid': id, 'listName': listName};

    //Creating json object
    var clientData = JSON.stringify(JSONObject);

    // Sending Json data
    jQuery.ajax({type: "POST", url: "saveListName.php", dataType: "JSON", data: 'clientData=' + clientData,
        success: function(serverData) {

        }
    });
}

function modifyTask(listid, taskid){
    if(document.getElementById('confirm_save_popup').style.display != "block"){
        document.getElementById('modal').style.display = "block";
        getTaskInfo(listid, taskid);
    }
}

function hidemodal(){
    document.getElementById('modal').style.display = "none";
}

function getTaskInfo(listid, taskid){       
    //creating tab with the values to send to server
    var JSONObject = {'listid': listid, 'taskid': taskid};

    //Creating json object
    var clientData = JSON.stringify(JSONObject);

    // Sending Json data
    jQuery.ajax({type: "POST", url: "getTaskData.php", dataType: "JSON", data: 'clientData=' + clientData,
        success: function(serverData) {
            //what to do when server respond
            writeTaskInfo(serverData);
        }
    });
}

//treate server response
function writeTaskInfo(serverData) {
    // Tableau de données
    if (defined(serverData)) {
        for (val of serverData) {
            var taskName = val.taskName; 
            var status = val.status;
            var description = val.description;
            var start_date = val.start_date;
            var end_date = val.end_date;
            var listName = val.listName;
            var taskid = val.taskid;
            var listid = val.listid;


            document.getElementById('modal_task_name_value').value = taskName;
            document.getElementById('modal_description_value').value = description;
            document.getElementById('modal_start_date_value').value = start_date;
            document.getElementById('modal_end_date_value').value = end_date;

            document.getElementById('modal_task_id_value').value = taskid;
            document.getElementById('modal_list_id_value').value = listid;

        }
    } else{
        alert("could not get task data");
    }
}


// Test if variable is defined
function defined(myVar) {
    if (typeof myVar != 'undefined') return true;
    return false;
}


function saveOrNot(){
    document.getElementById('modal').style.display = "none";
    document.getElementById('confirm_save_popup').style.display = "block";
}

function dontSave(){
    document.getElementById('modal').style.display = "block";
    document.getElementById('confirm_save_popup').style.display = "none";

    var notSaved = new Array();
    var success = new Array();

    success[0] = '0';
    notSaved[0] = success;

    isSaved(notSaved);
}


function save(newTask){

    document.getElementById('modal').style.display = "block";
    document.getElementById('confirm_save_popup').style.display = "none";

    var taskName = document.getElementById('modal_task_name_value').value; 
    // var status = val.status;
    var description = document.getElementById('modal_description_value').value;
    var start_date = document.getElementById('modal_start_date_value').value;
    var end_date = document.getElementById('modal_end_date_value').value;

    var taskID = document.getElementById('modal_task_id_value').value;
    var listID = document.getElementById('modal_list_id_value').value;

    if(newTask == false){
        //creating tab with the values to send to server
        var JSONObject = {
            'newtask': false,
            'taskID': taskID,
            'listID': listID,
            'taskName': taskName,
            'description': description,
            'start_date': start_date,
            'end_date': end_date
        };
    } else{
        //creating tab with the values to send to server
        var JSONObject = {
            'newtask': true,
            'taskID': taskID,
            'listID': listID,
            'taskName': taskName,
            'description': description,
            'start_date': start_date,
            'end_date': end_date
        };
    }


    //Creating json object
    var clientData = JSON.stringify(JSONObject);

    // Sending Json data
    jQuery.ajax({type: "POST", url: "updateTaskInfo.php", dataType: "JSON", data: 'clientData=' + clientData,
        success: function(serverData) {
            //what to do when server respond
            isSaved(serverData);
        }
    });
}

function isSaved(serverData){
    for (val of serverData){
        if(val.success == '1'){
            document.getElementById('message').textContent = 'Modifications saved successfully'; 
            document.getElementById('message').style.color = '#00844E'; 

            setTimeout(function(){
                document.getElementById('message').textContent = 'You can modify your task properties here';
                document.getElementById('message').style.color = '#2A47B9';
            }, 5000);
        } else{
            document.getElementById('message').textContent = 'Modifications not saved.';
            document.getElementById('message').style.color = 'red'; 

            setTimeout(function(){
                document.getElementById('message').textContent = 'You can modify your task properties here';
                document.getElementById('message').style.color = '#2A47B9';
            }, 5000);
        }
    }

    location.reload();
}


function addTask(listID){
    if(document.getElementById('textarea_'+listID).value != ''){
        document.getElementById('modal_task_name_value').value = document.getElementById('textarea_'+listID).value;
        document.getElementById('modal_task_id_value').value = 'NULL';
        document.getElementById('modal_list_id_value').value = listID;
        document.getElementById('modal_description_value').value = '';
        document.getElementById('modal_start_date_value').value = '';
        document.getElementById('modal_end_date_value').value = '';
    
        document.getElementById('modal').style.display = 'block';
    }
}

function newOrUpdate(){
    var taskID = document.getElementById('modal_task_id_value').value;

    
    if(taskID == 'NULL'){
        save(true);
    } else{
        save(false);
    }
}

function isselected(id){
    if(id == 'last_modification'){
        document.getElementById('created_date_order').checked = false;
    } else{
        document.getElementById('last_modification').checked = false;
    }
}

function reorder(){
    var created_date_order = document.getElementById('created_date_order').checked;
    var last_modification = document.getElementById('last_modification').checked;

    if(created_date_order == true){
        location.href = 'http://localhost/taskManager/index.php?order_by=created_date ASC';
    } else if(last_modification == true){
        location.href = 'http://localhost/taskManager/index.php?order_by=last_modification_date DESC';
    }
}

function newlist(){
    location.href = 'http://localhost/taskManager/newlist.php';
}

function isToTrash(id, element){
    var msg = 'Permanently delete this item ?';
    //if element == 0 it is a List
    //if element == 1 it is a Task
    if(element == 0){
        element = 'list'; 
        msg = 'Permanently delete this List and all it tasks ?';
    } else if(element == 1){
        element = 'task'; 
        msg = 'Permanently delete this Task ?';
    } 

    document.getElementById('msg').textContent = msg;
    document.getElementById('elementtotrash').value = element;
    document.getElementById('idtotrash').value = id;

    document.getElementById('confirm_trash_popup').style.display = "block";
}

function notTrash(){
    document.getElementById('confirm_trash_popup').style.display = "none";
}

function trash(){
    var element_to_trash = document.getElementById('elementtotrash').value;
    var id_element_to_trash = document.getElementById('idtotrash').value;

    //creating tab with the values to send to server
    var JSONObject = {
        'element_to_trash': element_to_trash,
        'id_element_to_trash': id_element_to_trash
    };
    
    
        //Creating json object
        var clientData = JSON.stringify(JSONObject);
    
        // Sending Json data
        jQuery.ajax({type: "POST", url: "trash.php", dataType: "JSON", data: 'clientData=' + clientData,
            success: function(serverData) {
                location.reload();
            }
        });
}

function renderProgress(){
    var allProgressInputs = document.getElementsByClassName("progress_input");
    
    for (let input of allProgressInputs) {
        var values = input.value.split('/');
        var val1= values[0];
        var val2= values[1];
        var porcent = (val1*100) / val2;
        var id = input.id;

        if(isNaN(porcent)) porcent = 0;

        porcent = Math.trunc(porcent);

        document.getElementById('porcent_' + id).textContent = porcent + '%';
        document.getElementById('progress_bar_' + id).style.width = porcent + '%';

        if(porcent >= 50){
            document.getElementById('progress_bar_' + id).style.backgroundColor = '#04A764';
            document.getElementById('porcent_' + id).style.color = '#FBFBFC';
        } else if(porcent < 50){
            document.getElementById('progress_bar_' + id).style.backgroundColor = '#FCFC64';
            document.getElementById('porcent_' + id).style.color = '#6B6B6B';
        }
    }
}

function chooseStatus(event, taskid){
    var x = event.clientX;
    var y = event.clientY;

    var modal_status = document.getElementById('modal_status');

    document.getElementById('status_taskid').value = taskid;

    modal_status.style.left = x + 'px';
    modal_status.style.top = y + 'px';
    modal_status.style.display = 'block';
}

function changestatus(status){
    var taskid = document.getElementById('status_taskid').value;

    //creating tab with the values to send to server
    var JSONObject = {'taskid': taskid, 'status': status};

    //Creating json object
    var clientData = JSON.stringify(JSONObject);

    // Sending Json data
    jQuery.ajax({type: "POST", url: "updatestatus.php", dataType: "JSON", data: 'clientData=' + clientData,
        success: function(serverData) {
            //what to do when server respond
            location.reload();
        }
    });
}

function closemodal(){
    var modal_status = document.getElementById('modal_status');

    clickonBoddy+=1;
    
    if(modal_status.style.display == 'block' && clickonBoddy == 2){
        modal_status.style.display = 'none';
        clickonBoddy = 0;
    }
    
}

