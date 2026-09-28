<?php
require_once "config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid package.");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$id]);

$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("Package not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($package['title']); ?> |
        GlobeTrek Adventures
    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">

    <div class="container navbar-content">

        <a href="index.php" class="logo">
            GlobeTrek
        </a>

        <ul class="nav-links">

            <li>
                <a href="index.php">Home</a>
            </li>

            <li>
                <a href="packages.php">Packages</a>
            </li>

            <li>
                <a href="contact.php">Contact</a>
            </li>

            <li>
                <a href="login.php">Login</a>
            </li>

            <li>
                <a href="register.php">Register</a>
            </li>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">

        <div class="package-details">

            <?php if (!empty($package['image'])): ?>

                <img
                    src="images/<?php echo htmlspecialchars($package['image']); ?>"
                    alt="<?php echo htmlspecialchars($package['title']); ?>"
                >

            <?php endif; ?>


            <div class="package-details-content">

                <h1>
                    <?php echo htmlspecialchars($package['title']); ?>
                </h1>

                <h3>
                    📍 <?php echo htmlspecialchars($package['destination']); ?>
                </h3>

                <p>
                    🕐
                    <strong>Duration:</strong>
                    <?php echo htmlspecialchars($package['duration']); ?>
                </p>

                <p>
                    <?php echo nl2br(htmlspecialchars($package['description'])); ?>
                </p>

                <div class="package-price">

                    LKR
                    <?php echo number_format($package['price'], 2); ?>

                    <small>per traveller</small>

                </div>

                <br>

                <a
                    href="booking.php?package_id=<?php echo $package['id']; ?>"
                    class="btn btn-primary"
                >
                    Book This Package
                </a>

                <a
                    href="packages.php"
                    class="btn btn-secondary"
                >
                    Back to Packages
                </a>

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