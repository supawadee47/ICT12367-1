<?php
session_start();
include 'config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// เช็คว่ามีการกดปุ่มลงทะเบียนหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // ตรวจสอบว่านักศึกษาได้ลงทะเบียนวิชานี้แล้วหรือยัง
    $check_sql = "SELECT * FROM registrations WHERE student_id = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $student_id, $course_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 0) {
        // ลงทะเบียนวิชา
        $insert_sql = "INSERT INTO registrations (student_id, course_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $student_id, $course_id);

        if ($insert_stmt->execute()) {
            echo "<script>alert('✅ ลงทะเบียนสำเร็จ!');</script>";
        } else {
            echo "<script>alert('❌ เกิดข้อผิดพลาด!');</script>";
        }
    } else {
        echo "<script>alert('⚠️ คุณได้ลงทะเบียนวิชานี้แล้ว!');</script>";
    }
}

// ดึงรายวิชาทั้งหมด
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนวิชา</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📚 รายวิชาที่เปิดสอน</h1>
        <form method="post">
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_name']); ?>
                        <button type="submit" name="course_id" value="<?php echo $row['course_id']; ?>">ลงทะเบียน</button>
                    </li>
                <?php endwhile; ?>
            </ul>
        </form>
        <a href="dashboard.php">🔙 กลับไปแดชบอร์ด</a>
    </div>
</body>
</html>
