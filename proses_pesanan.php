<?php
include 'config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari form
    $nama_penerima = $conn->real_escape_string($_POST['nama_penerima']);
    $no_kontak = $conn->real_escape_string($_POST['no_kontak']);
    $metode_pengiriman = $conn->real_escape_string($_POST['metode_pengiriman']);
    $alamat_pengiriman = $conn->real_escape_string($_POST['alamat_pengiriman']);
    $metode_pembayaran = $conn->real_escape_string($_POST['metode_pembayaran']);
    $total_bayar = (float)$_POST['total_bayar'];
    
    // Decode data JSON keranjang
    $data_keranjang = json_decode($_POST['data_keranjang'], true);

    if (empty($data_keranjang)) {
        die("Keranjang kosong. Pesanan gagal diproses.");
    }

    // Buat ID Pesanan unik (contoh: SIGI-YYYYMMDD-His)
    $id_pesanan = "SIGI-" . date("Ymd-His");
    
    // Mulai transaksi database agar aman (jika detail gagal, pesanan utama dibatalkan)
    $conn->begin_transaction();

    try {
        // 1. Insert ke tabel pesanan (ID pelanggan bisa NULL jika guest checkout)
        $query_pesanan = "INSERT INTO pesanan (id_pesanan, nama_penerima, no_kontak, alamat_pengiriman, metode_pengiriman, metode_pembayaran, total_harga) 
                          VALUES ('$id_pesanan', '$nama_penerima', '$no_kontak', '$alamat_pengiriman', '$metode_pengiriman', '$metode_pembayaran', '$total_bayar')";
        $conn->query($query_pesanan);

        // 2. Insert ke tabel detail_pesanan & Kurangi Stok Ikan
        foreach ($data_keranjang as $item) {
            $id_ikan = (int)$item['id'];
            $jumlah = (int)$item['jumlah'];
            $harga = (float)$item['harga'];
            $subtotal = $harga * $jumlah;

            // Insert detail
            $query_detail = "INSERT INTO detail_pesanan (id_pesanan, id_ikan, jumlah, harga_saat_ini, subtotal) 
                             VALUES ('$id_pesanan', $id_ikan, $jumlah, $harga, $subtotal)";
            $conn->query($query_detail);

            // Kurangi stok ikan di tabel ikan
            $query_stok = "UPDATE ikan SET stok = stok - $jumlah WHERE id_ikan = $id_ikan";
            $conn->query($query_stok);
        }

        // Commit transaksi jika semua sukses
        $conn->commit();

        // Tampilkan halaman sukses
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <title>Pesanan Berhasil - SIGI</title>
            <link rel='stylesheet' href='assets/css/style.css'>
        </head>
        <body style='text-align:center; padding-top: 100px;'>
            <h1 style='color: var(--turquoise);'>Pesanan Berhasil Dibuat!</h1>
            <p>Nomor Pesanan Anda: <strong>$id_pesanan</strong></p>
            <p>Terima kasih telah berbelanja di Sistem Informasi Gudang Ikan (SIGI).</p>
            <a href='index.php' class='btn-primary' style='display:inline-block; margin-top:20px;'>Kembali ke Beranda</a>
        </body>
        </html>";

    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan (misal gagal insert detail)
        $conn->rollback();
        echo "Terjadi kesalahan saat memproses pesanan: " . $e->getMessage();
    }
} else {
    // Jika diakses langsung tanpa POST form
    header("Location: index.php");
}
?>