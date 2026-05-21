<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

// Handle preflight request before method validation.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    include '../config/connect.php';
    try {


        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? $_GET['id'] ?? null;
        $query = "UPDATE tb_siswa SET status= 'nonaktif' WHERE id_siswa='$id'";
        if (mysqli_query($conn, $query)) {
            http_response_code(200);
            

            echo json_encode([
                'status' => 'success',
                'message' => 'Siswa berhasil dinonaktifkan'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menonaktifkan siswa'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
?>