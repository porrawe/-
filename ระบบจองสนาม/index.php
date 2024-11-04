<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Booking System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background-image: url('https://img2.pic.in.th/pic/fa965cbe087175d3fe0f428655e8ae07.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.8); /* เพิ่มพื้นหลังโปร่งแสง */
            padding: 40px; /* ขยาย padding */
            border-radius: 10px; /* ขอบมน */
            max-width: 600px; /* กำหนดความกว้างสูงสุด */
            text-align: center; /* จัดข้อความกลาง */
        }

        h1 {
            font-size: 3rem; /* ขยายขนาดของ h1 */
            margin-bottom: 20px;
        }

        p {
            font-size: 1.5rem; /* ขยายขนาดของ p */
            margin-bottom: 30px;
        }

        .btn {
            font-size: 1.25rem; /* ขยายขนาดของปุ่ม */
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4">Welcome to Field System</h1>
        <p class="text-center">ระบบจองสนามกีฬา</p>
        <div class="text-center">
            <a href="login.php" class="btn btn-primary m-2">Welcome</a> <!-- ปุ่ม Welcome -->
        </div>
    </div>
</body>
</html>
