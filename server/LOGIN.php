<?php
include '../config/connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../config/connect.php';
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? ($_GET['username'] ?? null);
        $password = $data['password'] ?? ($_GET['password'] ?? null);

        if ($username && $password) {
            $query = mysqli_prepare($conn, "SELECT * FROM tb_akun WHERE username = ? AND password = ?");
            mysqli_stmt_bind_param($query, "ss", $username, $password);
            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);


            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                http_response_code(200);
                echo json_encode([
                    'status' => 'success',
                    'nisn' => $user['username'],
                    'role' => $user['role'],
                    'isLogin' => true

                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => 'error'
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Username dan password harus diisi'
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