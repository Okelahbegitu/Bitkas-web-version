<?php
include '../fun/count_saldo.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include '../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $inputData = json_decode(file_get_contents('php://input'), true);
        if (!is_array($inputData)) {
            $inputData = [];
        }

        $bulanInput = $_GET['bulan'] ?? $inputData['dataMonthlyBody'] ?? null;
        $tahunInput = $_GET['tahun'] ?? $inputData['dataYearlyBody'] ?? null;

        $bulan = is_numeric($bulanInput) ? (int) $bulanInput : (int) date('m');
        $tahun = is_numeric($tahunInput) ? (int) $tahunInput : (int) date('Y');

        if ($bulan < 1 || $bulan > 12) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Bulan tidak valid'
            ]);
            exit;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Tahun tidak valid'
            ]);
            exit;
        }

        $query = mysqli_prepare($conn, "SELECT 
        FLOOR((DAY(tanggal_transaksi)-1)/7)+1 AS minggu_bulan,
        COALESCE(SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END),0) AS total_pemasukan,
        COALESCE(SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END),0) AS total_pengeluaran,
        (SELECT COUNT(DISTINCT nisn) AS total_siswa
        FROM tb_pending
        WHERE nominal_sisa >= 1) AS total_siswa
        FROM tb_transaksi
        WHERE MONTH(tanggal_transaksi) = ?
        AND YEAR(tanggal_transaksi) = ?
        GROUP BY minggu_bulan
        ORDER BY minggu_bulan;
        ");

        if (!$query) {
            throw new Exception('Gagal mempersiapkan query laporan');
        }

        mysqli_stmt_bind_param($query, "ii", $bulan, $tahun);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);

        $reportData = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reportData[] = [
                'minggu_bulan' => (int) $row['minggu_bulan'],
                'total_pemasukan' => (int) $row['total_pemasukan'],
                'total_pengeluaran' => (int) $row['total_pengeluaran'],
                'total_siswa' => (int) $row['total_siswa'],
            ];
        }


        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'saldo' => countSaldo($conn),
            'total_pemasukan' => (int) array_sum(array_column($reportData, 'total_pemasukan')),
            'total_pengeluaran' => (int) array_sum(array_column($reportData, 'total_pengeluaran')),
            'total_siswa' => (int) $reportData[0]['total_siswa'] ?? 0,
            'data' => $reportData
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server',
            'error' => $e->getMessage()
        ]);
    }

} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
