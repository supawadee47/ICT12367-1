<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // เช็คว่าเป็นแอดมินหรือไม่
    $sql_admin = "SELECT admin_id, fullname, password FROM admins WHERE email = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['fullname'] = $row['fullname'];
            header("Location: admin_dashboard.php"); // ไปหน้าแอดมิน
            exit();
        }
    }

    // เช็คว่าเป็นนักศึกษาหรือไม่
    $sql_student = "SELECT student_id, fullname, password FROM students WHERE email = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("s", $email);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    if ($result_student->num_rows > 0) {
        $row = $result_student->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['fullname'] = $row['fullname'];
            header("Location: dashboard.php"); // ไปหน้าของนักศึกษา
            exit();
        }
    }

    echo "❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>

<body>
    <div class="login-container">
        <h1>เข้าสู่ระบบ</h1>

        <form method="post" class="login-form">
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit" class="login-btn">เข้าสู่ระบบ</button>
        </form>

        <div class="signup-link">
            <a href="register.php">ลงทะเบียน</a>
        </div>
    </div>
</body>

</html>
