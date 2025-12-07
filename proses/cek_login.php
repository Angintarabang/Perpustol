<?php
session_start();
include "../koneksi.php";

// Pastikan menangkap inputan user & pass dari form login
$user = mysqli_real_escape_string($db, $_POST['user']);
$pass = mysqli_real_escape_string($db, $_POST['pass']);

$q_login = mysqli_query($db, "SELECT * FROM tbuser WHERE iduser = '$user' AND password = '$pass'");

// Kita butuh struktur HTML dikit buat nampilin SweetAlert
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Process</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* CSS Biar background pas loading item pekat */
        body { background-color: #050505; color: white; font-family: sans-serif; }
        
        /* Custom SweetAlert Chaos Style (Login) */
        div:where(.swal2-container) div:where(.swal2-popup) {
            background: #121212 !important;
            border: 1px solid #ffda47 !important;
            color: #f0f0f0 !important;
        }
        div:where(.swal2-container) .swal2-title {
            color: #ffda47 !important;
            font-family: 'Times New Roman', serif; /* Font Serif biar tegas */
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        div:where(.swal2-container) button.swal2-styled.swal2-confirm {
            background: linear-gradient(135deg, #b8860b, #8a6d00) !important;
            box-shadow: 0 0 15px rgba(255, 218, 71, 0.4);
            color: #fff !important;
        }
    </style>
</head>
<body>

<?php
if(mysqli_num_rows($q_login) > 0){
    $r_login = mysqli_fetch_array($q_login);
    $_SESSION['sesi'] = $r_login['iduser'];
    $_SESSION['nama'] = $r_login['nama'];

    // NOTIF SUKSES (HITAM EMAS)
    echo "<script>
        Swal.fire({
            title: 'ACCESS GRANTED',
            text: 'Selamat Datang di Sistem Chaos, " . $r_login['nama'] . ".',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000,
            background: '#121212',
            color: '#fff'
        }).then(() => {
            window.location='../index.php?p=beranda';
        });
    </script>";
} else {
    // NOTIF GAGAL (HITAM MERAH)
    echo "<script>
        Swal.fire({
            title: 'ACCESS DENIED',
            text: 'Username atau Password Salah!',
            icon: 'error',
            confirmButtonText: 'COBA LAGI',
            background: '#121212',
            color: '#fff',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.location='../login.php';
        });
    </script>";
}
?>

</body>
</html>