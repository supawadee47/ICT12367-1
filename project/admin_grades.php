<?php
session_start();
include 'config.php';

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ดึงรายชื่อนักศึกษาทั้งหมด
$students_sql = "SELECT student_id, fullname FROM students";
$students_result = $conn->query($students_sql);

// กำหนดค่า student_id ถ้าเลือกแล้ว
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$subjects = [];

// ดึงรายวิชาที่นักศึกษาลงทะเบียน ถ้ามีการเลือกนักศึกษา
if ($student_id) {
    $subjects_sql = "SELECT c.course_id, c.course_code, c.course_name, sc.score 
                     FROM registrations r
                     JOIN courses c ON r.course_id = c.course_id
                     LEFT JOIN student_courses sc ON r.student_id = sc.student_id AND r.course_id = sc.course_id
                     WHERE r.student_id = ?";
    $stmt = $conn->prepare($subjects_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $subjects_result = $stmt->get_result();
    while ($row = $subjects_result->fetch_assoc()) {
        // ถ้ายังไม่มีคะแนน ให้ตั้งค่าเป็น NULL
        if (is_null($row['score'])) {
            $row['score'] = '';
        }
        $subjects[] = $row;
    }
}

// อัปเดตหรือเพิ่มคะแนนนักศึกษา
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['scores'])) {
    foreach ($_POST['scores'] as $course_id => $score) {
        $score = floatval($score);
        if ($score >= 0 && $score <= 100) {
            // ตรวจสอบว่ามีข้อมูลอยู่ใน student_courses หรือยัง
            $check_sql = "SELECT * FROM student_courses WHERE student_id = ? AND course_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ii", $student_id, $course_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                // อัปเดตคะแนน
                $update_sql = "UPDATE student_courses SET score = ? WHERE student_id = ? AND course_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("dii", $score, $student_id, $course_id);
                $update_stmt->execute();
            } else {
                // เพิ่มข้อมูลใหม่
                $insert_sql = "INSERT INTO student_courses (student_id, course_id, score) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("iid", $student_id, $course_id, $score);
                $insert_stmt->execute();
            }
        }
    }
    header("Location: admin_grades.php?student_id=" . $student_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคะแนนนักศึกษา</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        select, input, button { padding: 10px; margin: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 8px 12px; background-color: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>📌 จัดการคะแนนนักศึกษา</h1>

    <form method="get">
        <label>เลือกนักศึกษา:</label>
        <select name="student_id" onchange="this.form.submit()">
            <option value="">-- กรุณาเลือก --</option>
            <?php while ($row = $students_result->fetch_assoc()): ?>
                <option value="<?php echo $row['student_id']; ?>" <?php if ($student_id == $row['student_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['fullname']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($student_id && count($subjects) > 0): ?>
        <form method="post">
            <h2>📊 รายวิชาที่ลงทะเบียน</h2>
            <table>
                <tr>
                    <th>รหัสวิชา</th>
                    <th>ชื่อวิชา</th>
                    <th>คะแนน</th>
                </tr>
                <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?php echo htmlspecialchars($subject['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($subject['course_name']); ?></td>
                    <td>
                        <input type="number" name="scores[<?php echo $subject['course_id']; ?>]" 
                               value="<?php echo htmlspecialchars($subject['score']); ?>" 
                               min="0" max="100" required>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit" class="btn">บันทึกคะแนน</button>
        </form>
    <?php elseif ($student_id): ?>
        <p>❌ นักศึกษาคนนี้ยังไม่ได้ลงทะเบียนวิชา</p>
    <?php endif; ?>

    <br>
    <a href="admin_dashboard.php">⬅️ กลับไปแดชบอร์ด</a>
</body>
</html> 