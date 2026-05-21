<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'config.php';
global $conn;

$id_siswa = $_GET['id_siswa'];

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = mysqli_prepare($conn, "SELECT * FROM akun WHERE id_siswa = ?");
    mysqli_stmt_bind_param($query, "i", $id_siswa);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

?>