<!-- FILE: pages/anggota-input.php -->
<!-- Isinya HANYA Tampilan Form -->

<div id="label-page"><h3>Input Data Anggota Chaos</h3></div>

<div id="content">
    <div class="chaos-form-container">
        <!-- Perhatikan action-nya mengarah ke folder proses -->
        <form action="proses/anggota-input-proses.php" method="post" enctype="multipart/form-data">
        
            <!-- ID Anggota -->
            <div class="form-group">
                <label>ID Anggota</label>
                <input type="text" name="id_anggota" class="isian-formulir" placeholder="Contoh: AG001" required>
            </div>

            <!-- Nama -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="isian-formulir" placeholder="Masukkan Nama Anggota" required>
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Pria" required> Pria
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Wanita"> Wanita
                    </label>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea rows="4" name="alamat" class="isian-formulir" placeholder="Masukkan Alamat..." required></textarea>
            </div>

            <!-- Foto -->
            <div class="form-group">
                <label>Foto Anggota</label>
                <input type="file" name="foto" class="isian-formulir" accept="image/*">
                <div style="font-size: 0.8em; color: #666; margin-top: 5px;">*Format: JPG/PNG. Kosongkan jika tidak ada foto.</div>
            </div>

            <!-- Tombol Simpan -->
            <div class="form-group" style="margin-top: 30px;">
                <input type="submit" name="simpan" value="SIMPAN DATA" class="tombol" style="width: 100%; padding: 15px; font-size: 1.1em;">
            </div>

        </form>
    </div>
</div>