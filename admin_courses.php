<?php
session_start();
include 'config.php';

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// เพิ่มรายวิชา (ตรวจสอบซ้ำก่อน)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_code']) && isset($_POST['course_name'])) {
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);

    // ตรวจสอบว่ามีรหัสวิชานี้อยู่แล้วหรือไม่
    $check_sql = "SELECT * FROM courses WHERE course_code = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $course_code);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "⚠️ รายวิชานี้มีอยู่แล้ว!";
    } else {
        $sql = "INSERT INTO courses (course_code, course_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $course_code, $course_name);
        
        if ($stmt->execute()) {
            $message = "✅ เพิ่มรายวิชาสำเร็จ!";
        } else {
            $message = "❌ เกิดข้อผิดพลาด!";
        }
    }
}

// ลบรายวิชา
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM courses WHERE course_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    
    if ($delete_stmt->execute()) {
        $message = "✅ ลบรายวิชาสำเร็จ!";
    } else {
        $message = "❌ ลบรายวิชาไม่สำเร็จ!";
    }
}

// ดึงข้อมูลวิชาทั้งหมด
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน - จัดการรายวิชา</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { margin-bottom: 20px; }
        input, button { padding: 10px; margin: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .delete-btn { color: red; text-decoration: none; }
    </style>
</head>
<body>

    <h1>📌 จัดการรายวิชา</h1>

    <!-- แจ้งเตือน -->
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <!-- ฟอร์มเพิ่มรายวิชา -->
    <form method="post">
        <input type="text" name="course_code" placeholder="รหัสวิชา" required>
        <input type="text" name="course_name" placeholder="ชื่อวิชา" required>
        <button type="submit">เพิ่มวิชา</button>
    </form>

    <!-- ตารางแสดงรายวิชา -->
    <h2>📚 รายวิชาทั้งหมด</h2>
    <table>
        <tr>
            <th>รหัสวิชา</th>
            <th>ชื่อวิชา</th>
            <th>การจัดการ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td>
                <a class="delete-btn" href="admin.php?delete_id=<?php echo $row['course_id']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่?')">❌ ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="admin_dashboard.php">🚪 ไปหน้าแดชบอดแอดมิน</a>
    <br>
    <a href="logout.php">🚪 ออกจากระบบ</a>
    
</body>
</html>
