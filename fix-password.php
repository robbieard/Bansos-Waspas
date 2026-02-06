<?php
/**
 * AUTOMATIC PASSWORD FIX SCRIPT
 * Run this once to fix admin password in database
 */

require_once 'classes/Database.php';

$db = Database::getInstance();

// Generate new hash for 'admin123'
$password = 'admin123';
$newHash = password_hash($password, PASSWORD_BCRYPT);

try {
    // Update admin password
    $sql = "UPDATE users SET password = ? WHERE username = 'admin'";
    $result = $db->execute($sql, [$newHash]);
    
    if ($result > 0) {
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Password Fixed</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 40px; 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            color: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #059669;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>✅ Password Berhasil Diperbaiki!</h1>
        
        <div class='success'>
            <strong>✓ Password admin sudah di-reset!</strong>
        </div>
        
        <div class='info'>
            <h3>Login Credentials:</h3>
            <p><strong>Username:</strong> <code>admin</code></p>
            <p><strong>Password:</strong> <code>admin123</code></p>
            <p><strong>New Hash:</strong><br><code style='word-break: break-all;'>$newHash</code></p>
        </div>
        
        <h3>Langkah Selanjutnya:</h3>
        <ol>
            <li>Hapus file <code>fix-password.php</code> ini (untuk keamanan)</li>
            <li>Kembali ke halaman login</li>
            <li>Login dengan username: <strong>admin</strong> dan password: <strong>admin123</strong></li>
        </ol>
        
        <a href='login.php' class='btn'>🔐 Login Sekarang</a>
        
        <p style='margin-top: 30px; font-size: 12px; color: #666;'>
            <strong>Catatan:</strong> File ini harus dihapus setelah selesai untuk keamanan sistem.
        </p>
    </div>
</body>
</html>";
    } else {
        echo "<!DOCTYPE html>
<html>
<head><title>Error</title></head>
<body style='font-family: Arial; padding: 40px;'>
    <h1>❌ Error</h1>
    <p>User 'admin' tidak ditemukan di database!</p>
    <p>Pastikan database sudah di-import dengan benar.</p>
    <a href='index.php'>Kembali</a>
</body>
</html>";
    }
} catch (Exception $e) {
    echo "<!DOCTYPE html>
<html>
<head><title>Error</title></head>
<body style='font-family: Arial; padding: 40px;'>
    <h1>❌ Database Error</h1>
    <p>" . htmlspecialchars($e->getMessage()) . "</p>
    <p>Periksa koneksi database di <code>config/database.php</code></p>
</body>
</html>";
}
?>
