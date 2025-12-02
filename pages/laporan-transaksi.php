<?php
include "koneksi.php";

// Fungsi untuk debugging MySQL
function debug_query($db, $query, $query_name = "Query") {
    if (!$query) {
        echo "<div style='background: #330000; border: 1px solid red; padding: 10px; color: #ffcccc; margin-bottom: 10px;'>";
        echo "<p style='font-weight: bold;'>!!! SQL ERROR ($query_name) !!!</p>";
        echo "<p>MySQL Error: " . mysqli_error($db) . "</p>";
        echo "</div>";
        return false;
    }
    return true;
}

// Ambil aturan denda
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
if (!$set || mysqli_num_rows($set) == 0) {
    $d = ['denda_per_hari' => 5000, 'maks_denda' => 50000, 'maks_hari_pinjam' => 7];
} else {
    $d = mysqli_fetch_array($set);
}

// CRITICAL FIX: HITUNG DENDA OTOMATIS
$hitung_denda_query = "
    UPDATE tbtransaksi 
    SET denda = 
        CASE 
            WHEN CURDATE() > tglkembali AND status_pengembalian = 'Dipinjam' THEN
                LEAST(
                    DATEDIFF(CURDATE(), tglkembali) * {$d['denda_per_hari']}, 
                    {$d['maks_denda']}
                )
            ELSE 0
        END
    WHERE status_pengembalian = 'Dipinjam'
";
mysqli_query($db, $hitung_denda_query);
?>

<div id="label-page"><h3>Laporan Transaksi (Chaos Log)</h3></div>

