<?php
    require_once "db.php";
    header('Content-Type: application/json');
    if(isset($_GET['getID']))
        echo json_encode(array("status"=>"ok","data"=>DB::get($_GET['getID'])));
    else if(isset($_GET['updateProfile']))
        DB::handleUpdate();
    else if(isset($_GET['addProject']))
        DB::handleAdd();
    else if(isset($_GET['getAll']))
        echo json_encode(DB::getAll());
    else if(isset($_GET['updateProject']))
        DB::handleProjectUpdate();
    else if(isset($_GET['deleteID'])){
        $id = $_GET['deleteID'];
        $conn = DB::connect();
        $conn->query("DELETE FROM projects WHERE id = $id") or die(json_encode(array("status"=>"error","msg"=>$conn->error)));
        echo json_encode(array("status"=>"success"));
    }
    else
        echo json_encode(array("status"=>"error","msg"=>"No operation is specified"));
?>