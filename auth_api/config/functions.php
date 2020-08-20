<?php 

function login( $id ){
    global $conn;
    $sql = $conn->query("SELECT * FROM user WHERE id = '{$id}'");

    $user = $sql->fetch_array();

    $data['identity'] = array(
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phoneno'],
        'token' => $user['token']
    ); 

    $sql = $conn->query("SELECT * FROM user_setup WHERE user_id = '{$id}'");

    while ($d = $sql->fetch_assoc()) {
        $sql2 = $conn->query("SELECT * FROM situations WHERE id = '{$d['situations_id']}'");
        $author = $sql2->fetch_assoc();
        $data['user_setup'][] = array(
            'id' => $d['id'],
            'title' => $author['title'],
            'message' => $d['message'],
            'contact_1' => $d['contact_1'],
            'contact_2' => $d['contact_2'],
            'contact_3' => $d['contact_3']
        );
    }
	
	return $data;} 