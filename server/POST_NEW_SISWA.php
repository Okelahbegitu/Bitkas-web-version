<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS ,PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include '../config/connect.php';
include '../fun/id_generator.php';
global $conn;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nisn = $data['nisn'];
    $nama = $data['nama_siswa'];
    $gender = $data['gender'];
    $tanggallahir = $data['tanggal_lahir'];
    $id = generateID("S");

    try {
        $query = mysqli_prepare($conn, "INSERT INTO tb_siswa VALUES (?, ?, ?, ?, ?, 'aktif')");
        mysqli_stmt_bind_param($query, "sssss", $id, $nisn, $nama, $gender, $tanggallahir);
        mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        http_response_code(201);
        echo json_encode(['message' => 'Siswa berhasil ditambahkan']);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        exit;
    }

}
?>