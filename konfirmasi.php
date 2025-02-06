<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Pesanan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi - TravelBooking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            color: #FF5733;
        }

        .container p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pesanan Anda Berhasil</h1>
        <p>Mohon tunggu admin atau driver kami menghubungi Anda</p>
        <p><strong>Total Harga:</strong> Rp <?php echo number_format($order['price'], 0, ',', '.'); ?></p>
        <button onclick="window.location.href='index.php'">Kembali ke Beranda</button>
    </div>
</body>
</html>
