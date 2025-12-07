<?php
// Pastikan tidak ada karakter lain sebelum tag <html>
session_start();
if(isset($_SESSION['user'])) {
    // Jika sudah login, redirect ke halaman utama
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Login - ADMIN OF CHAOS</title>
        <!-- Google Fonts: Cinzel (untuk Judul) & Poppins (untuk Body) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style_login.css">
        <!-- Menggunakan Font dari Google untuk tampilan yang lebih baik -->
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Roboto:wght@300&display=swap" rel="stylesheet">
    </head>
   
    <body>
        <div class="login-wrapper">
            <h1 class="chaos-title">ADMIN OF CHAOS</h1>
            
            <div class="login-container">
                
                <!-- Sisi Kiri: Gambar/Ilustrasi (Merah Chaos) -->
                <div class="login-image-side">
                    <!-- Pastikan path ke gambar Anda benar! -->
                    <img src="images/bukumewah.jpeg" alt="Chaos Library Illustration">
                    <p class="image-caption">Ji Simpang Karue Rumah Yohanes</p>
                </div>

                <!-- Sisi Kanan: Form Login (Dark Slate) -->
                <div class="login-form-side">
                    <h2>LOGIN</h2>
                    <form method="post" action="proses/cek_login.php">
                        
                        <div class="input-group">
                            <label for="user">Username</label>
                            <input type="text" id="user" name="user" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="pass">Password</label>
                            <input type="password" id="pass" name="pass" required>
                        </div>
                        
                        <button type="submit" name="submit" class="login-button">MASUK KE CHAOS</button>               
                    </form>
                </div>
            </div>
        </div>     
    </body>
</html>