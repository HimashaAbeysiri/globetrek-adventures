<?php
require_once "config/database.php";

$name = "GlobeTrek Admin";
$email = "admin@globetrek.com";
$password = "admin123";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (?, ?, ?, 'admin')
");

$stmt->execute([
    $name,
    $email,
    $hashedPassword
]);

echo "Admin account created successfully.<br><br>";
echo "Email: " . $email . "<br>";
echo "Password: " . $password;
?>