<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config/database.php";


// Admin access protection
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


// Get booking ID
$booking_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


// Validate booking ID
if ($booking_id <= 0) {
    header("Location: manage-bookings.php");
    exit;
}


// Check whether booking exists
$stmt = $pdo->prepare("
    SELECT id
    FROM bookings
    WHERE id = ?
");

$stmt->execute([
    $booking_id
]);

$booking = $stmt->fetch(PDO::FETCH_ASSOC);


// If booking does not exist
if (!$booking) {
    header("Location: manage-bookings.php");
    exit;
}


// Delete booking
$stmt = $pdo->prepare("
    DELETE FROM bookings
    WHERE id = ?
");

$stmt->execute([
    $booking_id
]);


// Return to Manage Bookings
header("Location: manage-bookings.php");
exit;

?>