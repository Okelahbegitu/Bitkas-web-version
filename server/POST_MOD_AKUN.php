<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, ngrok-skip-browser-warning");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight request
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include '../config/connect.php';
global $conn;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_siswa = $data['id_siswa'];
    $role = $data['role'];
    $password = $data['password'];
    $nisn = $data['nisn'];

    $query = mysqli_prepare($conn, "INSERT INTO tb_akun (id_user, username, role, password) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE role = ?, password = ?");
    mysqli_stmt_bind_param($query, "ssssss", $id_siswa, $nisn, $role, $password, $role, $password);
    if(mysqli_stmt_execute($query)) {
        echo json_encode(["message" => "Role updated successfully"]);
    } else {
        echo json_encode(["message" => "Failed to update role"]);
    }
}

?>