<div id="label-page"><h3>Edit Pengaturan Denda</h3></div>

<div id="content">
    <?php
        $sql = "SELECT * FROM tbdenda WHERE id_setting = 1";
        $query = mysqli_query($db, $sql);
        $r = mysqli_fetch_array($query);
    ?>

    <!-- FORM GLASS ESTETIK -->
    <div class="chaos-form-container" style="max-width: 600px;">
        <form action="index.php?p=denda-edit" method="post">
            
            <div class="form-group">
                <label>Nominal Denda Per Hari (Rp)</label>
                <input type="number" name="denda_per_hari" value="<?php echo $r['denda_per_hari']; ?>" class="isian-formulir">
            </div>

            <div class="form-group">
                <label>Maksimal Lama Pinjam (Hari)</label>
                <input type="number" name="maks_hari_pinjam" value="<?php echo $r['maks_hari_pinjam']; ?>" class="isian-formulir">
            </div>

            <div class="form-group">
                <label>Maksimal Denda (Capping) (Rp)</label>
                <input type="number" name="maks_denda" value="<?php echo $r['maks_denda']; ?>" class="isian-formulir">
                <small style="color: #888; font-style: italic;">*Denda tidak akan melebihi angka ini walaupun telat bertahun-tahun.</small>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <input type="submit" name="simpan" value="SIMPAN PERUBAHAN" class="tombol" style="width: 100%; padding: 15px; font-weight: bold; font-size: 1.1em;">
            </div>

        </form>
    </div>
</div>

<?php
// PROSES UPDATE (Langsung di file ini biar simpel)
// ... kode form di atas tetap ...

// PROSES UPDATE DENGAN SWEETALERT
if(isset($_POST['simpan'])){
    $denda_per_hari = $_POST['denda_per_hari'];
    $maks_hari_pinjam = $_POST['maks_hari_pinjam'];
    $maks_denda = $_POST['maks_denda'];

    $sql_update = "UPDATE tbdenda SET 
                   denda_per_hari='$denda_per_hari', 
                   maks_hari_pinjam='$maks_hari_pinjam', 
                   maks_denda='$maks_denda' 
                   WHERE id_setting=1";
    
    $query_update = mysqli_query($db, $sql_update);

    if($query_update){
        echo "<script>
            Swal.fire({
                title: 'BERHASIL!',
                text: 'Aturan Denda Chaos Library Telah Diperbarui.',
                icon: 'success',
                background: '#121212',
                color: '#fff',
                confirmButtonColor: '#ffda47',
                confirmButtonText: 'Sip, Lanjut!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php?p=denda';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'GAGAL!',
                text: 'Sistem menolak perubahan.',
                icon: 'error'
            });
        </script>";
    }
}
?>