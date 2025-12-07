<div id="label-page"><h3>Transaksi Peminjaman Buku</h3></div>

<div id="content">

    <!-- Container Tombol & Search (Layout Rapi) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        
        <!-- Tombol Tambah -->
        <div class="tombol-tambah-container" style="margin-bottom: 0;">
            <a href="index.php?p=transaksi-peminjaman-input" class="tombol">Tambah Peminjaman</a>
        </div>

        <!-- Form Pencarian -->
        <form action="" method="post" style="display: flex; gap: 5px;">
            <input type="text" name="pencarian" class="isian-formulir" placeholder="Cari ID / Nama..." style="margin: 0; padding: 10px; width: 250px;">
            <input type="submit" name="search" value="Search" class="tombol" style="margin: 0;">
        </form>

    </div>

    <!-- WRAPPER TABEL -->
    <div style="overflow-x: auto; width: 100%;">
        <table id="tabel-tampil" style="width: 100%; min-width: 100%;">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">ID Transaksi</th>
                    <th style="text-align: center;">Anggota</th>
                    <th style="text-align: center;">Buku</th>
                    <th style="text-align: center;">Tgl Pinjam</th>
                    <th style="text-align: center;">Jatuh Tempo</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Opsi</th>
                </tr>
            </thead>
            <tbody>

            <?php
            // QUERY UPGRADE (JOIN TABEL BIAR MUNCUL NAMA, BUKAN CUMA ID)
            $sql = "SELECT t.*, a.nama, b.judulbuku 
                    FROM tbtransaksi t
                    JOIN tbanggota a ON t.idanggota = a.idanggota
                    JOIN tbbuku b ON t.idbuku = b.idbuku";
            
            // Logika Pencarian
            if(isset($_POST['search']) && $_POST['pencarian'] != ''){
                $kunci = $_POST['pencarian'];
                $sql .= " WHERE t.idtransaksi LIKE '%$kunci%' OR a.nama LIKE '%$kunci%' OR b.judulbuku LIKE '%$kunci%'";
            }

            $sql .= " ORDER BY t.idtransaksi DESC";
            
            $query = mysqli_query($db, $sql);
            $nomor = 1;

            if(mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    
                    // Warna Status
                    $status_color = ($data['status_pengembalian'] == 'Dipinjam') ? '#ff4d4d' : '#00ff80'; // Merah Neon vs Hijau
            ?>
            <tr>
                <!-- RATA TENGAH SEMUA -->
                <td style="text-align: center;"><?= $nomor++; ?></td>
                <td style="text-align: center; font-weight: bold; color: var(--gold-primary);"><?= $data['idtransaksi']; ?></td>
                
                <td style="text-align: center;">
                    <?= $data['nama']; ?>
                    <div style="font-size: 0.8em; color: #888;">(<?= $data['idanggota']; ?>)</div>
                </td>
                
                <td style="text-align: center;">
                    <?= $data['judulbuku']; ?>
                    <div style="font-size: 0.8em; color: #888;">(<?= $data['idbuku']; ?>)</div>
                </td>
                
                <td style="text-align: center; color: #ccc;"><?= $data['tglpinjam']; ?></td>
                <td style="text-align: center; color: var(--gold-primary);"><?= $data['tglkembali']; ?></td>
                
                <td style="text-align: center; color: <?= $status_color; ?>; font-weight: bold; text-transform: uppercase; font-size: 0.85em;">
                    <?= $data['status_pengembalian']; ?>
                </td>

                <td style="text-align: center;">
                    <!-- INI TOMBOL HAPUS YANG SUDAH DIBENERIN VARIABELNYA -->
                    <a href="#" 
                       onclick="konfirmasiHapus(event, 'proses/transaksi-peminjaman-hapus.php?id=<?= $data['idtransaksi']; ?>', 'Transaksi <?= $data['idtransaksi']; ?>')"
                       class="tombol" 
                       style="background: linear-gradient(135deg, #500000, #300000); color: #ffcccc; padding: 8px 15px; font-size: 0.8em;">
                       HAPUS
                    </a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='8' style='text-align:center; padding:30px; font-style: italic; color: #ccc;'>Data tidak ditemukan.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>