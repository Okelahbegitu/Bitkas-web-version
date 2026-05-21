<?php
include '../config/connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //get param
    $month = isset($_GET['bulan']) ? (int) $_GET['bulan'] : null;
    $year = isset($_GET['tahun']) ? (int) $_GET['tahun'] : null;

    if (!$month || !$year) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Parameter bulan dan tahun wajib diisi'
        ]);
        exit;
    }

    $query = mysqli_prepare($conn, "SELECT 
            tanggal_transaksi,
            jenis,
            nominal,
            s.nama_siswa,
            keterangan 
            FROM tb_transaksi
            LEFT JOIN tb_siswa s ON tb_transaksi.nisn = s.nisn 
            WHERE MONTH(tanggal_transaksi) = ? AND YEAR(tanggal_transaksi) = ?
            ORDER BY `tb_transaksi`.`tanggal_transaksi` DESC");
    mysqli_stmt_bind_param($query, "ii", $month, $year);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            "tanggal_transaksi" => $row['tanggal_transaksi'],
            "jenis" => $row['jenis'],
            "nominal" => (int) $row['nominal'],
            "nama" => $row['nama_siswa'],
            "keterangan" => $row['keterangan']
        ];
    }
    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);

    exit;
}
?>