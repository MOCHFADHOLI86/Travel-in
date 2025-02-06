<?php
session_start();
include 'includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Step 1: Data Diri
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step1'])) {
    $_SESSION['name'] = htmlspecialchars($_POST['name']);
    $_SESSION['whatsapp'] = htmlspecialchars($_POST['whatsapp']);
    header('Location: order.php?step=2');
    exit;
}

// Step 2: Tanggal Keberangkatan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step2'])) {
    $_SESSION['departure_date'] = htmlspecialchars($_POST['departure_date']);
    header('Location: order.php?step=3');
    exit;
}

// Step 3: Titik Jemput
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step3'])) {
    $_SESSION['titik_jemput_kabupaten_kota'] = htmlspecialchars($_POST['titik_jemput_kabupaten_kota']);
    $_SESSION['titik_jemput_kecamatan'] = htmlspecialchars($_POST['titik_jemput_kecamatan']);
    $_SESSION['titik_jemput_detail_alamat'] = htmlspecialchars($_POST['titik_jemput_detail_alamat']);
    header('Location: order.php?step=4');
    exit;
}

// Step 4: Titik Antar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step4'])) {
    $_SESSION['titik_antar_kabupaten_kota'] = htmlspecialchars($_POST['titik_antar_kabupaten_kota']);
    $_SESSION['titik_antar_kecamatan'] = htmlspecialchars($_POST['titik_antar_kecamatan']);
    $_SESSION['titik_antar_detail_alamat'] = htmlspecialchars($_POST['titik_antar_detail_alamat']);

    // Tentukan harga berdasarkan tujuan
    $destinations = [
        'Surabaya' => 230000,
        'Malang' => 200000,
        'Tuban' => 250000,
        'Bojonegoro' => 250000,
        'Semarang' => 320000,
        'Bangkalan' => 250000,
        'Yogyakarta' => 320000,
    ];
    $destination = $_SESSION['titik_antar_kabupaten_kota'];
    $_SESSION['price'] = $destinations[$destination] ?? 0;

    header('Location: order.php?step=5');
    exit;
}

// Step 5: Pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['step5'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $whatsapp = $_SESSION['whatsapp'];
    $departure_date = $_SESSION['departure_date'];
    $titik_jemput_kabupaten_kota = $_SESSION['titik_jemput_kabupaten_kota'];
    $titik_jemput_kecamatan = $_SESSION['titik_jemput_kecamatan'];
    $titik_jemput_detail_alamat = $_SESSION['titik_jemput_detail_alamat'];
    $titik_antar_kabupaten_kota = $_SESSION['titik_antar_kabupaten_kota'];
    $titik_antar_kecamatan = $_SESSION['titik_antar_kecamatan'];
    $titik_antar_detail_alamat = $_SESSION['titik_antar_detail_alamat'];
    $price = $_SESSION['price'];
    $payment_method = htmlspecialchars($_POST['payment_method']);

    // Simpan ke database
    $sql = "INSERT INTO orders (user_id, name, whatsapp, departure_date, titik_jemput_kabupaten_kota, titik_jemput_kecamatan, titik_jemput_detail_alamat, titik_antar_kabupaten_kota, titik_antar_kecamatan, titik_antar_detail_alamat, price, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $name, $whatsapp, $departure_date, $titik_jemput_kabupaten_kota, $titik_jemput_kecamatan, $titik_jemput_detail_alamat, $titik_antar_kabupaten_kota, $titik_antar_kecamatan, $titik_antar_detail_alamat, $price, $payment_method]);

    // Alihkan ke halaman sukses
    header('Location: konfirmasi.php');
    exit;
}

// Tentukan step
$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - TravelBooking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh; /* Tinggi penuh layar */
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: url('gambar/pengumuman.png'); /* Ganti dengan path gambar Anda */
    background-size: cover; /* Gambar memenuhi seluruh layar */
    background-position: center; /* Gambar berada di tengah */
    background-repeat: no-repeat; /* Tidak mengulang gambar */
    color: #333; /* Warna teks tetap terbaca */
}
        .form-container { background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; text-align: center; }
        .form-container h1 { color: #FF5733; margin-bottom: 20px; }
        .form-container input, .form-container select, .form-container button { width: 100%; padding: 10px; margin: 10px 0; font-size: 16px; border-radius: 5px; border: 1px solid #ddd; }
        .form-container button { background: #FF5733; color: white; border: none; cursor: pointer; transition: 0.3s; }
        .form-container button:hover { background: #FF4500; }
    </style>
</head>
<body>
    <div class="form-container">
        <?php if ($step == 1): ?>
            <h1>Data Diri</h1>
            <form action="order.php" method="POST">
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="text" name="whatsapp" placeholder="Nomor WhatsApp" required>
                <button type="submit" name="step1">Lanjut</button>
            </form>
        <?php elseif ($step == 2): ?>
            <h1>Tanggal Keberangkatan</h1>
            <form action="order.php" method="POST">
                <input type="date" name="departure_date" required>
                <button type="submit" name="step2">Lanjut</button>
            </form>
        <?php elseif ($step == 3): ?>
            <h1>Titik Jemput</h1>
            <form action="order.php" method="POST">
                <select name="titik_jemput_kabupaten_kota" required>
                    <option value="" disabled selected>-- Pilih Kabupaten/Kota --</option>
                    <option value="Banyuwangi">Banyuwangi</option>
                    
                </select>
                <input type="text" name="titik_jemput_kecamatan" placeholder="Kecamatan" required>
                <input type="text" name="titik_jemput_detail_alamat" placeholder="Detail Alamat" required>
                <button type="submit" name="step3">Lanjut</button>
            </form>
        <?php elseif ($step == 4): ?>
            <h1>Titik Antar</h1>
            <form action="order.php" method="POST">
                <select name="titik_antar_kabupaten_kota" required>
                    <option value="" disabled selected>-- Pilih Kabupaten/Kota --</option>
                   
                    <option value="Surabaya">Surabaya</option>
                    <option value="Malang">Malang</option>
                    <option value="Tuban">Tuban</option>
                    <option value="Bojonegoro">Bojonegoro</option>
                    <option value="Semarang">Semarang</option>
                    <option value="Bangkalan">Bangkalan</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                </select>
                <input type="text" name="titik_antar_kecamatan" placeholder="Kecamatan" required>
                <input type="text" name="titik_antar_detail_alamat" placeholder="Detail Alamat" required>
                <button type="submit" name="step4">Lanjut</button>
            </form>
        <?php elseif ($step == 5): ?>
            <h1>Pembayaran</h1>
            <form action="order.php" method="POST">
            <p>Total Harga: Rp <?php echo number_format($_SESSION['price'], 0, ',', '.'); ?></p>
        <p>Tanggal Keberangkatan: <?php echo htmlspecialchars($_SESSION['departure_date']); ?></p> 
        <select name="payment_method" required>
            <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
            <option value="Transfer Bank">BCA - 2630932192 /AN RIFQI AZMI</option>
                </select>
                <button type="submit" name="step5">Selesaikan</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
