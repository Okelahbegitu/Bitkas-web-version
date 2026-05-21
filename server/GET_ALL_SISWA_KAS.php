<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

//disini adalha mengambil semua siswa yang sudah bayar atau belum perminggu dalam satu bulan
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include '../config/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('m');
        $tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

        $query = mysqli_prepare($conn, "SELECT 
    s.nisn,
    s.nama_siswa,
    COALESCE(SUM(t.nominal),0) AS total_bayar,
    15000 - COALESCE(SUM(t.nominal),0) AS sisa_bayar
    FROM tb_siswa s
    Left JOIN tb_transaksi t 
    ON t.nisn = s.nisn
    AND t.jenis = 'pemasukan'
    AND MONTH(t.tanggal_transaksi) = ?
    AND YEAR(t.tanggal_transaksi) = ?
    AND CEIL(DAY(t.tanggal_transaksi)/7)
        WHERE s.status = 'aktif'
    GROUP BY s.nisn, s.nama_siswa


    ");
        mysqli_stmt_bind_param($query, "ii", $bulan, $tahun);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        $siswa = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $siswa[] = $row;
        }

        /*
        hasilnya adalah
        {
            "nisn": "1234567890",
            "nama_siswa": "John Doe",
            "total_bayar": 15000,
            "sisa_bayar": 0
        }
        */
        http_response_code(200);
        echo json_encode($siswa);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
?>