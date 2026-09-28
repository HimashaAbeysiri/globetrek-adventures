<?php
session_start();
require_once "../config/database.php";

// Only staff can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $query_id = filter_input(
        INPUT_POST,
        'query_id',
        FILTER_VALIDATE_INT
    );

    if ($query_id) {

        $stmt = $pdo->prepare("
            UPDATE queries
            SET status = 'responded'
            WHERE id = ?
        ");

        $stmt->execute([$query_id]);
    }
}

header("Location: dashboard.php");
exit;
?>