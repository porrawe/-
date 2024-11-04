<?php
session_start();
require 'config.php';  // เรียกใช้ไฟล์สำหรับการเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบข้อมูลในฐานข้อมูล
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // เก็บข้อมูล role ของผู้ใช้ เช่น "admin" หรือ "user"

            // ตรวจสอบสิทธิ์แอดมิน
            if ($user['role'] === 'admin') {
                header("Location: admin_check.php");  // ถ้าเป็นแอดมิน ให้ไปที่หน้า admin_check.php
            } else {
                header("Location: booking.php");  // ถ้าไม่ใช่แอดมิน ให้ไปที่หน้า booking.php
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Field Booking System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('https://img5.pic.in.th/file/secure-sv1/-28db278ed52549cc3.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: flex-start; /* จัดให้ชิดซ้าย */
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-left: 20px; /* เพิ่ม padding ซ้าย */
        }

        .container {
            width: 90%;
            max-width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1rem;
        }

        .btn {
            font-size: 1rem;
            padding: 10px;
        }

        /* สำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 576px) {
            h2 {
                font-size: 1.5rem;
            }

            .container {
                padding: 15px;
            }

            .btn {
                font-size: 0.9rem;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login Field System</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="mt-4">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <p class="text-center mt-3">หากคุณยังไม่ได้เป็นสมาชิก <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
