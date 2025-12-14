<div id="label-page"><h3>Edit Data Anggota</h3></div>

<div id="content">
    <?php
        $id_anggota = $_GET['id'];
        $q_tampil_anggota = mysqli_query($db, "SELECT * FROM tbanggota WHERE idanggota = '$id_anggota'");
        $r_tampil_anggota = mysqli_fetch_array($q_tampil_anggota);

        if(!$r_tampil_anggota){
            echo "<script>window.location='index.php?p=anggota';</script>";
            exit;
        }
    ?>

    <div class="chaos-form-container">
        <!-- FORM EDIT -->
        <form id="formEditAnggota" action="proses/anggota-edit-proses.php" method="post" enctype="multipart/form-data">
            
            <!-- !!! INI JUARA KUNCINYA !!! -->
            <!-- Input Rahasia ini menggantikan tugas tombol submit yang 'dimatikan' sama SweetAlert -->
            <input type="hidden" name="simpan" value="gas_update">

            <div class="form-group">
                <label>ID Anggota</label>
                <input type="text" name="id_anggota" value="<?php echo $r_tampil_anggota['idanggota']; ?>" readonly class="isian-formulir" style="background: #222; color: #888; cursor: not-allowed;">
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
                
                <div style="margin-bottom: 15px;">
                    <?php 
                    $foto_path = "images/" . $r_tampil_anggota['foto'];
                    if(!empty($r_tampil_anggota['foto']) && file_exists($foto_path) && $r_tampil_anggota['foto'] != 'avatar-default.png'){
                        echo "<img src='$foto_path' width='120px' style='border: 2px solid var(--gold-primary); border-radius: 10px;'>";
                    } else {
                        echo "<img src='images/avatar-default.png' width='100px' style='border: 2px solid #555; border-radius: 10px;'>";
                    }
                    ?>
                </div>

                <input type="file" name="foto" class="isian-formulir" accept="image/*">
                <small style="color: #aaa; font-style: italic;">*Biarkan kosong jika tidak ingin mengubah foto.</small>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <!-- Tombol ini cuma pemicu JS, data dikirim lewat hidden input di atas -->
                <button type="button" class="tombol" style="width: 100%; padding: 15px; font-weight: bold; font-size: 1.1em;"
                       onclick="konfirmasiSimpan(event, 'formEditAnggota')">
                       SIMPAN PERUBAHAN
                </button>
            </div>

        </form>
    </div>
</div>