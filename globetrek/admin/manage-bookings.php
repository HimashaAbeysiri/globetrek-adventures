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


// Update booking status
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status'])) {

    $booking_id = isset($_POST['booking_id'])
        ? (int) $_POST['booking_id']
        : 0;

    $status = isset($_POST['status'])
        ? strtolower(trim($_POST['status']))
        : '';

    $allowed_statuses = [
        'pending',
        'confirmed',
        'cancelled'
    ];

    if (
        $booking_id > 0 &&
        in_array($status, $allowed_statuses, true)
    ) {

        $stmt = $pdo->prepare("
            UPDATE bookings
            SET status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $status,
            $booking_id
        ]);
    }

    header("Location: manage-bookings.php");
    exit;
}


// Get all bookings
$stmt = $pdo->query("
    SELECT
        b.id,
        b.travel_date,
        b.travelers,
        b.total_price,
        b.status,
        b.created_at,
        u.name AS customer_name,
        u.email AS customer_email,
        p.title AS package_title
    FROM bookings b
    INNER JOIN users u
        ON b.user_id = u.id
    INNER JOIN packages p
        ON b.package_id = p.id
    ORDER BY b.id DESC
");

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

    <title>
        Manage Bookings | GlobeTrek Adventures
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<!-- Navigation -->

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



<!-- Manage Bookings -->

<section class="section">

    <div class="container">


        <div class="section-title">

            <h2>
                Manage Bookings
            </h2>

            <p>
                View and update customer booking status.
            </p>

        </div>



        <?php if (empty($bookings)): ?>


            <div class="form-container">

                <p>
                    No bookings found.
                </p>

            </div>


        <?php else: ?>


            <div style="overflow-x: auto;">

                <table
                    style="
                        width: 100%;
                        border-collapse: collapse;
                        background: #ffffff;
                        border-radius: 10px;
                        overflow: hidden;
                    "
                >


                    <thead>

                        <tr>

                            <th style="padding: 12px; text-align: left;">
                                ID
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Customer
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Email
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Package
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Travel Date
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Travellers
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Total Price
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Status
                            </th>

                            <th style="padding: 12px; text-align: left;">
                                Action
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <?php foreach ($bookings as $booking): ?>


                            <tr>


                                <!-- Booking ID -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['id']
                                    );
                                    ?>

                                </td>



                                <!-- Customer -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['customer_name']
                                    );
                                    ?>

                                </td>



                                <!-- Email -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['customer_email']
                                    );
                                    ?>

                                </td>



                                <!-- Package -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['package_title']
                                    );
                                    ?>

                                </td>



                                <!-- Travel Date -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['travel_date']
                                    );
                                    ?>

                                </td>



                                <!-- Travellers -->

                                <td style="padding: 12px;">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking['travelers']
                                    );
                                    ?>

                                </td>



                                <!-- Total Price -->

                                <td style="padding: 12px;">

                                    LKR

                                    <?php
                                    echo number_format(
                                        $booking['total_price'],
                                        2
                                    );
                                    ?>

                                </td>



                                <!-- Status -->

                                <td style="padding: 12px;">

                                    <form
                                        method="POST"
                                        action="manage-bookings.php"
                                        style="
                                            display: flex;
                                            align-items: center;
                                            gap: 8px;
                                        "
                                    >

                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?php
                                                echo htmlspecialchars(
                                                    $booking['id']
                                                );
                                            ?>"
                                        >


                                        <select
                                            name="status"
                                            style="
                                                padding: 8px;
                                                border: 1px solid #ccc;
                                                border-radius: 5px;
                                                min-width: 110px;
                                            "
                                        >


                                            <option
                                                value="pending"
                                                <?php

                                                if (
                                                    strtolower(
                                                        $booking['status']
                                                    ) === 'pending'
                                                ) {
                                                    echo 'selected';
                                                }

                                                ?>
                                            >
                                                Pending
                                            </option>



                                            <option
                                                value="confirmed"
                                                <?php

                                                if (
                                                    strtolower(
                                                        $booking['status']
                                                    ) === 'confirmed'
                                                ) {
                                                    echo 'selected';
                                                }

                                                ?>
                                            >
                                                Confirmed
                                            </option>



                                            <option
                                                value="cancelled"
                                                <?php

                                                if (
                                                    strtolower(
                                                        $booking['status']
                                                    ) === 'cancelled'
                                                ) {
                                                    echo 'selected';
                                                }

                                                ?>
                                            >
                                                Cancelled
                                            </option>


                                        </select>



                                        <button
                                            type="submit"
                                            name="update_status"
                                            class="btn btn-primary"
                                            style="
                                                width: 100px;
                                                min-width: 100px;
                                                height: 38px;
                                                min-height: 38px;
                                                padding: 0;
                                                margin: 0;
                                            "
                                        >
                                            Update
                                        </button>


                                    </form>

                                </td>



                                <!-- Actions -->

                                <td style="padding: 12px;">

                                    <div
                                        style="
                                            display: flex;
                                            gap: 8px;
                                            align-items: center;
                                        "
                                    >


                                        <!-- View Button -->

                                        <a
                                            href="booking-details.php?id=<?php echo (int) $booking['id']; ?>"
                                            class="btn btn-secondary"
                                            style="
                                                width: 80px;
                                                min-width: 80px;
                                                height: 38px;
                                                min-height: 38px;
                                                padding: 0;
                                                margin: 0;
                                            "
                                        >
                                            View
                                        </a>



                                        <!-- Delete Button -->

                                        <a
                                            href="delete-booking.php?id=<?php echo (int) $booking['id']; ?>"
                                            class="btn btn-primary"
                                            style="
                                                width: 80px;
                                                min-width: 80px;
                                                height: 38px;
                                                min-height: 38px;
                                                padding: 0;
                                                margin: 0;
                                                background: #dc3545;
                                                color: #ffffff;
                                            "
                                            onclick="return confirm('Are you sure you want to delete this booking?');"
                                        >
                                            Delete
                                        </a>


                                    </div>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    </tbody>


                </table>

            </div>


        <?php endif; ?>


    </div>

</section>



<!-- Footer -->

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