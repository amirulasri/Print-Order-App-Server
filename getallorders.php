<?php
include_once 'conn.php';
include_once 'config/cors.php';
include_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$authHeader = getallheaders();
if (isset($authHeader['Authorization']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $authHeader['Authorization'];
    $token = explode(" ", $token)[1];

    try {
        $key = "CVs3gubehD35BjiiSFs8Ie7Xt0YX2OITufeqdxvAb20QW497j8GA7DGV3VmWjU8";
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $arraydecoded = (array)$decoded;

        http_response_code(200);

        //CHECK IF USER EXISTS IN DATA
        $stmt = $conn->prepare("SELECT * FROM customerlogin WHERE username=?");
        $stmt->execute([$arraydecoded['username']]);
        if ($stmt->rowCount() > 0) {
            $userdata = $stmt->fetch();
            if ($arraydecoded['id'] == $userdata['id']) {
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customerid=? ORDER BY orderid DESC");
                $stmt->execute([$arraydecoded['id']]);
                while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $responsedata[] = $row;
                }
                echo json_encode($responsedata);
            } else {
                echo json_encode(array('status' => 'error'));
            }
        } else {
            echo json_encode(array('status' => 'error'));
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('status' => 'error'));
    }
} else {
    http_response_code(401);
    echo json_encode(array('status' => 'error'));
}
