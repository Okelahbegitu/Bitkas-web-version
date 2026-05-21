<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning');
include '../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = mysqli_query($conn, "SELECT s.nama_siswa, p.nominal_sisa 
        FROM tb_pending p 
        JOIN tb_siswa s ON p.nisn = s.nisn  
        WHERE s.status = 'aktif'
        ORDER BY s.nama_siswa ASC");
        
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = [
                'nama_siswa' => $row['nama_siswa'],
                'nominal_sisa' => (int) $row['nominal_sisa']
            ];
        }
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Data saldo siswa berhasil diambil',
            'data' => $data
        ]);
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
        'message' => 'Metode HTTP tidak valid'
    ]);
}
?>