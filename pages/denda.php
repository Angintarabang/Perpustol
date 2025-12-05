<div id="label-page"><h3>Manajemen Denda & Sanksi</h3></div>

<div id="content">

    <?php
        // Ambil data denda dari database
        $sql = "SELECT * FROM tbdenda WHERE id_setting = 1"; // Pastikan ID setting lu bener
        $query = mysqli_query($db, $sql);
        $data = mysqli_fetch_array($query);

        // Nilai Default kalau database kosong
        $denda  = isset($data['denda_per_hari']) ? $data['denda_per_hari'] : 0;
        $max_hari = isset($data['maks_hari_pinjam']) ? $data['maks_hari_pinjam'] : 0;
        $max_denda = isset($data['maks_denda']) ? $data['maks_denda'] : 0;
    ?>

    <!-- BAGIAN ATAS: INTRO -->
    <div style="text-align: center; margin-bottom: 40px; color: #ccc;">
        <p>Aturan ini berlaku mutlak untuk seluruh anggota Perpustakaan Chaos Yohanes.</p>
    </div>

    <!-- BAGIAN TENGAH: GRID KARTU SAKTI -->
    <div class="denda-grid">
        
        <!-- KARTU 1: DENDA HARIAN -->
        <div class="denda-card">
            <div class="denda-icon">💸</div>
            <div class="denda-label">Denda Keterlambatan</div>
            <div class="denda-value">
                <span>Rp</span> <?php echo number_format($denda, 0, ',', '.'); ?>
                <div style="font-size: 0.4em; color: #666; font-family: 'Poppins'; margin-top: 5px;">Per Hari</div>
            </div>
        </div>

        <!-- KARTU 2: BATAS WAKTU -->
        <div class="denda-card">
            <div class="denda-icon">⏳</div>
            <div class="denda-label">Batas Peminjaman</div>
            <div class="denda-value">
                <?php echo $max_hari; ?> <span>Hari</span>
                <div style="font-size: 0.4em; color: #666; font-family: 'Poppins'; margin-top: 5px;">Durasi Maksimal</div>
            </div>
        </div>

        <!-- KARTU 3: MAKSIMAL DENDA -->
        <div class="denda-card">
            <div class="denda-icon">⛔</div>
            <div class="denda-label">Maksimal Denda</div>
            <div class="denda-value">
                <span>Rp</span> <?php echo number_format($max_denda, 0, ',', '.'); ?>
                <div style="font-size: 0.4em; color: #666; font-family: 'Poppins'; margin-top: 5px;">Capping (Batas Atas)</div>
            </div>
        </div>

    </div>

    <!-- BAGIAN BAWAH: TOMBOL EDIT -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php?p=denda-edit" class="tombol" style="padding: 15px 40px; font-size: 1.2em; border-radius: 50px; box-shadow: 0 0 20px rgba(255, 218, 71, 0.3);">
            UBAH PENGATURAN
        </a>
    </div>

</div>