<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
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

    <title>Customer Dashboard | GlobeTrek Adventures</title>

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

            <h1>
                Welcome,
                <?php echo htmlspecialchars($_SESSION['name']); ?>!
            </h1>

            <p>
                Welcome to your GlobeTrek Adventures dashboard.
            </p>

        </div>


        <div class="package-grid">

            <div class="package-card">

                <div class="package-content">

                    <h3>Explore Packages</h3>

                    <p>
                        Discover our travel packages
                        across Sri Lanka.
                    </p>

                    <a
                        href="../packages.php"
                        class="btn btn-primary"
                    >
                        View Packages
                    </a>

                </div>

            </div>


            <div class="package-card">

                <div class="package-content">

                    <h3>My Bookings</h3>

                    <p>
                        View and manage your travel bookings.
                    </p>

                    <a
                        href="my-bookings.php"
                        class="btn btn-primary"
                    >
                        My Bookings
                    </a>

                </div>

            </div>


            <div class="package-card">

                <div class="package-content">

                    <h3>Contact GlobeTrek</h3>

                    <p>
                        Send us a question or travel enquiry.
                    </p>

                    <a
                        href="../contact.php"
                        class="btn btn-primary"
                    >
                        Contact Us
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<footer class="footer">

    <div class="container">

        <p>
            &copy; <?php echo date("Y"); ?>
            GlobeTrek Adventures.
            All Rights Reserved.
        </p>

    </div>

</footer>

</body>

</html>