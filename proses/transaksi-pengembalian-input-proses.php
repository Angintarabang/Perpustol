<?php
include "../koneksi.php";

if(isset($_POST['simpan'])){
    $tgl_kembali_nyata = date('Y-m-d'); // Hari ini
    $id_transaksi = $_POST['id_transaksi']; 

    // 1. Ambil Data Transaksi
    $sql_cek = "SELECT * FROM tbtransaksi WHERE idtransaksi = '$id_transaksi'";
    $q_cek = mysqli_query($db, $sql_cek);
    $data_transaksi = mysqli_fetch_array($q_cek);

    if(!$data_transaksi){
        echo "<script>alert('Data Transaksi Tidak Ditemukan!'); window.history.back();</script>";
        exit;
    }

    $tgl_rencana = $data_transaksi['tglkembali']; 
    $id_anggota = $data_transaksi['idanggota'];
    $id_buku = $data_transaksi['idbuku'];

    // 2. Ambil Aturan Denda (Limit & Tarif)
    $q_denda = mysqli_query($db, "SELECT * FROM tbdenda LIMIT 1");
    $denda_rules = mysqli_fetch_array($q_denda);
    $tarif_per_hari = $denda_rules['denda_per_hari'];
    $limit_maksimal = $denda_rules['maks_denda']; // Ini yang 100 Juta

    // 3. Hitung Denda
    $denda_total = 0;
    $time_rencana = strtotime($tgl_rencana);
    $time_nyata = strtotime($tgl_kembali_nyata);

    if($time_nyata > $time_rencana){
        $selisih = $time_nyata - $time_rencana;
        $hari_telat = floor($selisih / (60 * 60 * 24));
        $denda_total = $hari_telat * $tarif_per_hari;
    }

    // --- LOGIKA CAPPING (BATAS ATAS) ---
    // Kalau denda hitungan > 100 Juta, Paksa jadi 100 Juta.
    if($denda_total > $limit_maksimal) {
        $denda_total = $limit_maksimal;
    }

    // 4. Simpan ke Database
    // PERHATIKAN: Kita GAK masukin 'idpengembalian' di sini, biar MySQL yang generate nomor otomatis (1, 2, 3...)
    $sql_input = "INSERT INTO tbpengembalian (idtransaksi, idanggota, idbuku, tglkembali, denda) 
                  VALUES ('$id_transaksi', '$id_anggota', '$id_buku', '$tgl_kembali_nyata', '$denda_total')";
    
    $query_input = mysqli_query($db, $sql_input);

    if($query_input){
        // Update status tabel lain
        mysqli_query($db, "UPDATE tbtransaksi SET status_pengembalian='Sudah Kembali', denda='$denda_total' WHERE idtransaksi='$id_transaksi'");
        mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$id_buku'");
        mysqli_query($db, "UPDATE tbanggota SET status='Tidak Meminjam' WHERE idanggota='$id_anggota'");

        header("Location: ../index.php?p=transaksi-pengembalian");
    } else {
        echo "Gagal menyimpan! Error: " . mysqli_error($db);
    }
}
?>