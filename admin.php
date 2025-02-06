<?php
session_start();
include 'includes/db.php';

// Hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil data pesanan dari database
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses pembaruan data jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_order'])) {
    $order_id = intval($_POST['order_id']);
    $name = htmlspecialchars($_POST['name']);
    $whatsapp = htmlspecialchars($_POST['whatsapp']);
    $departure_date = htmlspecialchars($_POST['departure_date']);
    $titik_jemput_kabupaten_kota = htmlspecialchars($_POST['titik_jemput_kabupaten_kota']);
    $titik_jemput_kecamatan = htmlspecialchars($_POST['titik_jemput_kecamatan']);
    $titik_antar_kabupaten_kota = htmlspecialchars($_POST['titik_antar_kabupaten_kota']);
    $titik_antar_kecamatan = htmlspecialchars($_POST['titik_antar_kecamatan']);
    $price = intval($_POST['price']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    $sql = "UPDATE orders 
            SET name = ?, whatsapp = ?, departure_date = ?, titik_jemput_kabupaten_kota = ?, titik_jemput_kecamatan = ?, 
                titik_antar_kabupaten_kota = ?, titik_antar_kecamatan = ?, price = ?, payment_method = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $name, $whatsapp, $departure_date, $titik_jemput_kabupaten_kota, $titik_jemput_kecamatan,
        $titik_antar_kabupaten_kota, $titik_antar_kecamatan, $price, $payment_method, $order_id
    ]);

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh; /* Tinggi halaman penuh */
    width: 100vw; /* Lebar halaman penuh */
    background-image: url('gambar/TRAVEL.png'); /* Ganti dengan path gambar Anda */
    background-size: cover; /* Gambar akan menutupi seluruh layar */
    background-repeat: no-repeat; /* Gambar tidak diulang */
    background-position: center; /* Gambar diposisikan di tengah */
    color: #333;
    overflow-x: hidden; /* Hindari scroll horizontal jika gambar terlalu besar */
}

    .container {
        width: 90%;
        margin: 20px auto;
        background: rgba(255, 255, 255, 0.9); /* Warna putih semi-transparan */
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #FF5733;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            color: #FF5733;
        }
        .logout-btn {
            background-color: #FF5733;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #FF4500;
        }
        form input, form select, form button {
            padding: 8px;
            margin-bottom: 5px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        form button {
            background-color: #FF5733;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard Admin</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        <h2>Daftar Pesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>WhatsApp</th>
                    <th>Keberangkatan</th>
                    <th>Titik Jemput</th>
                    <th>Titik Antar</th>
                    <th>Harga</th>
                    <th>Metode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <form action="admin.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <td><?php echo $order['id']; ?></td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($order['name']); ?>" required></td>
                                <td><input type="text" name="whatsapp" value="<?php echo htmlspecialchars($order['whatsapp']); ?>" required></td>
                                <td><input type="date" name="departure_date" value="<?php echo htmlspecialchars($order['departure_date']); ?>" required></td>
                                <td>
                                    <input type="text" name="titik_jemput_kabupaten_kota" value="<?php echo htmlspecialchars($order['titik_jemput_kabupaten_kota']); ?>" required>
                                    <input type="text" name="titik_jemput_kecamatan" value="<?php echo htmlspecialchars($order['titik_jemput_kecamatan']); ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="titik_antar_kabupaten_kota" value="<?php echo htmlspecialchars($order['titik_antar_kabupaten_kota']); ?>" required>
                                    <input type="text" name="titik_antar_kecamatan" value="<?php echo htmlspecialchars($order['titik_antar_kecamatan']); ?>" required>
                                </td>
                                <td><input type="number" name="price" value="<?php echo htmlspecialchars($order['price']); ?>" required></td>
                                <td>
                                    <select name="payment_method" required>
                                        <option value="Transfer Bank" <?php echo $order['payment_method'] == 'Transfer Bank' ? 'selected' : ''; ?>>Transfer Bank</option>
                                        <option value="Cash" <?php echo $order['payment_method'] == 'Cash' ? 'selected' : ''; ?>>Cash</option>
                                    </select>
                                </td>
                                <td><button type="submit" name="edit_order">Simpan</button></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak ada pesanan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
