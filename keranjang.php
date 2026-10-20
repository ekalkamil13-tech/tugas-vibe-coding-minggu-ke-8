<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Pesanan - SIGI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background: var(--primary-blue); color: white; }
        .checkout-form { margin-top: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
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
        <h2>Keranjang Anda</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Ikan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="isi-keranjang">
                <!-- Diisi oleh JavaScript -->
            </tbody>
        </table>
        <h3 style="text-align: right; margin-top: 15px;">Total: Rp <span id="total-harga">0</span></h3>

        <div class="checkout-form">
            <h3>Formulir Pemesanan</h3>
            <form action="proses_pesanan.php" method="POST" id="form-checkout">
                <!-- Input hidden untuk mengirim data array JSON dari localStorage ke PHP -->
                <input type="hidden" name="data_keranjang" id="data_keranjang">
                <input type="hidden" name="total_bayar" id="total_bayar">

                <div class="form-group">
                    <label>Nama Penerima</label>
                    <input type="text" name="nama_penerima" required>
                </div>
                <div class="form-group">
                    <label>Nomor Kontak (WA/HP)</label>
                    <input type="text" name="no_kontak" required>
                </div>
                <div class="form-group">
                    <label>Metode Pengiriman</label>
                    <select name="metode_pengiriman" required>
                        <option value="ambil">Ambil di Gudang</option>
                        <option value="kirim">Kirim ke Alamat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alamat Pengiriman (Jika dikirim)</label>
                    <textarea name="alamat_pengiriman" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" required>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="COD">Bayar di Tempat (COD)</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Proses Pesanan</button>
            </form>
        </div>
    </div>

    <script>
        let keranjang = JSON.parse(localStorage.getItem('sigi_keranjang')) || [];
        let tbody = document.getElementById('isi-keranjang');
        let totalHargaEl = document.getElementById('total-harga');
        let dataKeranjangInput = document.getElementById('data_keranjang');
        let totalBayarInput = document.getElementById('total_bayar');

        function renderKeranjang() {
            tbody.innerHTML = '';
            let total = 0;
            
            if(keranjang.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5">Keranjang masih kosong.</td></tr>';
                document.getElementById('form-checkout').style.display = 'none';
                return;
            }

            keranjang.forEach((item, index) => {
                let subtotal = item.harga * item.jumlah;
                total += subtotal;
                tbody.innerHTML += `
                    <tr>
                        <td>${item.nama}</td>
                        <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                        <td>${item.jumlah}</td>
                        <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                        <td><button onclick="hapusItem(${index})" style="color:red; cursor:pointer;">Hapus</button></td>
                    </tr>
                `;
            });

            totalHargaEl.innerText = total.toLocaleString('id-ID');
            dataKeranjangInput.value = JSON.stringify(keranjang);
            totalBayarInput.value = total;
        }

        function hapusItem(index) {
            keranjang.splice(index, 1);
            localStorage.setItem('sigi_keranjang', JSON.stringify(keranjang));
            renderKeranjang();
        }

        // Jalankan saat halaman dimuat
        renderKeranjang();

        // Kosongkan localStorage setelah submit form
        document.getElementById('form-checkout').addEventListener('submit', function() {
            localStorage.removeItem('sigi_keranjang');
        });
    </script>
</body>
</html>