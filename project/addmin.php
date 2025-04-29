<?php
include 'config.php';

$fullname = "Administrator";
$email = "admin@example.com";
$password = password_hash("admin123", PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน

$sql = "INSERT INTO admins (fullname, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fullname, $email, $password);

if ($stmt->execute()) {
    echo "✅ เพิ่มแอดมินสำเร็จ!";
} else {
    echo "❌ เกิดข้อผิดพลาด!";
}
?>
