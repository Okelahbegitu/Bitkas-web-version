<?php
//berbeda dengan GET_ALL_REAPORT_MOTHLY.php, bedany endpoin ini mengembalikan motlhy reaport yang dikeoompokan.
//contoh:
/*
    {
        status: "success",
        data: [{
            tahun: 2024,
            data: [
                {
                    bulan: 1,
                    total_pemasukan: 100000,
                    total_pengeluaran: 50000,
                    total_siswa: 20
                },
                {
                    bulan: 2,
                    total_pemasukan: 150000,
                    total_pengeluaran: 70000,
                    total_siswa: 25
                }
            ]
        }]
    }
*/

include '../config/connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid'
    ]);
    exit;
}

$query = mysqli_prepare($conn, "SELECT 
    YEAR(tanggal_transaksi) AS tahun,
    MONTH(tanggal_transaksi) AS bulan,
    COALESCE(SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END),0) AS total_pemasukan,
    COALESCE(SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END),0) AS total_pengeluaran,
    COUNT(DISTINCT nisn) AS total_siswa
    FROM tb_transaksi
    GROUP BY tahun, bulan
    ORDER BY tahun DESC, bulan DESC");

mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "tahun" => (int)$row['tahun'],
        "bulan" => (int)$row['bulan'],
        "total_pemasukan" => (int)$row['total_pemasukan'],
        "total_pengeluaran" => (int)$row['total_pengeluaran'],
        "total_siswa" => (int)$row['total_siswa']
    ];
}


echo json_encode([
    'status' => 'success',
    'data' => array_values($data)
]);

?>