<?php
session_start();
require_once "../config/database.php";

// Only staff can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';

    $allowed_statuses = ['confirmed', 'cancelled'];

    if ($booking_id && in_array($status, $allowed_statuses, true)) {

        $stmt = $pdo->prepare("
            UPDATE bookings
            SET status = ?
            WHERE id = ?
        ");

        $stmt->execute([$status, $booking_id]);
    }
}

header("Location: dashboard.php");
exit;
?>