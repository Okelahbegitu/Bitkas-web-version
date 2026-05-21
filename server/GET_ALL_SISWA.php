<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
$id = $_GET['id'] ?? null;
include '../config/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT s.nisn, s.id_siswa, s.nama_siswa, s.gender, s.tanggal_lahir, p.nominal_sisa, a.role FROM tb_siswa s INNER JOIN tb_pending p ON s.nisn = p.nisn LEFT JOIN tb_akun a ON a.id_user = s.id_siswa WHERE s.status = 'aktif'";
        $params = [];
        $types = '';

        if ($id) {
            $query .= " AND s.id_siswa = ?";
            $params[] = $id;
            $types .= 's';
        }

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception('Gagal menyiapkan query: ' . mysqli_error($conn));
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $siswa = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $siswa[] = [
                'nisn' => $row['nisn'],
                'id_siswa' => $row['id_siswa'],
                'nama_siswa' => $row['nama_siswa'],
                'gender' => $row['gender'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'nominal_sisa' => (int)$row['nominal_sisa'],
                'role' => $row['role'] ?? 'siswa'
            ];
        }
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $siswa
        ]);

        mysqli_stmt_close($stmt);
        exit;
    }
    catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

?>