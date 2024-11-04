<?php
require 'config.php';  // เชื่อมต่อกับฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php"); // เปลี่ยนเส้นทางไปยังหน้า login
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('https://img2.pic.in.th/pic/-329fcb25bbed5e7d4.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.85); /* เพิ่มความโปร่งแสงให้กับพื้นหลัง */
            padding: 40px; /* เพิ่ม padding */
            border-radius: 8px; /* ขอบมน */
            max-width: 500px; /* ความกว้างสูงสุด */
            width: 90%; /* ความกว้างที่ปรับได้ */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
        }

        h2 {
            margin-bottom: 20px; /* ระยะห่างระหว่าง h2 กับฟอร์ม */
        }

        .btn {
            margin-top: 20px; /* ระยะห่างด้านบนของปุ่ม */
        }

        a {
            display: block; /* แสดงลิงก์ให้เต็มบล็อค */
            margin-top: 15px; /* ระยะห่างระหว่างปุ่มและลิงก์ */
            text-align: center; /* จัดข้อความกลาง */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Register</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <a href="login.php">กลับสู่หน้าแรก</a>
    </div>
</body>
</html>
