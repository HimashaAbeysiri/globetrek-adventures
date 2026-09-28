<?php
session_start();
require_once "config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tester_name = trim($_POST['tester_name'] ?? '');
    $rating = (int) ($_POST['rating'] ?? 0);
    $comments = trim($_POST['comments'] ?? '');

    if (empty($tester_name) || $rating < 1 || $rating > 5) {

        $message = "Please enter your name and a rating between 1 and 5.";
        $message_type = "error";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO feedback (tester_name, rating, comments)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$tester_name, $rating, $comments]);

        $message = "Thank you for your feedback!";
        $message_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback | GlobeTrek Adventures</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="logo">GlobeTrek</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="packages.php">Packages</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>
</nav>

<section class="section">
    <div class="container">
        <div class="form-container">
            <h1>Test User Feedback</h1>
            <p>Help us improve GlobeTrek Adventures by sharing your experience.</p>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="tester_name">Your Name</label>
                    <input type="text" id="tester_name" name="tester_name" required>
                </div>

                <div class="form-group">
                    <label for="rating">Ease of Booking (1 = Difficult, 5 = Very Easy)</label>
                    <input type="number" id="rating" name="rating" min="1" max="5" required>
                </div>

                <div class="form-group">
                    <label for="comments">Comments / Suggestions</label>
                    <textarea id="comments" name="comments" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> GlobeTrek Adventures. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>