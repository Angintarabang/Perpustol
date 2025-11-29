<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Tampil Data Buku</h3></div>

<div id="content">
    <p id="tombol-tambah-container">
        <a href="index.php?p=buku-input" class="tombol">Tambah Buku</a>
        <a href="pages/buku-cetak.php" target="_blank">
            <img src="print.png" width="50px">
        </a>
    </p>

    <form method="post">
        <input type="text" name="pencarian">
        <input type="submit" name="search" value="search" class="tombol">
    </form>

    <table id="tabel-tampil">
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>

        <?php
        $sql = mysqli_query($db, "SELECT * FROM tbbuku ORDER BY idbuku ASC");
        $no = 1;
        while($data = mysqli_fetch_array($sql)){
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['idbuku']; ?></td>
            <td><?= $data['judulbuku']; ?></td>
            <td><?= $data['kategori']; ?></td>
            <td><?= $data['pengarang']; ?></td>
            <td><?= $data['penerbit']; ?></td>
            <td><?= $data['status']; ?></td>

            <td>
                <a class="tombol" href="index.php?p=buku-edit&id=<?= $data['idbuku']; ?>">Edit</a>
                <a class="tombol" href="index.php?p=buku-hapus&id=<?= $data['idbuku']; ?>" onclick="return confirm('Yakin?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
