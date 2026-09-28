<?php
require_once "config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GlobeTrek Adventures | Explore Sri Lanka</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- Navigation -->
<nav class="navbar">
    <div class="container navbar-content">

        <a href="index.php" class="logo">
            GlobeTrek
        </a>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="packages.php">Packages</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>

    </div>
</nav>


<!-- Hero Section -->
<section class="hero">

    <div class="container">

        <div class="hero-content">

            <h1>Discover Sri Lanka With GlobeTrek</h1>

            <p>
                Explore breathtaking destinations, unforgettable experiences,
                and carefully designed travel packages across Sri Lanka.
            </p>

            <a href="packages.php" class="btn btn-secondary">
                Explore Packages
            </a>

        </div>

    </div>

</section>


<!-- Welcome Section -->
<section class="section">

    <div class="container">

        <div class="section-title">

            <h2>Why Travel With GlobeTrek?</h2>

            <p>
                Your journey, our passion.
            </p>

        </div>


        <div class="package-grid">

            <div class="package-card">

                <div class="package-content">

                    <h3>Beautiful Destinations</h3>

                    <p>
                        Discover beaches, mountains, wildlife,
                        culture and unforgettable Sri Lankan landscapes.
                    </p>

                </div>

            </div>


            <div class="package-card">

                <div class="package-content">

                    <h3>Flexible Packages</h3>

                    <p>
                        Choose travel packages designed for
                        different interests, budgets and travel styles.
                    </p>

                </div>

            </div>


            <div class="package-card">

                <div class="package-content">

                    <h3>Easy Booking</h3>

                    <p>
                        Browse packages, select your travel date,
                        choose the number of travelers and submit your booking.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- Featured Packages -->
<section class="section">

    <div class="container">

        <div class="section-title">

            <h2>Featured Travel Packages</h2>

            <p>
                Explore some of our popular Sri Lankan experiences.
            </p>

        </div>


        <div class="package-grid">

            <?php

            $stmt = $pdo->query("
                SELECT *
                FROM packages
                ORDER BY created_at DESC
                LIMIT 3
            ");

            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($packages) > 0):

                foreach ($packages as $package):

            ?>

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
                                <?php echo htmlspecialchars($package['destination']); ?>
                            </p>

                            <div class="package-price">
                                LKR <?php echo number_format($package['price'], 2); ?>
                            </div>

                            <a
                                href="package-details.php?id=<?php echo $package['id']; ?>"
                                class="btn btn-primary"
                            >
                                View Details
                            </a>

                        </div>

                    </div>

            <?php

                endforeach;

            else:

            ?>

                <p style="text-align:center;">
                    Travel packages will be available soon.
                </p>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- ============================= -->
<!-- About Us / Why Choose Us (from wireframe) -->
<!-- ============================= -->
<section class="section">

    <div class="container">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

            <!-- About Us -->
            <div style="background: var(--light); padding: 30px; border-radius: 12px;">

                <h2 style="color: var(--dark); margin-bottom: 15px;">
                    About Us
                </h2>

                <p style="color: var(--muted);">
                    GlobeTrek Adventures is a newly established travel and tourism
                    company based in Negombo, Sri Lanka, helping travelers plan
                    unforgettable trips with transparent pricing and dedicated support.
                </p>

            </div>

            <!-- Why Choose Us -->
            <div style="background: var(--light); padding: 30px; border-radius: 12px;">

                <h2 style="color: var(--dark); margin-bottom: 15px;">
                    Why Choose Us
                </h2>

                <ul style="color: var(--muted); padding-left: 20px; line-height: 1.9;">
                    <li>Curated local tour packages with transparent pricing</li>
                    <li>Secure online booking and account management</li>
                    <li>Dedicated staff to coordinate hotels and transport</li>
                    <li>Fast responses to travel queries</li>
                </ul>

            </div>

        </div>

    </div>

</section>


<!-- Call to Action -->
<section class="section">

    <div class="container">

        <div class="section-title">

            <h2>Ready For Your Next Adventure?</h2>

            <p>
                Explore our travel packages and start planning your journey.
            </p>

            <br>

            <a href="packages.php" class="btn btn-primary">
                View All Packages
            </a>

        </div>

    </div>

</section>


<!-- Footer -->
<footer class="footer">

    <div class="container">

        <p>
            &copy; <?php echo date("Y"); ?> GlobeTrek Adventures, Negombo, Sri Lanka.
            All rights reserved.
        </p>

        <p>
            Email: info@globetrek.lk | Phone: +94 77 123 4567
        </p>

    </div>

</footer>

</body>
</html>
