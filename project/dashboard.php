<?php
session_start();
include 'config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$fullname = $_SESSION['fullname'];

// ดึงข้อมูลวิชาที่นักศึกษาลงทะเบียนแล้ว
$sql = "SELECT c.course_name, c.course_code 
        FROM registrations r
        JOIN courses c ON r.course_id = c.course_id
        WHERE r.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1 style="color: #000000;">ยินดีต้อนรับ, <?php echo htmlspecialchars($fullname); ?>!</h1>
            <nav>
                <a href="courses.php">📚 ลงทะเบียนวิชา</a>
                <a href="student_grades.php">📊 คะแนนของฉัน</a>
                <a href="logout.php" class="logout">🚪 ออกจากระบบ</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="courses">
            <h2>วิชาที่ลงทะเบียนแล้ว</h2>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_name']); ?></li>
                <?php endwhile; ?>
            </ul>
        </section>
    </main>
</body>
</html>
