<?php
session_start();
session_destroy(); // ทำลายเซสชันทั้งหมด
header("Location: login.php"); // กลับไปที่หน้าล็อกอิน
exit();
?>
