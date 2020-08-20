<?php
include_once 'config/db.php';
include_once 'config/functions.php';
include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

include_once 'config/cors.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $fname = $data->fullname;
    $mail = $data->email;
    $phone = $data->phonenumber;
    $pass = $data->password;

    $sql = $conn->query("SELECT * FROM user WHERE phoneno = '{$phone}'");
    $check = $sql-> fetch_array();
    if ($check['phoneno'] == $phone){

        exit(json_encode(array('stats' => $phone )));
    }

    
    $sql = $conn->query("SELECT * FROM user WHERE email = '{$mail}'");
    $check2 = $sql-> fetch_array();
    if ($check2['email'] == $mail){
        //email already exists
        exit(json_encode(array('email' => $mail. ' as been used to register' )));
    }
    


    //Hash Password
    $hashed = password_hash($pass, PASSWORD_DEFAULT);


    $sql = $conn->query("INSERT INTO user (id, name, email, phoneno, password) VALUES (NULL, '$fname','$mail','$phone','$hashed')");
    if ($sql) {
        $sql2 = $conn->query("SELECT * FROM user WHERE phoneno = '$phone'");
        $user = $sql2->fetch_assoc();

    $key = "pU#SsY(Ga%4T*iGV";

    $payload = array(
        'id' => $user['id'],
        'fullname' => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phoneno'],
        'pass' => $user['password']
    );

    $token = JWT::encode($payload, $key);

    $sql = $conn->query("UPDATE user SET token = '". $token ."' WHERE id = '{$user['id']}'");

        $sql2 = $conn->query("SELECT * FROM situations");
        while ($d = $sql2->fetch_assoc()) {
            $sql3 = $conn->query("INSERT INTO user_setup (id, user_id, situations_id, message) VALUES (NULL, '{$user['id']}','{$d['id']}','{$d['message']}')");
        }      

        $sql = $conn->query("INSERT INTO location (id, u_id) VALUES (NULL, '{$user['id']}')");

        $data = login($user['id']);
        http_response_code(200);
        echo json_encode($data);
    }

} else {
    http_response_code(404);
}