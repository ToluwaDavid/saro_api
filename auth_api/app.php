<?php
include_once 'config/db.php';
include_once 'config/cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = $conn->query("SELECT * FROM user WHERE id = '$id'");
        $data = $sql->fetch_assoc();
    }else{
        $data = array();
        $sql = $conn->query("SELECT * FROM user");
        while ($d = $sql->fetch_assoc()) {
            $data[] = $d;
        }
        
    }
    //return json data
    exit(json_encode($data));
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $data = json_decode(file_get_contents("php://input"));
        $sql = $conn->query("UPDATE user SET name = '". $data->name ."', email = '". $data->email ."' WHERE id = '$id'");
        if ($sql) {
            exit(json_encode(array('status' => 'update successful')));
        }else{
            exit(json_encode(array('status' => 'error updating'))); 
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = $conn->query("DELETE FROM user WHERE id = '$id'");

        if ($sql) {
            exit(json_encode(array('status' => 'deleted')));
        }else{
            exit(json_encode(array('status' => 'success')));
        }
    }
}