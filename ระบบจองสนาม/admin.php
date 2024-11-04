<?php
session_start(); // เริ่ม session เพื่อเก็บข้อมูลผู้ใช้

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'field_booking';
$username = 'root';  // แทนที่ด้วยชื่อผู้ใช้ของคุณ
$password = '';      // แทนที่ด้วยรหัสผ่านของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "<div style='text-align: center; color: red;'>กรุณาล็อกอินเพื่อทำการจองสนาม</div>";
    exit();
}

// รับข้อมูลจากฟอร์มและตรวจสอบค่า
$name = mysqli_real_escape_string($conn, $_POST['name']);
$sport_type = mysqli_real_escape_string($conn, $_POST['sport_type']);
$booking_date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$duration = (int)$_POST['duration'];

// แทนที่รหัสสนามให้รับจากฟอร์มอย่างถูกต้อง
$field_id = mysqli_real_escape_string($conn, $_POST['field']); // ใช้ mysqli_real_escape_string เพื่อหลีกเลี่ยง SQL Injection

// ตรวจสอบว่ามีการจองในช่วงวันและเวลานี้อยู่แล้วหรือไม่
$end_time = date("H:i:s", strtotime("+$duration hours", strtotime($time))); // เวลาสิ้นสุดของการจอง
$check_sql = "SELECT * FROM bookings WHERE field_id = '$field_id' AND booking_date = '$booking_date' 
              AND ('$time' < ADDTIME(booking_time, SEC_TO_TIME(duration * 3600)) AND '$end_time' > booking_time)";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    // ถ้าพบการจองในช่วงเวลาดังกล่าว
    echo "<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-image: url('https://img2.pic.in.th/pic/-5fddcd736050fc8f2.jpg');
        background-size: cover;
        color: white;
    }
    .container {
        text-align: center;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 10px;
        max-width: 500px;
        width: 90%;
    }
  </style>";

    echo "<div class='container'>";
    echo "<p>ช่วงวันและเวลาที่เลือกมีการจองอยู่แล้ว กรุณาเลือกช่วงเวลาอื่น</p>";
    echo "<a href='booking.php' style='text-decoration: none; padding: 10px 20px; color: white; background-color: blue; border-radius: 5px;'>กลับสู่หน้าจอง</a>";
    echo "</div>";
} else {
    // บันทึกข้อมูลลงในฐานข้อมูลถ้าไม่มีการจองซ้ำ
    $user_id = $_SESSION['user_id']; // รับ user_id จาก session
    $sql = "INSERT INTO bookings (user_id, field_id, name, sport_type, booking_date, booking_time, duration) 
            VALUES ('$user_id', '$field_id', '$name', '$sport_type', '$booking_date', '$time', $duration)";

    if ($conn->query($sql) === TRUE) {
        echo "<style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-image: url('https://img2.pic.in.th/pic/-5fddcd736050fc8f2.jpg');
                    background-size: cover;
                    color: white;
                }
                .container {
                    text-align: center;
                    padding: 20px;
                    background-color: rgba(0, 0, 0, 0.7);
                    border-radius: 10px;
                    max-width: 500px;
                    width: 90%;
                }
              </style>";

        echo "<div class='container'>";
        echo "<h1>บันทึกการจองสำเร็จ</h1>";
        echo "<p>ชื่อผู้จอง: $name</p>";
        echo "<p>ประเภทกีฬา: $sport_type</p>";
        echo "<p>วันที่จอง: $booking_date</p>";
        echo "<p>เวลาที่จอง: $time</p>";
        echo "<p>ระยะเวลา: $duration ชั่วโมง</p>";
        echo "<p>รหัสสนาม: $field_id</p>";
        echo "<p>*อย่าลืมแคปหน้าจอทุกครั้ง*</p>";
        echo "<a href='booking.php' style='text-decoration: none; padding: 10px 20px; color: white; background-color: green; border-radius: 5px;'>กลับสู่หน้าจอง</a>";
        echo "</div>";
    } else {
        echo "<div style='text-align: center; color: red;'>เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error . "</div>";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
