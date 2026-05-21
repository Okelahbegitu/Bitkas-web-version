<?php
    include_once "../config/connect.php";
    
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data["id"];
    $role = $data["role"];
    $password = $data["password"];
    $nisn = $data["nisn"];

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $query = mysqli_prepare($conn, "INSERT INTO tb_akun (id_user, username, role, password) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE role = VALUES(role), password = VALUES(password)");
        if(!$query){
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Prepare failed: ' . mysqli_error($conn)
            ]);
            exit;
        }
        mysqli_stmt_bind_param($query, "ssss", $id, $nisn, $role, $password);
        if(mysqli_stmt_execute($query)){
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Siswa berhasil dipromosikan menjadi admin.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mempromosikan siswa: ' . mysqli_stmt_error($query)
            ]);
        }
    }
?>