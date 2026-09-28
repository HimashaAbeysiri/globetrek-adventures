<?php
session_start();
require_once "config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";
        $message_type = "error";

    } else {

        $stmt = $pdo->prepare("
            SELECT id, name, email, password, role
            FROM users
            WHERE email = ?
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
                exit;
            }

            if ($user['role'] === 'staff') {
                header("Location: staff/dashboard.php");
                exit;
            }

            header("Location: customer/dashboard.php");
            exit;

        } else {

            $message = "Invalid email or password.";
            $message_type = "error";
        }
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

    <title>Login | GlobeTrek Adventures</title>

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

        <div class="form-container">

            <h1>Welcome Back</h1>

            <p>
                Login to your GlobeTrek Adventures account.
            </p>

            <?php if (!empty($message)): ?>

                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Login
                </button>

            </form>


            <p style="margin-top: 20px;">

                Don't have an account?

                <a href="register.php">
                    Register here
                </a>

            </p>

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