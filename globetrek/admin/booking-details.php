<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


$booking_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($booking_id <= 0) {
    header("Location: manage-bookings.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT
        b.id,
        b.travel_date,
        b.travelers,
        b.total_price,
        b.status,
        b.created_at,
        u.name AS customer_name,
        u.email AS customer_email,
        p.title AS package_title,
        p.destination AS package_destination
    FROM bookings b
    INNER JOIN users u
        ON b.user_id = u.id
    INNER JOIN packages p
        ON b.package_id = p.id
    WHERE b.id = ?
    LIMIT 1
");

$stmt->execute([$booking_id]);

$booking = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$booking) {
    header("Location: manage-bookings.php");
    exit;
}


$status = strtolower(
    trim($booking['status'] ?? 'pending')
);


if ($status === 'confirmed') {
    $status_text = 'Confirmed';
} elseif ($status === 'cancelled') {
    $status_text = 'Cancelled';
} else {
    $status_text = 'Pending';
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Booking Details | GlobeTrek Adventures
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<nav class="navbar">

    <div class="container navbar-content">

        <a
            href="dashboard.php"
            class="logo"
        >
            GlobeTrek
        </a>


        <ul class="nav-links">

            <li>
                <a href="dashboard.php">
                    Home
                </a>
            </li>

            <li>
                <a href="../packages.php">
                    Packages
                </a>
            </li>

            <li>
                <a href="../manage-packages.php">
                    Manage Packages
                </a>
            </li>

            <li>
                <a href="manage-bookings.php">
                    Manage Bookings
                </a>
            </li>

            <li>
                <a href="../logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">


        <div class="section-title">

            <h1>
                Booking Details
            </h1>

            <p>
                View customer booking information.
            </p>

        </div>


        <div
            class="form-container"
            style="
                max-width: 700px;
                margin: 0 auto;
            "
        >


            <h2>
                Booking #<?php echo (int) $booking['id']; ?>
            </h2>


            <hr>


            <p>
                <strong>Customer Name:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['customer_name']
                );
                ?>
            </p>


            <p>
                <strong>Customer Email:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['customer_email']
                );
                ?>
            </p>


            <p>
                <strong>Package:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['package_title']
                );
                ?>
            </p>


            <p>
                <strong>Destination:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['package_destination']
                );
                ?>
            </p>


            <p>
                <strong>Travel Date:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['travel_date']
                );
                ?>
            </p>


            <p>
                <strong>Number of Travellers:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['travelers']
                );
                ?>
            </p>


            <p>
                <strong>Total Price:</strong><br>

                LKR
                <?php
                echo number_format(
                    $booking['total_price'],
                    2
                );
                ?>
            </p>


            <p>
                <strong>Status:</strong><br>

                <?php
                echo htmlspecialchars(
                    $status_text
                );
                ?>
            </p>


            <p>
                <strong>Booking Created:</strong><br>

                <?php
                echo htmlspecialchars(
                    $booking['created_at']
                );
                ?>
            </p>


            <br>


            <div
                style="
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                "
            >

                <a
                    href="manage-bookings.php"
                    class="btn btn-secondary"
                >
                    Back to Bookings
                </a>


                <a
                    href="dashboard.php"
                    class="btn btn-primary"
                >
                    Dashboard
                </a>

            </div>


        </div>


    </div>

</section>


<footer class="footer">

    <div class="container">

        <p>

            &copy;
            <?php echo date("Y"); ?>
            GlobeTrek Adventures.
            All Rights Reserved.

        </p>

    </div>

</footer>


</body>

</html>