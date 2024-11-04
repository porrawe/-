<?php
require 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

// ดึงข้อมูลการจองทั้งหมดจากฐานข้อมูล
$bookings = [];
$sql = "SELECT * FROM bookings";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = [
        'title' => 'จองแล้ว',
        'start' => $row['booking_date'] . 'T' . $row['booking_time'],
    ];
}

// ดึงข้อมูลชื่อสนามจากตาราง fields
$fields = [];
$field_sql = "SELECT field_id, field_name FROM fields";
$field_result = mysqli_query($conn, $field_sql);

while ($field_row = mysqli_fetch_assoc($field_result)) {
    $fields[] = $field_row;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองสนามกีฬา</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://img5.pic.in.th/file/secure-sv1/-4340b95e8a4dec9ce.jpg');
            background-color: #f5f5f5;
            padding: 20px;
        }
        .content {
            display: flex;
            width: 100%;
            max-width: 1200px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }
        .calendar-section {
            flex: 2;
            margin-right: 20px;
        }
        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .booking-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .booking-form input,
        .booking-form select,
        .booking-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .booking-form button {
            background-color: #007bff;
            color: white;
            border: none;
        }
        #calendar {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
        /* ส่วนสำหรับชื่อผู้ใช้และกรอบรูป */
        .user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* จัดชิดขวา */
            margin-bottom: 20px;
            position: relative; /* ใช้สำหรับ dropdown */
        }
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .user-info span {
            font-weight: bold;
            cursor: pointer; /* แสดงว่าเป็นปุ่ม */
        }
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .dropdown.show {
            display: block;
        }
        .dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }
        .dropdown a:hover {
            background: #f0f0f0;
        }
        .booking-form label {
    display: block; /* จัดให้ป้ายกำกับแสดงเป็นบล็อก */
    margin: 15px 0 5px; /* เพิ่มระยะห่างด้านบน */
}

    </style>
</head>
<body>

<!-- แสดงชื่อผู้ใช้และกรอบรูป -->
<div class="user-info">
    <img src="https://img2.pic.in.th/pic/icone-utilisateur.png" alt="Profile Picture"> <!-- รูปโปรไฟล์ทั่วไปสำหรับทุกคน -->
    <span id="username"><?php echo htmlspecialchars($_SESSION['username']); // แสดงชื่อผู้ใช้ ?></span> 
    <div class="dropdown" id="logout-dropdown">
        <a href="logout.php">ออกจากระบบ</a>
    </div>
</div>

<div class="content">
    <!-- ส่วนปฏิทิน -->
    <div class="calendar-section">
        <h2>ปฏิทินการจอง</h2>
        <div id="calendar"></div>
    </div>

    <!-- ฟอร์มจองสนามกีฬา -->
    <div class="form-section">
        <h2>จองสนามกีฬา</h2>
        <form action="admin.php" method="POST" class="booking-form">
            <label for="name">ชื่อผู้จอง:</label>
            <input type="text" id="name" name="name" required>

            <label for="sport_type">ประเภทกีฬา:</label>
            <select id="sport_type" name="sport_type" required>
                <option value="football">ฟุตบอล</option>
                <option value="basketball">บาสเกตบอล</option>
                <option value="tennis">เทนนิส</option>
                <option value="badminton">แบดมินตัน</option>
                <option value="futsal">ฟุตซอล</option>
                <option value="fitness">ยิมฟิตเนส</option>
                <option value="swimming">ว่ายนํ้า</option>
                <option value="volleyball">วอลเลย์บอล</option>
                <option value="gym">ยิมฟิตเนส</option>
            </select>

            <label for="field">ชื่อสนาม:</label>
            <select id="field" name="field" required>
            <option value="FC01">สนามฟุตบอล 1 (FC01)</option>
                <option value="FC02">สนามฟุตบอล 2 (FC02)</option>
                <option value="FC03">สนามฟุตบอล 3 (FC03)</option>
                <option value="FC04">สนามฟุตบอล 4 (FC04)</option>
                <option value="D01">อาคารโดม (D01) - สำหรับบาสเกตบอล</option>
                <option value="D02">อาคารโดม (D02) - สำหรับฟุตซอล</option>
                <option value="D03">อาคารโดม (D03) - สำหรับวอลเลย์บอล</option>
                <option value="FIBA01">สนามบาสเกตบอล 1 (FIBA01)</option>
                <option value="FIBA02">สนามบาสเกตบอล 2 (FIBA02)</option>
                <option value="FIBA03">สนามบาสเกตบอล 3 (FIBA03)</option>
                <option value="TC01">สนามเทนนิส 1 (TC01)</option>
                <option value="TC02">สนามเทนนิส 2 (TC02)</option>
                <option value="TC03">สนามเทนนิส 3 (TC03)</option>
                <option value="BC01">สนามแบดมินตัน 1 (BC01)</option>
                <option value="BC02">สนามแบดมินตัน 2 (BC02)</option>
                <option value="BC03">สนามแบดมินตัน 3 (BC03)</option>
                <option value="BC04">สนามแบดมินตัน (อาคารกีฬาในร่ม) (BC04)</option>
                <option value="GYM01">ยิมฟิตเนส (GYM01)</option>
                <option value="FS01">สนามฟุตซอล 1 (FS01)</option>
                <option value="FS02">สนามฟุตซอล 2 (FS02)</option>
                <option value="VC01">สนามวอลเลย์บอล 1 (VC01)</option>
                <option value="VC02">สนามวอลเลย์บอล 2 (VC02)</option>
                <option value="SP01">สระน้ำสุพรรณกรรยา (SP01)</option>

                <?php foreach ($fields as $field): ?>
                    <option value="<?php echo $field['field_id']; ?>"><?php echo $field['field_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="date">วันที่จอง:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">เวลาที่จอง:</label>
            <input type="time" id="time" name="time" required>

            <label for="duration">ระยะเวลา (ชั่วโมง):</label>
            <input type="number" id="duration" name="duration" min="1" required>

            <button type="submit">ส่งการจอง</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($bookings); ?> // ดึงข้อมูลการจองจาก PHP
        });
        calendar.render();
        
        // Dropdown for logout
        const usernameSpan = document.getElementById('username');
        const dropdown = document.getElementById('logout-dropdown');

        usernameSpan.addEventListener('click', function() {
            dropdown.classList.toggle('show');
        });

        // Close dropdown if clicked outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('#username') && !event.target.matches('#logout-dropdown a')) {
                dropdown.classList.remove('show');
            }
        });
    });
</script>

</body>
</html> 