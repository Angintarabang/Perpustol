<div id="label-page"><h3>Input Transaksi Pengembalian</h3></div>
<div id="content">
    <div class="chaos-form-container">
        <form action="proses/transaksi-pengembalian-input-proses.php" method="post">
            <div class="form-group">
                <label>Pilih Transaksi (Buku Dipinjam)</label>
                <select name="id_transaksi" class="isian-formulir" required>
                    <option value="" disabled selected>-- Pilih Transaksi --</option>
                    <?php
                    $q = mysqli_query($db, "SELECT * FROM tbtransaksi WHERE status_pengembalian='Dipinjam'");
                    while($r = mysqli_fetch_array($q)){
                        echo "<option value='$r[idtransaksi]'>$r[idtransaksi] (Anggota: $r[idanggota])</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" style="margin-top: 30px;">
                <input type="submit" name="simpan" value="PROSES PENGEMBALIAN" class="tombol" style="width: 100%;">
            </div>
        </form>
    </div>
</div>