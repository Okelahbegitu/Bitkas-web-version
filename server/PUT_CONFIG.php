<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    exit;
}

include '../config/connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? null;
$value = $data['value'] ?? null;

$query = mysqli_prepare($conn, "INSERT INTO tb_config (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)");
mysqli_stmt_bind_param($query, "ss", $name, $value);
if (mysqli_stmt_execute($query)) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pengaturan berhasil diperbarui'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memperbarui pengaturan: ' . mysqli_error($conn)
    ]);
}
?>