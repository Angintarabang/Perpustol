<div id="label-page"><h3>Tampil Data Anggota</h3></div>

<div id="content">

    <!-- Container Tombol & Pencarian -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        
        <!-- Tombol Tambah & Cetak (Kiri) -->
        <div class="tombol-tambah-container" style="margin-bottom: 0;">
            <a href="index.php?p=anggota-input" class="tombol">Tambah Anggota</a>
            
            <!-- Tombol Print Icon -->
            <a target="_blank" href="pages/cetak.php">
                <img src="print.png" alt="Cetak" style="height: 40px; width: auto; vertical-align: middle; margin-left: 5px;">
            </a>
        </div>

        <!-- Form Pencarian (Kanan) -->
        <form action="" method="post" style="display: flex; gap: 5px;">
            <input type="text" name="pencarian" class="isian-formulir" placeholder="Cari ID atau Nama..." style="margin: 0; padding: 10px; width: 250px;">
            <input type="submit" name="search" value="Search" class="tombol" style="margin: 0;">
        </form>

    </div>

    <!-- TABEL DATA ANGGOTA (NO HP SUDAH DIHAPUS) -->
    <div style="overflow-x: auto; width: 100%;">
        <table id="tabel-tampil">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Anggota</th>
                    <th>Nama</th>
                    <th>Foto</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <!-- Kolom No HP DIHAPUS -->
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Logika Pencarian
                $batas = 5;
                $hal = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
                $posisi = ($hal - 1) * $batas;

                $sql = "SELECT * FROM tbanggota";
                if(isset($_POST['search']) && $_POST['pencarian'] != ''){
                    $kunci = $_POST['pencarian'];
                    $sql .= " WHERE nama LIKE '%$kunci%' OR idanggota LIKE '%$kunci%'";
                    $posisi = 0; // Reset ke halaman 1 kalo nyari
                }
                
                // Order by ID descending biar data baru di atas
                $sql .= " ORDER BY idanggota DESC LIMIT $posisi, $batas";
                
                $q_tampil_anggota = mysqli_query($db, $sql);
                $nomor = $posisi + 1;

                if(mysqli_num_rows($q_tampil_anggota) > 0){
                    while($r_tampil_anggota = mysqli_fetch_array($q_tampil_anggota)){
                        
                        // Cek Foto
                        $foto_path = "images/" . $r_tampil_anggota['foto'];
                        if(empty($r_tampil_anggota['foto']) || !file_exists($foto_path)){
                            $foto_tampil = "images/avatar-default.png";
                        } else {
                            $foto_tampil = $foto_path;
                        }
                ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $r_tampil_anggota['idanggota']; ?></td>
                    <td><?php echo $r_tampil_anggota['nama']; ?></td>
                    <td style="text-align: center;">
                        <img src="<?php echo $foto_tampil; ?>" width="60" height="60" style="border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-primary);">
                    </td>
                    <td><?php echo $r_tampil_anggota['jeniskelamin']; ?></td>
                    <td><?php echo $r_tampil_anggota['alamat']; ?></td>
                    
                    <!-- Kolom Data No HP DIHAPUS -->

                    <td style="text-align: center;">
                        <a target="_blank" href="pages/cetak-kartu.php?id=<?php echo $r_tampil_anggota['idanggota']; ?>" class="tombol" style="font-size: 0.8em; margin-bottom: 5px;">Cetak Detail</a>
                        
                        <div style="margin-top: 5px;">
                            <a href="index.php?p=anggota-edit&id=<?php echo $r_tampil_anggota['idanggota']; ?>" class="tombol" style="background: #333; border: 1px solid #555;">Edit</a>
                            
                            <a href="proses/anggota-hapus.php?id=<?php echo $r_tampil_anggota['idanggota']; ?>" 
                               onclick="return confirm('Yakin mau menghapus data anggota <?php echo $r_tampil_anggota['nama']; ?>?')" 
                               class="tombol" style="background: linear-gradient(135deg, #500000, #300000); color: #ffcccc;">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding: 20px; font-style:italic;'>Data tidak ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (Tetap Ada) -->
    <div style="margin-top: 20px; text-align: center;">
        <?php
        $jml_data_query = "SELECT COUNT(*) as jml FROM tbanggota";
        if(isset($_POST['search']) && $_POST['pencarian'] != ''){
            $kunci = $_POST['pencarian'];
            $jml_data_query .= " WHERE nama LIKE '%$kunci%' OR idanggota LIKE '%$kunci%'";
        }
        $jml_data = mysqli_fetch_array(mysqli_query($db, $jml_data_query))['jml'];
        $jml_hal = ceil($jml_data / $batas);

        for($i = 1; $i <= $jml_hal; $i++){
            $active_style = ($i == $hal) ? "background-color: var(--gold-primary); color: black; font-weight:bold;" : "background-color: #333;";
            if($i != $hal){
                echo "<a href='index.php?p=anggota&hal=$i' class='tombol' style='$active_style margin: 0 2px;'>$i</a>";
            } else {
                echo "<span class='tombol' style='$active_style margin: 0 2px; cursor: default;'>$i</span>";
            }
        }
        ?>
    </div>

</div>