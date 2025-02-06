<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Booking</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    font-family: 'Poppins', sans-serif;
    background-image: url('gambar/TRAVEL.png'); /* Ganti dengan lokasi gambar */
    background-size: cover; /* Gambar memenuhi layar */
    background-position: center; /* Gambar di tengah */
    background-repeat: no-repeat; /* Gambar tidak mengulang */
    color: white;
    height: 100vh; /* Tinggi penuh layar */
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin: 0;
}


        .welcome-container {
            padding: 20px;
            background: rgba(0, 0, 0, 0.6); /* Background gelap agar teks terlihat jelas */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .welcome-container h1 {
            font-size: 50px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .cta-button {
            background-color: #ff5733;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #ff4500;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Selamat Datang di TravelBooking</h1>
        <p>Temukan perjalanan terbaik untuk Anda</p>
        <a href="order.php" class="cta-button">Pesan Sekarang</a>
    </div>
</body>
</html>
