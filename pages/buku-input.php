<div id="label-page"><h3>Input Data Buku</h3></div>

<div id="content">
    <div class="chaos-form-container">
        
        <!-- Form Input Buku -->
        <form action="proses/buku-input-proses.php" method="post">
        
            <div class="form-group">
                <label>ID Buku</label>
                <input type="text" name="id_buku" class="isian-formulir" placeholder="Contoh: BK001" required>
            </div>

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" class="isian-formulir" placeholder="Masukkan Judul Buku" required>
            </div>

            <!-- INI YANG GW BENERIN: JADI DROPDOWN -->
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="isian-formulir" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Ilmu Komputer">Ilmu Komputer</option>
                    <option value="Ilmu Agama">Ilmu Agama</option>
                    <option value="Karya Sastra">Karya Sastra</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" class="isian-formulir" placeholder="Nama Pengarang" required>
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" class="isian-formulir" placeholder="Nama Penerbit" required>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <input type="submit" name="simpan" value="SIMPAN DATA" class="tombol" style="width: 100%; padding: 15px; font-weight: bold;">
            </div>

        </form>
    </div>
</div>