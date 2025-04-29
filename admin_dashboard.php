<?php
session_start();

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน - แดชบอร์ด</title>
    <style>
        /* Body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f9fc; /* สีพื้นหลังอ่อน */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Container หลัก */
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        /* หัวข้อ */
        h1 {
            font-size: 28px;
            color: #4A90E2;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* ปุ่ม */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* ปุ่มออกจากระบบ */
        a.logout {
            display: block;
            margin-top: 30px;
            color: #F14C4C;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        a.logout:hover {
            color: #F1A7A1;
        }

        /* ปรับแต่งมือถือ */
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }

            .btn {
                font-size: 14px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 20px;
            }

            .btn {
                font-size: 12px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>👨‍💻 แดชบอร์ดแอดมิน</h1>
        <div class="button-container">
            <!-- ปุ่มไปยังหน้า 1 (จัดการวิชา) -->
            <a href="admin_courses.php" class="btn">จัดการรายวิชา</a>
        
            <!-- ปุ่มไปยังหน้า 2 (จัดการนักศึกษา) -->
            <a href="admin_students.php" class="btn">จัดการนักศึกษา</a>
        
            <!-- ปุ่มไปยังหน้า 3 (ลงคะแนนรายวิชา) -->
            <a href="admin_grades.php" class="btn">ลงคะแนนรายวิชา</a>
        </div>

        <!-- ลิงก์ออกจากระบบ -->
        <a href="logout.php" class="logout">🚪 ออกจากระบบ</a>
    </div>

</body>
</html>
