<div id="label-page"><h3>Edit Data Anggota</h3></div>

<div id="content">
    <?php
        $id_anggota = $_GET['id'];
        $q_tampil_anggota = mysqli_query($db, "SELECT * FROM tbanggota WHERE idanggota = '$id_anggota'");
        $r_tampil_anggota = mysqli_fetch_array($q_tampil_anggota);
    ?>

    <div class="chaos-form-container">
        <!-- KASIH ID KE FORM: id="formEditAnggota" -->
        <form id="formEditAnggota" action="proses/anggota-edit-proses.php" method="post" enctype="multipart/form-data">
        
            <div class="form-group">
                <label>ID Anggota</label>
                <input type="text" name="id_anggota" value="<?php echo $r_tampil_anggota['idanggota']; ?>" readonly class="isian-formulir" style="background: #222; color: #888;">
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo $r_tampil_anggota['nama']; ?>" class="isian-formulir" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Pria" <?php if($r_tampil_anggota['jeniskelamin'] == 'Pria') echo 'checked'; ?>> Pria
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Wanita" <?php if($r_tampil_anggota['jeniskelamin'] == 'Wanita') echo 'checked'; ?>> Wanita
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea rows="4" name="alamat" class="isian-formulir" required><?php echo $r_tampil_anggota['alamat']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Foto Anggota</label>
                <?php if(!empty($r_tampil_anggota['foto'])){ ?>
                    <img src="images/<?php echo $r_tampil_anggota['foto']; ?>" width="80px" style="border: 1px solid var(--gold-primary); margin-bottom: 10px; border-radius: 5px;">
                <?php } ?>
                <input type="file" name="foto" class="isian-formulir">
                <small style="color: #888;">*Biarkan kosong jika tidak ingin mengganti foto.</small>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <!-- PASANG ONCLICK DISINI -->
                <input type="submit" name="simpan" value="SIMPAN PERUBAHAN" 
                       class="tombol" style="width: 100%; padding: 15px; font-weight: bold;"
                       onclick="konfirmasiSimpan(event, 'formEditAnggota')">
            </div>

        </form>
    </div>
</div>