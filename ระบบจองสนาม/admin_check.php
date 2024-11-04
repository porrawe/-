<?php
session_start();

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php"); // ถ้าไม่ใช่แอดมิน ให้กลับไปหน้า login
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลการจองทั้งหมดจากฐานข้อมูล
$sql = "SELECT booking_id, user_name, field_name, booking_date, booking_time FROM bookings";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Check Bookings</title>
</head>
<body>

<h1>ตรวจสอบการจองสนาม</h1>
<table border="1">
    <tr>
        <th>Booking ID</th>
        <th>ชื่อผู้จอง</th>
        <th>สนามที่จอง</th>
        <th>วันที่จอง</th>
        <th>เวลาที่จอง</th>
    </tr>

    <?php
    // แสดงข้อมูลการจอง
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['booking_id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['field_name'] . "</td>";
            echo "<td>" . $row['booking_date'] . "</td>";
            echo "<td>" . $row['booking_time'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>ไม่มีการจอง</td></tr>";
    }
    $conn->close();
    ?>

</table>

</body>
</html>

