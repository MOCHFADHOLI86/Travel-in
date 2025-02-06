<?php
$servername = "localhost"; // Nama host database (localhost jika di server lokal seperti XAMPP)
$username = "root"; // Username untuk database (default untuk XAMPP adalah 'root')
$password = ""; // Password untuk database (kosong jika menggunakan XAMPP default)
$dbname = "travel_booking"; // Nama database yang ingin digunakan

// Membuat koneksi menggunakan PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";  // Menampilkan pesan jika koneksi berhasil (opsional)
} catch (PDOException $e) {
    // Menampilkan pesan kesalahan jika koneksi gagal
    echo "Koneksi gagal: " . $e->getMessage();
    die(); // Menghentikan eksekusi script jika terjadi kesalahan
}
?>
