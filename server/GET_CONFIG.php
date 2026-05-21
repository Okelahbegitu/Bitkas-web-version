<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
    header("Access-Control-Allow-Origin: *");

    if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        exit;
    }

    include '../config/connect.php';

    $query = "SELECT * FROM tb_config";
    $result = mysqli_query($conn, $query);
    $configs = [];
    // niatny isi array adalah [{name, value} {} {}]
    while ($row = mysqli_fetch_assoc($result)) {
        $configs[] = [
            'name' => $row['name'],
            'value' => $row['value']
        ];
    }
    echo json_encode($configs);
?>