<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Cek apakah username dan password terdaftar
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session jika login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Simpan role pengguna

        // Alihkan berdasarkan role
        if ($user['role'] == 'admin') {
            header('Location: admin.php'); // Halaman admin
        } else {
            header('Location: order.php'); // Halaman pelanggan
        }
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TravelBooking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Styling as before */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .form-container h1 {
            margin-bottom: 20px;
            color: #FF5733;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background: #FF5733;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background: #FF4500;
        }
        .form-container .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
