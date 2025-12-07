<div id="label-page"><h3>Input Transaksi Peminjaman</h3></div>
<div id="content">
    <div class="chaos-form-container">
        <form action="proses/transaksi-peminjaman-input-proses.php" method="post">
            
            <div class="form-group">
                <label>ID Transaksi</label>
                <input type="text" name="id_transaksi" class="isian-formulir" required>
            </div>
            
            <div class="form-group">
                <label>Anggota</label>
                <select name="id_anggota" class="isian-formulir">
                    <option value="" disabled selected>-- Pilih Anggota --</option>
                    <?php
                    $q = mysqli_query($db, "SELECT * FROM tbanggota ORDER BY idanggota");
                    while($r = mysqli_fetch_array($q)){
                        echo "<option value='$r[idanggota]'>$r[idanggota] - $r[nama]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Buku</label>
                <select name="id_buku" class="isian-formulir">
                    <option value="" disabled selected>-- Pilih Buku --</option>
                    <?php
                    $q = mysqli_query($db, "SELECT * FROM tbbuku WHERE status='Tersedia' ORDER BY idbuku");
                    while($r = mysqli_fetch_array($q)){
                        echo "<option value='$r[idbuku]'>$r[idbuku] - $r[judulbuku]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="tgl_pinjam" class="isian-formulir" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="tgl_kembali" class="isian-formulir">
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <input type="submit" name="simpan" value="SIMPAN" class="tombol" style="width: 100%;">
            </div>
        </form>
    </div>
</div>