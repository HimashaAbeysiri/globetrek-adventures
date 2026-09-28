<?php
session_start();
require_once "config/database.php";

$message = "";
$message_type = "";

$name = "";
$email = "";

if (isset($_SESSION['user_id'])) {
    $name = $_SESSION['name'] ?? "";
    $email = $_SESSION['email'] ?? "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $user_message = trim($_POST['message'] ?? "");

    if (empty($name) || empty($email) || empty($user_message)) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($user_message) < 10) {

        $message = "Please enter a message with at least 10 characters.";
        $message_type = "error";

    } else {

        $user_id = $_SESSION['user_id'] ?? null;

        $stmt = $pdo->prepare("
            INSERT INTO queries
            (user_id, name, email, message)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $user_id,
            $name,
            $email,
            $user_message
        ]);

        $message = "Your query has been submitted successfully. We will get back to you soon.";
        $message_type = "success";

        $user_message = "";
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

    <title>Contact Us | GlobeTrek Adventures</title>

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
                    <a href="login.php">Login</a>
                </li>

                <li>
                    <a href="register.php">Register</a>
                </li>

            <?php endif; ?>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">

        <div class="section-title">

            <h1>Contact GlobeTrek Adventures</h1>

            <p>
                Have a question about our travel packages?
                Send us a message and our team will assist you.
            </p>

        </div>


        <div class="form-container">

            <?php if (!empty($message)): ?>

                <div class="alert alert-<?php echo $message_type; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?php echo htmlspecialchars($name); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="message">
                        Your Query
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="Enter your question or enquiry..."
                        required
                    ><?php echo htmlspecialchars($user_message ?? ""); ?></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Submit Query
                </button>

            </form>

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