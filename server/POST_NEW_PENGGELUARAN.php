<?php
include '../fun/count_saldo.php';
include '../fun/id_generator.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../config/connect.php";
    $data = json_decode(file_get_contents("php://input"), true);

    $id_transaksi = generateID('T');
    $keterangan = $data['keterangan'];
    $nominal = $data['nominal'];

    $tanggal_transaksi = date("Y-m-d H:i:s");

    $saldo = countSaldo($conn);

    if ($nominal <= 0 || $nominal > $saldo) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Nominal harus lebih besar dari 0 dan tidak boleh melebihi saldo saat ini ($saldo)"    
        ]);
        exit;
    }

    $query = mysqli_prepare($conn, "
INSERT INTO tb_transaksi(
    id_transaksi, 
    nisn,
    keterangan, 
    jenis, 
    nominal, 
    tanggal_transaksi
) 
VALUES (?, NULL, ?, 'pengeluaran', ?, ?)
");

    mysqli_stmt_bind_param($query, "ssis", $id_transaksi, $keterangan, $nominal, $tanggal_transaksi);
    if (mysqli_stmt_execute($query)) {
        //ngirim response
        http_response_code(201);
        echo json_encode(["message" => "Penggeluaran berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error: " . mysqli_error($conn)]);
    }
}
?>

