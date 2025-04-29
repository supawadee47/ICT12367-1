<?php
session_start();
include 'config.php';

// ตรวจสอบว่านักศึกษาเข้าสู่ระบบ
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// ดึงคะแนนของนักศึกษา พร้อมชื่อวิชา และรหัสวิชา
$sql = "SELECT c.course_code, c.course_name, sc.score 
        FROM student_courses sc
        JOIN courses c ON sc.course_id = c.course_id
        WHERE sc.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
$total_weighted_score = 0; // คะแนนรวมที่ถูกถ่วงน้ำหนักด้วยหน่วยกิต
$total_credits = 0; // หน่วยกิตรวม

// ฟังก์ชันแปลงคะแนนเป็นเกรด
function calculateGrade($score) {
    if ($score >= 80) return ['A', 4.0];
    if ($score >= 75) return ['B+', 3.5];
    if ($score >= 70) return ['B', 3.0];
    if ($score >= 65) return ['C+', 2.5];
    if ($score >= 60) return ['C', 2.0];
    if ($score >= 55) return ['D+', 1.5];
    if ($score >= 50) return ['D', 1.0];
    return ['F', 0.0]; // คะแนนต่ำกว่า 50 ได้ F
}

// ฟังก์ชันกำหนดหน่วยกิตตามรหัสวิชา
function getCredits($course_code) {
    if (strpos($course_code, '10') === 0) return 1;
    if (strpos($course_code, '11') === 0) return 2;
    if (strpos($course_code, '12') === 0) return 3;
    return 0; // ค่าเริ่มต้นถ้าไม่มีตรงเงื่อนไข
}

while ($row = $result->fetch_assoc()) {
    $credits = getCredits($row['course_code']); // หาหน่วยกิต
    list($grade, $grade_point) = calculateGrade($row['score']); // คำนวณเกรด

    // คำนวณค่าถ่วงน้ำหนักของเกรด
    $total_weighted_score += $grade_point * $credits;
    $total_credits += $credits;

    $subjects[] = [
        'course_code' => $row['course_code'],
        'course_name' => $row['course_name'],
        'score' => $row['score'],
        'credits' => $credits,
        'grade' => $grade
    ];
}

// คำนวณเกรดเฉลี่ยแบบถ่วงน้ำหนัก
$gpa = $total_credits > 0 ? $total_weighted_score / $total_credits : 0;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นักศึกษา - คะแนนและเกรด</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>

<body>
    <div class="container">
        <h1>📊 คะแนนของฉัน</h1>

        <?php if (!empty($subjects)): ?>
        <table>
            <thead>
                <tr>
                    <th>รหัสวิชา</th>
                    <th>ชื่อวิชา</th>
                    <th>คะแนน</th>
                    <th>หน่วยกิต</th>
                    <th>เกรด</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?php echo htmlspecialchars($subject['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($subject['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($subject['score']); ?></td>
                    <td><?php echo htmlspecialchars($subject['credits']); ?></td>
                    <td><?php echo htmlspecialchars($subject['grade']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>🎓 เกรดเฉลี่ย (GPA): <?php echo number_format($gpa, 2); ?></h2>
        
        <?php else: ?>
        <p>❌ คุณยังไม่มีคะแนนในระบบ</p>
        <?php endif; ?>

        <br>
        <a href="dashboard.php">⬅️ กลับไปแดชบอร์ด</a>
    </div>
</body>

</html>
