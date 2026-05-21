<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS ,PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include '../config/connect.php';
global $conn;
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nisn = $data['nisn'];
    $nama = $data['nama'];
    $gender = $data['gender'];
    $tanggallahir = $data['tanggallahir'];
    $id = $data['id'];

    try {
    $query = mysqli_prepare($conn, "UPDATE tb_siswa SET nisn = ?, nama_siswa = ?, gender = ?, tanggal_lahir = ? WHERE id_siswa = ?");
    mysqli_stmt_bind_param($query, "sssss", $nisn, $nama, $gender, $tanggallahir, $id);
    mysqli_stmt_execute($query);
    mysqli_stmt_close($query);
    }
    catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
}
?>