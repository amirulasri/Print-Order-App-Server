<?php
include_once 'conn.php';
include_once 'config/cors.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

	$uname = $data->username;
    $fname = $data->fullname;
	$phoneno = $data->phoneno;
    $pass = $data->password;

    // Hash Password
    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    $sql = $conn->query("INSERT INTO customerlogin (id, username, userpass, fullname, phoneno) VALUES (NULL, '$uname', '$hashed', '$fname', '$phoneno')");
    if ($sql) {
        http_response_code(201);
        echo json_encode(array('message' => 'User created'));
    } else {
        http_response_code(500);
        echo json_encode(array('message' => 'Internal Server error'));
    }
} else {
    http_response_code(404);
}