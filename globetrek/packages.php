<?php
require_once "config/database.php";

$search = $_GET['search'] ?? '';
$destination = $_GET['destination'] ?? '';

$sql = "SELECT * FROM packages WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR destination LIKE ? OR description LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($destination)) {
    $sql .= " AND destination = ?";
    $params[] = $destination;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$destinationsStmt = $pdo->query(
    "SELECT DISTINCT destination FROM packages ORDER BY destination"
);
$destinations = $destinationsStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Travel Packages | GlobeTrek Adventures</title>

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

        <div class="section-title">
            <h1>Explore Our Travel Packages</h1>

            <p>
                Discover exciting destinations and experiences
                across Sri Lanka.
            </p>
        </div>

        <!-- Search and Filter -->

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search packages..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="destination">

                <option value="">
                    All Destinations
                </option>

                <?php foreach ($destinations as $item): ?>

                    <option
                        value="<?php echo htmlspecialchars($item); ?>"
                        <?php echo ($destination === $item) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($item); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <button type="submit" class="btn btn-primary">
                Search
            </button>

        </form>


        <!-- Package Cards -->

        <div class="package-grid">

            <?php if (count($packages) > 0): ?>

                <?php foreach ($packages as $package): ?>

                    <div class="package-card">

                        <?php if (!empty($package['image'])): ?>

                            <img
                                src="images/<?php echo htmlspecialchars($package['image']); ?>"
                                alt="<?php echo htmlspecialchars($package['title']); ?>"
                            >

                        <?php endif; ?>


                        <div class="package-content">

                            <h3>
                                <?php echo htmlspecialchars($package['title']); ?>
                            </h3>

                            <p>
                                📍 <?php echo htmlspecialchars($package['destination']); ?>
                            </p>

                            <p>
                                🕐 <?php echo htmlspecialchars($package['duration']); ?>
                            </p>

                            <p>
                                <?php echo htmlspecialchars($package['description']); ?>
                            </p>

                            <div class="package-price">
                                LKR <?php echo number_format($package['price'], 2); ?>
                            </div>

                            <br>

                            <a
                                href="package-details.php?id=<?php echo $package['id']; ?>"
                                class="btn btn-primary"
                            >
                                View Details
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="alert alert-error">
                    No travel packages found.
                </div>

            <?php endif; ?>

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