<?php
include_once 'config/db.php';
include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

include_once 'config/cors.php';

//get all headers
$authHeader = getallheaders();

if (isset($authHeader['Authorization']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $authHeader['Authorization'];
    $token = explode(" ", $token)[1];

    try {
        $key = "pU#SsY(Ga%4T*iGV";
        $decoded = JWT::decode($token, $key, array('HS256'));

        //DO this if token decoded succeessfuly

        http_response_code(200);
        echo json_encode($decoded);

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('message' => 'Please authenticate'));

    }
} else {
    http_response_code(401);
    echo json_encode(array('message' => 'Please authenticate'));
}