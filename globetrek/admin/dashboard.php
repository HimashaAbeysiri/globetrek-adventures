<?php
session_start();
require_once "../config/database.php";

/* Admin access only */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

/* Get statistics */

$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users
    WHERE role = 'customer'
")->fetchColumn();

$totalStaff = $pdo->query("
    SELECT COUNT(*) FROM users
    WHERE role = 'staff'
")->fetchColumn();

$totalPackages = $pdo->query("
    SELECT COUNT(*) FROM packages
")->fetchColumn();

$totalBookings = $pdo->query("
    SELECT COUNT(*) FROM bookings
")->fetchColumn();

$totalQueries = $pdo->query("
    SELECT COUNT(*) FROM queries
")->fetchColumn();


/* Get recent bookings */

$stmt = $pdo->query("
    SELECT
        bookings.id,
        users.name AS customer_name,
        packages.title AS package_title,
        bookings.travel_date,
        bookings.travelers,
        bookings.total_price,
        bookings.status
    FROM bookings
    INNER JOIN users
        ON bookings.user_id = users.id
    INNER JOIN packages
        ON bookings.package_id = packages.id
    ORDER BY bookings.id DESC
    LIMIT 10
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* Get recent queries */

$stmt = $pdo->query("
    SELECT
        id,
        name,
        email,
        message,
        status,
        created_at
    FROM queries
    ORDER BY id DESC
    LIMIT 10
");

$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        Admin Dashboard | GlobeTrek Adventures
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin: 30px 0;
        }

        .admin-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .admin-card h3 {
            margin-bottom: 10px;
        }

        .admin-number {
            font-size: 32px;
            font-weight: bold;
        }

        .admin-section {
            margin-top: 40px;
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .admin-table th {
            font-weight: bold;
        }

        .status {
            font-weight: bold;
        }

        @media (max-width: 900px) {

            .admin-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .admin-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


<nav class="navbar">

    <div class="container navbar-content">

        <a
            href="../index.php"
            class="logo"
        >
            GlobeTrek
        </a>


        <ul class="nav-links">

            <li>
                <a href="../index.php">
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
                <a href="../logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">


        <h1>
            Admin Dashboard
        </h1>

        <p>
            Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.
            Manage and monitor GlobeTrek Adventures.
        </p>


        <!-- Statistics -->

        <div class="admin-grid">


            <div class="admin-card">

                <h3>
                    Customers
                </h3>

                <div class="admin-number">
                    <?php echo $totalUsers; ?>
                </div>

            </div>


            <div class="admin-card">

                <h3>
                    Staff
                </h3>

                <div class="admin-number">
                    <?php echo $totalStaff; ?>
                </div>

            </div>


            <div class="admin-card">

                <h3>
                    Packages
                </h3>

                <div class="admin-number">
                    <?php echo $totalPackages; ?>
                </div>

            </div>


            <div class="admin-card">

                <h3>
                    Bookings
                </h3>

                <div class="admin-number">
                    <?php echo $totalBookings; ?>
                </div>

            </div>


            <div class="admin-card">

                <h3>
                    Queries
                </h3>

                <div class="admin-number">
                    <?php echo $totalQueries; ?>
                </div>

            </div>


        </div>


        <!-- Recent Bookings -->

        <div class="admin-section">

            <h2>
                Recent Bookings
            </h2>


            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            Customer
                        </th>

                        <th>
                            Package
                        </th>

                        <th>
                            Travel Date
                        </th>

                        <th>
                            Travellers
                        </th>

                        <th>
                            Total Price
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (empty($bookings)): ?>

                        <tr>

                            <td colspan="6">
                                No bookings found.
                            </td>

                        </tr>

                    <?php else: ?>


                        <?php foreach ($bookings as $booking): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking['customer_name']
                                    );
                                    ?>
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

                                <td class="status">
                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $booking['status']
                                        )
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


        <!-- Customer Queries -->

        <div class="admin-section">

            <h2>
                Recent Customer Queries
            </h2>


            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Message
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (empty($queries)): ?>

                        <tr>

                            <td colspan="4">
                                No customer queries found.
                            </td>

                        </tr>

                    <?php else: ?>


                        <?php foreach ($queries as $query): ?>

                            <tr>

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

                                <td class="status">
                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $query['status']
                                        )
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php endif; ?>


                </tbody>

            </table>

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