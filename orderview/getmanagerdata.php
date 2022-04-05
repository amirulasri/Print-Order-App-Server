<?php
include_once '../conn.php';
include_once '../config/cors.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$orderid = "";
if(isset($_GET['d'])){
    $orderid = $_GET['d'];
}
$authHeader = getallheaders();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        http_response_code(200);

        //GET MANAGER USERNAME
        $stmtmanager = $conn->prepare("SELECT manageruser FROM orders WHERE orderid=?");
        $stmtmanager->execute([$orderid]);
        $getmanageruser = $stmtmanager->fetch();
        $manageruser = $getmanageruser['manageruser'];

        //CHECK IF MANAGER EXISTS IN DATA
        $stmt = $conn->prepare("SELECT name, email, manageruser FROM manageruser WHERE manageruser=?");
        $stmt->execute([$manageruser]);
        if ($stmt->rowCount() > 0) {
            $managerdata = $stmt->fetch();
            if ($manageruser == $managerdata['manageruser']) {
                $responsedata[] = $managerdata;
                echo json_encode($responsedata);
            }else{
                echo json_encode(array('status' => 'invalid'));
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
