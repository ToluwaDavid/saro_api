<?php
include_once 'config/db.php';
include_once 'config/functions.php';
include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

include_once 'config/cors.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $phone = $data->phonenumber;
    $pass = $data->password;


    $sql = $conn->query("SELECT * FROM user WHERE phoneno = '$phone'");
    if ($sql->num_rows > 0) {
        $user = $sql->fetch_assoc();
        if (password_verify($pass, $user['password'])) {
            
            $data = login($user['id']);

            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'Login Failed'));
        }
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'User does not exist'));
 
    }
}