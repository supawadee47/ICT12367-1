<?php
session_start();
include 'config.php';

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['student_id'])) {
    header("Location: admin_grades.php");
    exit();
}

$student_id = $_GET['student_id'];

// ดึงข้อมูลนักศึกษา
$sql_student = "SELECT fullname FROM students WHERE student_id = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("i", $student_id);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
$student = $result_student->fetch_assoc();
$fullname = $student['fullname'];

// เพิ่มคะแนน
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject'], $_POST['score'])) {
    $subject = trim($_POST['subject']);
    $score = floatval($_POST['score']);

    $sql = "INSERT INTO grades (student_id, subject, score) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $student_id, $subject, $score);

    if ($stmt->execute()) {
        $message = "✅ เพิ่มคะแนนสำเร็จ!";
    } else {
        $message = "❌ เกิดข้อผิดพลาด!";
    }
}

// ลบคะแนน
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM grades WHERE grade_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "✅ ลบคะแนนสำเร็จ!";
    } else {
        $message = "❌ ลบคะแนนไม่สำเร็จ!";
    }
}

// ดึงข้อมูลคะแนนของนักศึกษา
$sql_grades = "SELECT * FROM grades WHERE student_id = ?";
$stmt_grades = $conn->prepare($sql_grades);
$stmt_grades->bind_param("i", $student_id);
$stmt_grades->execute();
$result_grades = $stmt_grades->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน - จัดการคะแนนของ <?php echo htmlspecialchars($fullname); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f4f4f4; }
        .delete-btn { color: red; text-decoration: none; }
    </style>
</head>
<body>

    <h1>📌 จัดการคะแนนของ <?php echo htmlspecialchars($fullname); ?></h1>

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <!-- ฟอร์มเพิ่มคะแนน -->
    <form method="post">
        <input type="text" name="subject" placeholder="ชื่อวิชา" required>
        <input type="number" step="0.1" name="score" placeholder="คะแนน" required>
        <button type="submit">เพิ่มคะแนน</button>
    </form>

    <!-- ตารางแสดงคะแนนของนักศึกษา -->
    <h2>📊 รายวิชาและคะแนน</h2>
    <table>
        <tr>
            <th>วิชา</th>
            <th>คะแนน</th>
            <th>จัดการ</th>
        </tr>
        <?php while ($row = $result_grades->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['subject']); ?></td>
            <td><?php echo htmlspecialchars($row['score']); ?></td>
            <td>
                <a class="delete-btn" href="admin_student_grades.php?student_id=<?php echo $student_id; ?>&delete_id=<?php echo $row['grade_id']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่?')">❌ ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="admin_grades.php">⬅️ กลับไปหน้าหลัก</a>

</body>
</html>
