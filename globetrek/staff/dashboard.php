<?php
session_start();
require_once "../config/database.php";

// Only staff can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit;
}

// Get all bookings
$stmt = $pdo->query("
    SELECT
        bookings.id,
        users.name AS customer_name,
        users.email,
        packages.title AS package_title,
        packages.destination,
        bookings.travel_date,
        bookings.travelers,
        bookings.total_price,
        bookings.status,
        bookings.created_at
    FROM bookings
    INNER JOIN users ON bookings.user_id = users.id
    INNER JOIN packages ON bookings.package_id = packages.id
    ORDER BY bookings.created_at DESC
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all customer queries
$stmt = $pdo->query("
    SELECT
        queries.id,
        queries.name,
        queries.email,
        queries.message,
        queries.status,
        queries.created_at
    FROM queries
    ORDER BY queries.created_at DESC
");

$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff Dashboard - GlobeTrek Adventures</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="navbar">

    <div class="container nav-content">

        <h2>GlobeTrek Adventures</h2>

        <nav>

            <a href="../index.php">
                Home
            </a>

            <a href="../packages.php">
                Packages
            </a>

            <a href="../contact.php">
                Contact
            </a>

            <a href="../manage-packages.php">
                Manage Packages
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </nav>

    </div>

</header>


<section class="section">

    <div class="container">

        <h1>Staff Dashboard</h1>

        <p>
            Welcome,
            <strong>
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </strong>!
        </p>


        <!-- ============================= -->
        <!-- CUSTOMER BOOKINGS -->
        <!-- ============================= -->

        <h2 style="margin-top: 40px;">
            Customer Bookings
        </h2>

        <div class="table-container">

            <?php if (count($bookings) > 0): ?>

                <table class="booking-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Customer</th>

                            <th>Package</th>

                            <th>Destination</th>

                            <th>Travel Date</th>

                            <th>Travellers</th>

                            <th>Total Price</th>

                            <th>Status / Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($bookings as $booking): ?>

                        <tr>

                            <td>
                                <?php echo $booking['id']; ?>
                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking['customer_name']
                                );
                                ?>

                                <br>

                                <small>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['email']
                                    );
                                    ?>
                                </small>

                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $booking['package_title']
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
                                <?php echo $booking['travelers']; ?>
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

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['status']
                                    );
                                    ?>
                                </strong>


                                <?php if ($booking['status'] === 'pending'): ?>

                                    <!-- Confirm Booking -->

                                    <form
                                        action="update-booking.php"
                                        method="POST"
                                        style="margin-top: 8px;"
                                    >

                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?php
                                            echo $booking['id'];
                                            ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="confirmed"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                        >
                                            Confirm
                                        </button>

                                    </form>


                                    <!-- Cancel Booking -->

                                    <form
                                        action="update-booking.php"
                                        method="POST"
                                        style="margin-top: 5px;"
                                    >

                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?php
                                            echo $booking['id'];
                                            ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="cancelled"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                        >
                                            Cancel
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    No bookings found.
                </p>

            <?php endif; ?>

        </div>


        <!-- ============================= -->
        <!-- CUSTOMER QUERIES -->
        <!-- ============================= -->

        <h2 style="margin-top: 50px;">
            Customer Queries
        </h2>

        <div class="table-container">

            <?php if (count($queries) > 0): ?>

                <table class="booking-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Customer</th>

                            <th>Email</th>

                            <th>Message</th>

                            <th>Status / Action</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($queries as $query): ?>

                        <tr>

                            <td>
                                <?php echo $query['id']; ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $query['name']
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $query['email']
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $query['message']
                                );
                                ?>
                            </td>


                            <td>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $query['status']
                                    );
                                    ?>
                                </strong>


                                <?php if ($query['status'] === 'new'): ?>

                                    <form
                                        action="update-query.php"
                                        method="POST"
                                        style="margin-top: 8px;"
                                    >

                                        <input
                                            type="hidden"
                                            name="query_id"
                                            value="<?php
                                            echo $query['id'];
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn"
                                        >
                                            Mark as Responded
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $query['created_at']
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    No customer queries found.
                </p>

            <?php endif; ?>

        </div>

    </div>

</section>


<footer class="footer">

    <div class="container">

        <p>
            © 2026 GlobeTrek Adventures.
            All rights reserved.
        </p>

    </div>

</footer>

</body>

</html>