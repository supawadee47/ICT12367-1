<?php
session_start();
include 'config.php';

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// เพิ่มนักศึกษา (ตรวจสอบซ้ำก่อน)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
    $department = isset($_POST['department']) ? trim($_POST['department']) : "";

    // ตรวจสอบว่ามีอีเมลนี้อยู่แล้วหรือไม่
    $check_sql = "SELECT * FROM students WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "⚠️ นักศึกษารายนี้มีอยู่แล้ว!";
    } else {
        $sql = "INSERT INTO students (fullname, email, password, department) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $password, $department);
        
        if ($stmt->execute()) {
            $message = "✅ เพิ่มนักศึกษาสำเร็จ!";
        } else {
            $message = "❌ เกิดข้อผิดพลาด!";
        }
    }
}

// ลบนักศึกษา
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM students WHERE student_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    
    if ($delete_stmt->execute()) {
        $message = "✅ ลบนักศึกษาสำเร็จ!";
    } else {
        $message = "❌ ลบนักศึกษาไม่สำเร็จ!";
    }
}

// ดึงข้อมูลนักศึกษาทั้งหมด
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน - จัดการนักศึกษา</title>
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

    <h1>📌 จัดการนักศึกษา</h1>

    <!-- แจ้งเตือน -->
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <!-- ฟอร์มเพิ่มนักศึกษา -->
    <form method="post">
        <input type="text" name="fullname" placeholder="ชื่อนักศึกษา" required>
        <input type="email" name="email" placeholder="อีเมลนักศึกษา" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <input type="text" name="department" placeholder="คณะและสาขา" required>
        <button type="submit">เพิ่มนักศึกษา</button>
    </form>

    <!-- ตารางแสดงนักศึกษาทั้งหมด -->
    <h2>👨‍🎓 รายชื่อนักศึกษาทั้งหมด</h2>
    <table>
        <tr>
            <th>ชื่อ</th>
            <th>อีเมล</th>
            <th>การจัดการ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <a class="delete-btn" href="admin_students.php?delete_id=<?php echo $row['student_id']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่?')">❌ ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="admin_dashboard.php">⬅️ กลับไปแดชบอร์ด</a>
    <a href="logout.php">🚪 ออกจากระบบ</a>

</body>
</html>
