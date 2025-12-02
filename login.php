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
                    <img src="assets/img/chaos_image.png" alt="Chaos Library Illustration">
                    <p class="image-caption">Ji Simpang Karue Rumah Yohanes</p>
                </div>

                <!-- Sisi Kanan: Form Login (Dark Slate) -->
                <div class="login-form-side">
                    <h2>LOGIN</h2>
                    <form method="post" action="cek_login.php">
                        
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