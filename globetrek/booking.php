<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/database.php";


/* ==============================
   Check Package ID
   ============================== */

if (!isset($_GET['package_id']) || !is_numeric($_GET['package_id'])) {
    die("Invalid package.");
}

$package_id = (int) $_GET['package_id'];


/* ==============================
   Get Package
   ============================== */

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);

$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("Package not found.");
}


/* ==============================
   Booking Message
   ============================== */

$message = "";
$message_type = "";


/* ==============================
   Submit Booking
   ============================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $travel_date = $_POST['travel_date'] ?? '';
    $travelers = (int) ($_POST['travelers'] ?? 0);


    /* Validate travelers */

    if ($travelers < 1 || $travelers > 20) {

        $message = "Travelers must be between 1 and 20.";
        $message_type = "error";

    }


    /* Validate date */

    elseif (empty($travel_date)) {

        $message = "Please select a travel date.";
        $message_type = "error";

    }


    elseif ($travel_date < date('Y-m-d')) {

        $message = "Please select a future travel date.";
        $message_type = "error";

    }


    /* Check login */

    elseif (!isset($_SESSION['user_id'])) {

        $message = "Please login before making a booking.";
        $message_type = "error";

    }


    /* Save booking */

    else {

        $total_price = $package['price'] * $travelers;


        $stmt = $pdo->prepare("
            INSERT INTO bookings
            (
                user_id,
                package_id,
                travel_date,
                travelers,
                total_price
            )
            VALUES (?, ?, ?, ?, ?)
        ");


        $stmt->execute([
            $_SESSION['user_id'],
            $package_id,
            $travel_date,
            $travelers,
            $total_price
        ]);


        $message = "Booking submitted successfully!";
        $message_type = "success";
    }
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
        Book Package | GlobeTrek Adventures
    </title>


    <!-- Main CSS -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body>


<!-- ==============================
     Navigation
     ============================== -->

<nav class="navbar">

    <div class="container navbar-content">

        <a
            href="index.php"
            class="logo"
        >
            GlobeTrek
        </a>


        <ul class="nav-links">

            <li>
                <a href="index.php">
                    Home
                </a>
            </li>


            <li>
                <a href="packages.php">
                    Packages
                </a>
            </li>


            <li>
                <a href="contact.php">
                    Contact
                </a>
            </li>


            <?php if (isset($_SESSION['user_id'])): ?>

                <li>
                    <a href="customer/dashboard.php">
                        Dashboard
                    </a>
                </li>


                <li>
                    <a href="logout.php">
                        Logout
                    </a>
                </li>


            <?php else: ?>

                <li>
                    <a href="login.php">
                        Login
                    </a>
                </li>


                <li>
                    <a href="register.php">
                        Register
                    </a>
                </li>

            <?php endif; ?>

        </ul>

    </div>

</nav>



<!-- ==============================
     Booking Section
     ============================== -->

<section class="section">

    <div class="container">

        <div class="form-container">


            <h1>
                Book Your Adventure
            </h1>


            <p>
                <?php
                echo htmlspecialchars(
                    $package['title']
                );
                ?>
            </p>



            <!-- ==============================
                 Message
                 ============================== -->

            <?php if (!empty($message)): ?>

                <div
                    class="alert alert-<?php
                        echo htmlspecialchars(
                            $message_type
                        );
                    ?>"
                >

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>



            <!-- ==============================
                 Package Summary
                 ============================== -->

            <div class="package-summary">

                <h3>

                    <?php
                    echo htmlspecialchars(
                        $package['title']
                    );
                    ?>

                </h3>


                <p>

                    📍

                    <?php
                    echo htmlspecialchars(
                        $package['destination']
                    );
                    ?>

                </p>


                <p>

                    🕐

                    <?php
                    echo htmlspecialchars(
                        $package['duration']
                    );
                    ?>

                </p>


                <p class="package-price">

                    LKR

                    <?php
                    echo number_format(
                        $package['price'],
                        2
                    );
                    ?>

                    <small>
                        per traveller
                    </small>

                </p>

            </div>



            <!-- ==============================
                 Booking Form
                 ============================== -->

            <?php if (isset($_SESSION['user_id'])): ?>

                <form method="POST">


                    <!-- Travel Date -->

                    <div class="form-group">

                        <label for="travel_date">
                            Travel Date
                        </label>


                        <input
                            type="date"
                            id="travel_date"
                            name="travel_date"
                            min="<?php
                                echo date('Y-m-d');
                            ?>"
                            required
                        >

                    </div>



                    <!-- Number of Travellers -->

                    <div class="form-group">

                        <label for="travelers">
                            Number of Travellers
                        </label>


                        <input
                            type="number"
                            id="travelers"
                            name="travelers"
                            min="1"
                            max="20"
                            value="1"
                            required
                        >

                    </div>



                    <!-- Total Price -->

                    <div class="form-group">

                        <label>
                            Estimated Total
                        </label>


                        <p
                            class="package-price"
                            id="totalPrice"
                        >

                            LKR

                            <?php
                            echo number_format(
                                $package['price'],
                                2
                            );
                            ?>

                        </p>

                    </div>



                    <!-- ==============================
                         Buttons
                         ============================== -->

                    <div class="booking-buttons">


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Submit Booking
                        </button>


                        <a
                            href="package-details.php?id=<?php
                                echo $package['id'];
                            ?>"
                            class="btn btn-secondary"
                        >
                            Back
                        </a>


                    </div>


                </form>


            <?php else: ?>


                <!-- ==============================
                     Login Message
                     ============================== -->

                <div class="alert alert-error">

                    Please login before making a booking.

                    <br>
                    <br>


                    <a
                        href="login.php"
                        class="btn btn-primary"
                    >
                        Login
                    </a>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- ==============================
     Footer
     ============================== -->

<footer class="footer">

    <div class="container">

        <p>

            &copy;

            <?php
            echo date("Y");
            ?>

            GlobeTrek Adventures.
            All Rights Reserved.

        </p>

    </div>

</footer>



<!-- ==============================
     JavaScript
     ============================== -->

<script>

const travelersInput =
    document.getElementById("travelers");

const totalPrice =
    document.getElementById("totalPrice");


const packagePrice =
    <?php echo (float) $package['price']; ?>;


if (travelersInput && totalPrice) {

    travelersInput.addEventListener(
        "input",
        function () {

            let travelers =
                parseInt(this.value) || 1;


            if (travelers < 1) {
                travelers = 1;
            }


            if (travelers > 20) {
                travelers = 20;
            }


            const total =
                packagePrice * travelers;


            totalPrice.textContent =
                "LKR " +
                total.toLocaleString(
                    "en-LK",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );

        }
    );

}

</script>


</body>

</html>