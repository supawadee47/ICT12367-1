<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $department = $_POST['department'];

    $sql = "INSERT INTO students (fullname, email, password, department) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $email, $password, $department);

    if ($stmt->execute()) {
        echo "ลงทะเบียนสำเร็จ! <a href='login.php'>เข้าสู่ระบบ</a>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="register-container">
        <h1>ลงทะเบียน</h1>
        <form method="post" class="register-form">
            <input type="text" name="fullname" placeholder="ชื่อ-นามสกุล" required>
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <input type="text" name="department" placeholder="คณะ / สาขา">
            <button type="submit" class="register-btn">ลงทะเบียน</button>
        </form>
        <div class="login-link">
            <a href="login.php">ไปหน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</body>

</html>
