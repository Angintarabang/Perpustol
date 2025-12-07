<div id="label-page"><h3>Edit Data Buku</h3></div>

<div id="content">
    <?php
        $id_buku = $_GET['id'];
        $q_tampil_buku = mysqli_query($db, "SELECT * FROM tbbuku WHERE idbuku = '$id_buku'");
        $r_tampil_buku = mysqli_fetch_array($q_tampil_buku);
    ?>

    <div class="chaos-form-container">
        <!-- KASIH ID KE FORM: id="formEditBuku" -->
        <form id="formEditBuku" action="proses/buku-edit-proses.php" method="post">
        
            <div class="form-group">
                <label>ID Buku</label>
                <input type="text" name="id_buku" value="<?php echo $r_tampil_buku['idbuku']; ?>" readonly class="isian-formulir" style="background: #222; color: #888;">
            </div>

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" value="<?php echo $r_tampil_buku['judulbuku']; ?>" class="isian-formulir" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="isian-formulir">
                    <option value="Ilmu Komputer" <?php if($r_tampil_buku['kategori'] == 'Ilmu Komputer') echo 'selected'; ?>>Ilmu Komputer</option>
                    <option value="Ilmu Agama" <?php if($r_tampil_buku['kategori'] == 'Ilmu Agama') echo 'selected'; ?>>Ilmu Agama</option>
                    <option value="Karya Sastra" <?php if($r_tampil_buku['kategori'] == 'Karya Sastra') echo 'selected'; ?>>Karya Sastra</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" value="<?php echo $r_tampil_buku['pengarang']; ?>" class="isian-formulir" required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" value="<?php echo $r_tampil_buku['penerbit']; ?>" class="isian-formulir" required>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <!-- PASANG ONCLICK DISINI -->
                <input type="submit" name="simpan" value="SIMPAN PERUBAHAN" 
                       class="tombol" style="width: 100%; padding: 15px; font-weight: bold;"
                       onclick="konfirmasiSimpan(event, 'formEditBuku')">
            </div>

        </form>
    </div>
</div>