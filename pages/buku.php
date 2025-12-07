<div id="label-page"><h3>Tampil Data Buku</h3></div>

<div id="content">

    <!-- Container Tombol & Pencarian (Layout Flexbox Rapi) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        
        <!-- Tombol Tambah & Cetak (Kiri) -->
        <div class="tombol-tambah-container" style="margin-bottom: 0;">
            <a href="index.php?p=buku-input" class="tombol">Tambah Buku</a>
            
            <!-- Tombol Print Icon -->
            <a target="_blank" href="pages/buku-cetak.php"> <!-- Sesuaikan nama file cetak lu -->
                <img src="print.png" alt="Cetak" style="height: 40px; width: auto; vertical-align: middle; margin-left: 5px;">
            </a>
        </div>

        <!-- Form Pencarian (Kanan) -->
        <form action="" method="post" style="display: flex; gap: 5px;">
            <input type="text" name="pencarian" class="isian-formulir" placeholder="Judul / Pengarang..." style="margin: 0; padding: 10px; width: 250px;">
            <input type="submit" name="search" value="Search" class="tombol" style="margin: 0;">
        </form>

    </div>

    <!-- TABEL DATA BUKU -->
    <div style="overflow-x: auto; width: 100%;">
        <table id="tabel-tampil">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Logika Pencarian & Pagination
                $batas = 5;
                $hal = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
                $posisi = ($hal - 1) * $batas;

                $sql = "SELECT * FROM tbbuku";
                if(isset($_POST['search']) && $_POST['pencarian'] != ''){
                    $kunci = $_POST['pencarian'];
                    $sql .= " WHERE judulbuku LIKE '%$kunci%' OR pengarang LIKE '%$kunci%' OR penerbit LIKE '%$kunci%'";
                    $posisi = 0;
                }
                
                // Urutkan ID Descending (Buku baru di atas)
                $sql .= " ORDER BY idbuku DESC LIMIT $posisi, $batas";
                
                $q_tampil_buku = mysqli_query($db, $sql);
                $nomor = $posisi + 1;

                if(mysqli_num_rows($q_tampil_buku) > 0){
                    while($r_tampil_buku = mysqli_fetch_array($q_tampil_buku)){
                        
                        // Warna Status Neon
                        $status_color = ($r_tampil_buku['status'] == 'Tersedia') ? '#00ff80' : '#ff4d4d';
                ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $r_tampil_buku['idbuku']; ?></td>
                    <td><?php echo $r_tampil_buku['judulbuku']; ?></td>
                    <td><?php echo $r_tampil_buku['kategori']; ?></td>
                    <td><?php echo $r_tampil_buku['pengarang']; ?></td>
                    <td><?php echo $r_tampil_buku['penerbit']; ?></td>
                    <td style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $r_tampil_buku['status']; ?></td>
                    
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <a href="index.php?p=buku-edit&id=<?php echo $r_tampil_buku['idbuku']; ?>" class="tombol" style="background: #333; border: 1px solid #555; padding: 8px 12px; font-size: 0.85em;">Edit</a>
                            
                            <!-- INI PERBAIKAN LINK HAPUS BIAR GAK NYASAR KE DASHBOARD -->
                            <a href="proses/buku-hapus.php?id=<?php echo $r_tampil_buku['idbuku']; ?>" 
                               onclick="return confirm('Hapus buku <?php echo $r_tampil_buku['judulbuku']; ?>?')" 
                               class="tombol" 
                               style="background: linear-gradient(135deg, #500000, #300000); color: #ffcccc; padding: 8px 12px; font-size: 0.85em;">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center; padding: 20px; font-style:italic;'>Data buku tidak ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; text-align: center;">
        <?php
        $jml_data_query = "SELECT COUNT(*) as jml FROM tbbuku";
        if(isset($_POST['search']) && $_POST['pencarian'] != ''){
            $kunci = $_POST['pencarian'];
            $jml_data_query .= " WHERE judulbuku LIKE '%$kunci%' OR pengarang LIKE '%$kunci%'";
        }
        $jml_data = mysqli_fetch_array(mysqli_query($db, $jml_data_query))['jml'];
        $jml_hal = ceil($jml_data / $batas);

        for($i = 1; $i <= $jml_hal; $i++){
            $active_style = ($i == $hal) ? "background-color: var(--gold-primary); color: black; font-weight:bold;" : "background-color: #333;";
            if($i != $hal){
                echo "<a href='index.php?p=buku&hal=$i' class='tombol' style='$active_style margin: 0 2px;'>$i</a>";
            } else {
                echo "<span class='tombol' style='$active_style margin: 0 2px; cursor: default;'>$i</span>";
            }
        }
        ?>
    </div>

</div>