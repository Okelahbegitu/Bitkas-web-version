<?php
    function countSaldo($conn) {
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END), 0) AS total_pemasukan,
                    COALESCE(SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END), 0) AS total_pengeluaran
                FROM tb_transaksi";
        $result = mysqli_query($conn, $query);
        $saldo = 0;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $saldo = $row['total_pemasukan'] - $row['total_pengeluaran'];
        }
        return $saldo;
    }
?>