<?php
include_once 'conn.php';
include_once 'config/cors.php';
include_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $uname = $data->username;
    $pass = $data->password;
    $stmt = $conn->prepare("SELECT * FROM customerlogin WHERE username=?");
    $stmt->execute([$uname]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        if (password_verify($pass, $user['userpass'])) {
            $key = "CVs3gubehD35BjiiSFs8Ie7Xt0YX2OITufeqdxvAb20QW497j8GA7DGV3VmWjU8";
            $payload = array(
                'id' => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'phoneno' => $user['phoneno']
            );

            $token = JWT::encode($payload, $key, 'HS256');
            http_response_code(200);
            echo json_encode(array('token' => $token));
        } else {
            http_response_code(400);
            echo json_encode(array('message' => 'Login Failed 2!'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('message' => 'Login Failed 1!'));
    }
}
