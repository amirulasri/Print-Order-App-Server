<?php
include_once '../conn.php';
include_once '../config/cors.php';

$orderid = "";
if (isset($_GET['d'])) {
    $orderid = $_GET['d'];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        http_response_code(200);

        $stmt = $conn->prepare("SELECT * FROM items WHERE orderid=?");
        $stmt->execute([$orderid]);
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $responsedata[] = $row;
        }
        echo json_encode($responsedata);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('status' => 'error'));
    }
} else {
    http_response_code(401);
    echo json_encode(array('status' => 'error'));
}
