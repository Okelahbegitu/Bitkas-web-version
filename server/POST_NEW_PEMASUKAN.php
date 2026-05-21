<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

include '../config/connect.php';
include '../fun/id_generator.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Metode HTTP tidak valid']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data) || !isset($data['nisn'], $data['nominal'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Data request tidak valid']);
    exit;
}

$nisn = trim((string) $data['nisn']);
$nominal = (int) $data['nominal'];

if ($nisn === '' || $nominal <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'NISN atau nominal tidak valid']);
    exit;
}

global $conn;
$id_transaksi = generateID('T');
$tanggal_transaksi = date('Y-m-d H:i:s');

if ($nominal <= 15000) {
    $query = mysqli_prepare($conn, "INSERT INTO tb_transaksi (id_transaksi, nisn, jenis, nominal, keterangan, tanggal_transaksi) VALUES (?, ?, 'pemasukan', ?, NULL, ?)");
    mysqli_stmt_bind_param($query, "ssis", $id_transaksi, $nisn, $nominal, $tanggal_transaksi);

    if (!$query) {
        http_response_code(500);
        echo json_encode(['message' => 'Gagal menyiapkan query']);
        exit;
    }

    if (mysqli_stmt_execute($query)) {
        http_response_code(201);
        echo json_encode(['message' => 'Pemasukan berhasil ditambahkan']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . mysqli_error($conn)]);
    }
} else {
    mysqli_begin_transaction($conn);

    $nominal_lebih = $nominal - 15000;

    $query_transaksi = mysqli_prepare($conn, "INSERT INTO tb_transaksi (id_transaksi, nisn, jenis, nominal, keterangan, tanggal_transaksi) VALUES (?, ?, 'pemasukan', ?, NULL, ?)");
    $nominal_pemasukan = 15000;
    if (!$query_transaksi) {
        mysqli_rollback($conn);
        http_response_code(500);
        echo json_encode(['message' => 'Gagal menyiapkan query transaksi']);
        exit;
    }

    mysqli_stmt_bind_param($query_transaksi, "ssis", $id_transaksi, $nisn, $nominal_pemasukan, $tanggal_transaksi);

    $query_pending = mysqli_prepare($conn, "UPDATE tb_pending SET nominal_sisa = nominal_sisa + ? WHERE nisn = ?");
    if (!$query_pending) {
        mysqli_rollback($conn);
        http_response_code(500);
        echo json_encode(['message' => 'Gagal menyiapkan query pending']);
        exit;
    }

    mysqli_stmt_bind_param($query_pending, "si", $nominal_lebih, $nisn);

    if (mysqli_stmt_execute($query_transaksi) && mysqli_stmt_execute($query_pending)) {
        mysqli_commit($conn);
        http_response_code(201);
        echo json_encode(['message' => 'Pemasukan berhasil ditambahkan dan sisa dimasukkan ke pending']);
    } else {
        mysqli_rollback($conn);
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . mysqli_error($conn)]);
    }

}
?>