<div id="content">

    <!-- Form Filter: GLASS STYLE -->
    <div style="margin-bottom: 30px; padding: 25px; border: 1px solid #333; background: rgba(30, 30, 30, 0.6); border-radius: 12px; backdrop-filter: blur(5px); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h3 style="color: var(--gold-primary); margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px; letter-spacing: 1px;">
            FILTER DATA
        </h3>
        
        <form method="get">
            <input type="hidden" name="p" value="laporan-transaksi">
            <table id="tabel-input" style="width: 100%;">
                <tr>
                    <td class="label-formulir" style="width: 180px;">Status Transaksi</td>
                    <td class="isian-formulir-container">
                        <select name="status" class="isian-formulir">
                            <option value="">-- Tampilkan Semua Status --</option>
                            <option value="Dipinjam" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Dipinjam') ? 'selected' : ''; ?>>Belum Kembali (Dipinjam)</option>
                            <option value="Sudah Kembali" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Sudah Kembali') ? 'selected' : ''; ?>>Riwayat (Sudah Kembali)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label-formulir">Tanggal Pinjam (Mulai)</td>
                    <td>
                        <input type="date" name="tgl_mulai" value="<?php echo isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : ''; ?>" class="isian-formulir">
                    </td>
                </tr>
                <tr>
                    <td class="label-formulir">Tanggal Pinjam (Sampai)</td>
                    <td>
                        <input type="date" name="tgl_sampai" value="<?php echo isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : ''; ?>" class="isian-formulir">
                    </td>
                </tr>
                <tr>
                    <td class="label-formulir">Status Denda</td>
                    <td>
                        <select name="ada_denda" class="isian-formulir">
                            <option value="">-- Semua Kondisi --</option>
                            <option value="ya" <?php echo (isset($_GET['ada_denda']) && $_GET['ada_denda'] == 'ya') ? 'selected' : ''; ?>>Terkena Denda</option>
                            <option value="tidak" <?php echo (isset($_GET['ada_denda']) && $_GET['ada_denda'] == 'tidak') ? 'selected' : ''; ?>>Bebas Denda</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="padding-top: 15px;">
                        <input type="submit" value="Terapkan Filter" class="tombol">
                        <a href="index.php?p=laporan-transaksi" class="tombol" style="background: #333; border: 1px solid #555;">Reset</a>
                        <a href="cetak/cetak-laporan-transaksi.php?<?php echo http_build_query($_GET); ?>" target="_blank" class="tombol" style="background: linear-gradient(135deg, #eee, #ccc); color: #000;">Cetak Laporan</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- Logika Query Filter -->
    <?php
    $where = "1=1";
    $filter_params = $_GET; 

    if(isset($filter_params['status']) && $filter_params['status'] != '') {
        $where .= " AND t.status_pengembalian = '".mysqli_real_escape_string($db, $filter_params['status'])."'";
    }
    if(isset($filter_params['tgl_mulai']) && $filter_params['tgl_mulai'] != '') {
        $where .= " AND t.tglpinjam >= '".mysqli_real_escape_string($db, $filter_params['tgl_mulai'])."'";
    }
    if(isset($filter_params['tgl_sampai']) && $filter_params['tgl_sampai'] != '') {
        $where .= " AND t.tglpinjam <= '".mysqli_real_escape_string($db, $filter_params['tgl_sampai'])."'";
    }
    if(isset($filter_params['ada_denda']) && $filter_params['ada_denda'] != '') {
        if($filter_params['ada_denda'] == 'ya') {
            $where .= " AND t.denda > 0";
        } else {
            $where .= " AND t.denda = 0";
        }
    }

    // Query Utama
    $qry = mysqli_query($db, "
        SELECT 
            t.*, 
            a.nama as nama_anggota, 
            b.judulbuku,
            p.tglkembali as tgl_dikembalikan_aktual 
        FROM tbtransaksi t 
        JOIN tbanggota a ON t.idanggota = a.idanggota 
        JOIN tbbuku b ON t.idbuku = b.idbuku 
        LEFT JOIN tbpengembalian p ON t.idtransaksi = p.idtransaksi
        WHERE $where
        ORDER BY t.tglpinjam DESC
    ");

    if (!debug_query($db, $qry, "Laporan Transaksi Umum")) { $qry = false; }
    ?>

    <!-- TABEL HASIL -->
    <div style="overflow-x: auto;">
        <table id="tabel-tampil">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Telat</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $nomor = 1;
            $total_denda_laporan = 0;

            if($qry && mysqli_num_rows($qry) > 0) {
                while($data = mysqli_fetch_array($qry)) {
                    
                    $batas_waktu = $data['tglkembali']; 
                    $denda = $data['denda']; 
                    $tgl_kembali_aktual = $data['tgl_dikembalikan_aktual']; 
                    $tgl_pinjam = $data['tglpinjam'];
                    
                    $telat_display = '-';
                    $tgl_display = ($tgl_kembali_aktual != null && $data['status_pengembalian'] == 'Sudah Kembali') ? $tgl_kembali_aktual : '-'; 
                    
                    if ($data['status_pengembalian'] == 'Dipinjam') {
                        if (strtotime($batas_waktu) < strtotime(date('Y-m-d'))) {
                            $date_diff_obj = date_diff(date_create($batas_waktu), date_create(date('Y-m-d')));
                            $telat_display = "<span style='color:#ff4d4d'>" . $date_diff_obj->days . " Hari</span>";
                        }
                    } elseif ($data['status_pengembalian'] == 'Sudah Kembali' && $tgl_kembali_aktual) {
                        if (strtotime($batas_waktu) < strtotime($tgl_kembali_aktual)) {
                            $date_diff_obj = date_diff(date_create($batas_waktu), date_create($tgl_kembali_aktual));
                            $telat_display = $date_diff_obj->days . " Hari";
                        }
                    }
                    
                    // Warna Status Neon biar kebaca di background hitam
                    $warna_status = ($data['status_pengembalian'] == 'Dipinjam') ? '#ff4d4d' : '#00ff80'; // Neon Red vs Neon Green
                    $warna_denda = ($denda > 0) ? '#ff4d4d' : '#cccccc';
                    
                    $total_denda_laporan += $denda;
                ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo htmlspecialchars($data['idtransaksi']); ?></td>
                    <td><?php echo htmlspecialchars($data['nama_anggota']); ?></td>
                    <td><?php echo htmlspecialchars($data['judulbuku']); ?></td>
                    <td><?php echo htmlspecialchars($tgl_pinjam); ?></td>
                    <td><?php echo htmlspecialchars($batas_waktu); ?></td>
                    <td><?php echo $tgl_display; ?></td>
                    <td style="color: <?php echo $warna_status; ?>; font-weight: bold; text-transform:uppercase; font-size:0.9em; letter-spacing:1px;"><?php echo htmlspecialchars($data['status_pengembalian']); ?></td>
                    <td><?php echo $telat_display; ?></td>
                    <td style="color: <?php echo $warna_denda; ?>; font-weight: bold;">Rp <?php echo number_format($denda, 0, ',', '.'); ?></td>
                </tr>
                <?php
                }
            } else {
                echo "<tr><td colspan='10' style='text-align:center; padding:30px; font-style:italic; color:#888;'>Data tidak ditemukan dalam arsip Chaos.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Statistik Box: GLASS STYLE -->
    <div style="margin-top: 30px; padding: 25px; border: 1px solid #333; background: rgba(30, 30, 30, 0.6); border-radius: 12px; backdrop-filter: blur(5px); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
        <?php
        $total_transaksi = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE $where"))['total'];
        $belum_kembali = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
        $denda_aktif = mysqli_fetch_array(mysqli_query($db, "SELECT COALESCE(SUM(denda), 0) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
        ?>
        
        <div style="flex: 1; min-width: 200px;">
            <h3 style="color: var(--gold-primary); margin-bottom: 10px;">RINGKASAN</h3>
            <div style="color: #ccc; line-height: 1.8;">
                Total Transaksi: <strong style="color: #fff;"><?php echo $total_transaksi; ?></strong><br>
                Belum Dikembalikan: <strong style="color: #ff4d4d;"><?php echo $belum_kembali; ?></strong>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px; text-align: right; border-left: 2px solid var(--gold-primary); padding-left: 20px;">
            <div style="font-size: 0.9em; color: #888; text-transform: uppercase; letter-spacing: 1px;">Total Denda (Tampil)</div>
            <div style="font-size: 1.8em; color: var(--gold-primary); font-family: 'Cinzel', serif;">Rp <?php echo number_format($total_denda_laporan, 0, ',', '.'); ?></div>
            
            <div style="font-size: 0.8em; color: #ff4d4d; margin-top: 5px;">
                Denda Aktif (Belum Lunas): Rp <?php echo number_format($denda_aktif, 0, ',', '.'); ?>
            </div>
        </div>
    </div>

</div>