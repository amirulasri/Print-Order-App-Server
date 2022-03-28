<?php
header('Access-Control-Allow-Origin: http://localhost:8100');
include('conn.php');
$orderid = 1;
$increment = 1;
$responsedata = array();
try {
    $statementgetitems = $conn->prepare("SELECT * FROM items WHERE custid = ?");
    $statementgetitems->execute([$orderid]);
    while ($row = $statementgetitems->fetch(PDO::FETCH_OBJ)) {
        $responsedata[] = $row;
    }
} catch (PDOException $e) {
    //echo $e->getMessage();
}
echo json_encode($responsedata);