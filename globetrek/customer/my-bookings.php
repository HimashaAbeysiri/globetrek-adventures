<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT
        bookings.id,
        packages.title,
        packages.destination,
        bookings.travel_date,
        bookings.travelers,
        bookings.total_price,
        bookings.status,
        bookings.created_at
    FROM bookings
    INNER JOIN packages
        ON bookings.package_id = packages.id
    WHERE bookings.user_id = ?
    ORDER BY bookings.created_at DESC
");

$stmt->execute([$user_id]);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Bookings | GlobeTrek Adventures</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="container navbar-content">

        <a href="../index.php" class="logo">
            GlobeTrek
        </a>

        <ul class="nav-links">

            <li>
                <a href="../index.php">Home</a>
            </li>

            <li>
                <a href="../packages.php">Packages</a>
            </li>

            <li>
                <a href="../contact.php">Contact</a>
            </li>

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="../logout.php">Logout</a>
            </li>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">

        <div class="section-title">

            <h1>My Bookings</h1>

            <p>
                View your GlobeTrek Adventures bookings.
            </p>

        </div>


        <?php if (count($bookings) > 0): ?>

            <div class="table-container">

                <table class="booking-table">

                    <thead>

                        <tr>

                            <th>Package</th>

                            <th>Destination</th>

                            <th>Travel Date</th>

                            <th>Travellers</th>

                            <th>Total Price</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($bookings as $booking): ?>

                            <?php

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


                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['title']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['destination']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['travel_date']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['travelers']
                                    );
                                    ?>
                                </td>


                                <td>

                                    LKR

                                    <?php
                                    echo number_format(
                                        $booking['total_price'],
                                        2
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $status_text
                                    );
                                    ?>

                                </td>


                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        <?php else: ?>

            <div class="alert alert-error">

                You don't have any bookings yet.

                <br><br>

                <a
                    href="../packages.php"
                    class="btn btn-primary"
                >
                    Explore Packages
                </a>

            </div>

        <?php endif; ?>

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