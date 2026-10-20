<?php 
include 'config/koneksi.php';

$id_ikan = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM ikan WHERE id_ikan = $id_ikan AND status='aktif'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "<script>alert('Ikan tidak ditemukan!'); window.location='katalog.php';</script>";
    exit;
}
$ikan = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail <?= $ikan['nama_ikan']; ?> - SIGI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .detail-container { display: flex; gap: 30px; margin-top: 20px; }
        .detail-img { flex: 1; max-width: 400px; border-radius: 8px; }
        .detail-info { flex: 2; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .info-table th, .info-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .info-table th { background-color: var(--light-blue); width: 30%; }
    </style>
</head>
<body>
    <header>
        <h2>SIGI</h2>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="katalog.php">Katalog Ikan</a>
            <a href="keranjang.php">Keranjang</a>
        </nav>
    </header>

    <div class="container">
        <a href="katalog.php" style="color: var(--primary-blue); text-decoration: none;">&larr; Kembali ke Katalog</a>
        <div class="detail-container">
            <img src="assets/img/<?= $ikan['foto']; ?>" alt="<?= $ikan['nama_ikan']; ?>" class="detail-img">
            <div class="detail-info">
                <h2><?= $ikan['nama_ikan']; ?> (<i><?= $ikan['nama_ilmiah']; ?></i>)</h2>
                <span class="badge"><?= $ikan['kategori']; ?></span>
                <h3 style="color: var(--turquoise); margin-top: 15px;">Rp <?= number_format($ikan['harga'], 0, ',', '.'); ?> / <?= $ikan['satuan']; ?></h3>
                
                <table class="info-table">
                    <tr><th>Stok</th><td><?= $ikan['stok']; ?> <?= $ikan['satuan']; ?></td></tr>
                    <tr><th>Asal / Habitat</th><td><?= $ikan['asal'] ?? '-'; ?> / <?= $ikan['habitat'] ?? '-'; ?></td></tr>
                    <tr><th>Penyimpanan</th><td><?= $ikan['penyimpanan'] ?? '-'; ?></td></tr>
                    <tr><th>Ciri Fisik</th><td><?= $ikan['ciri_fisik'] ?? 'Informasi belum tersedia'; ?></td></tr>
                    <tr><th>Kandungan Gizi</th><td><?= $ikan['gizi'] ?? 'Informasi belum tersedia'; ?></td></tr>
                </table>

                <br>
                <button class="btn-primary" onclick="tambahKeranjang(<?= $ikan['id_ikan']; ?>, '<?= $ikan['nama_ikan']; ?>', <?= $ikan['harga']; ?>)">Tambah ke Keranjang</button>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>