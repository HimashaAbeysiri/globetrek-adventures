<?php
session_start();
require_once "config/database.php";

/* Staff/Admin access only */
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['staff', 'admin'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$message_type = "";

/* Delete package */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {

    $delete_id = (int) $_POST['delete_id'];

    try {

        /* Get image filename before deleting */
        $stmt = $pdo->prepare("SELECT image FROM packages WHERE id = ?");
        $stmt->execute([$delete_id]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        /* Delete package */
        $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
        $stmt->execute([$delete_id]);

        /* Delete uploaded image if it exists */
        if ($package && !empty($package['image'])) {

            $image_path = __DIR__ . "/images/" . $package['image'];

            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $message = "Package deleted successfully.";
        $message_type = "success";

    } catch (PDOException $e) {

        $message = "This package cannot be deleted because it may have existing bookings.";
        $message_type = "error";
    }
}


/* Add package */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_package'])) {

    $title = trim($_POST['title'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $duration = trim($_POST['duration'] ?? '');

    $image_name = "";


    /* Validate package details */

    if (
        empty($title) ||
        empty($destination) ||
        empty($description) ||
        $price <= 0 ||
        empty($duration)
    ) {

        $message = "Please fill in all package details correctly.";
        $message_type = "error";

    } else {

        /* Image upload validation */

        if (
            !isset($_FILES['image']) ||
            $_FILES['image']['error'] !== UPLOAD_ERR_OK
        ) {

            $message = "Please select a package image.";
            $message_type = "error";

        } else {

            $image = $_FILES['image'];

            /* Maximum file size: 5MB */

            if ($image['size'] > 5 * 1024 * 1024) {

                $message = "Image size must be less than 5 MB.";
                $message_type = "error";

            } else {

                /* Allowed image types */

                $allowed_types = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];

                $file_type = mime_content_type($image['tmp_name']);

                if (!array_key_exists($file_type, $allowed_types)) {

                    $message = "Only JPG, PNG and WEBP images are allowed.";
                    $message_type = "error";

                } else {

                    /* Create unique image filename */

                    $extension = $allowed_types[$file_type];

                    $image_name =
                        strtolower(
                            preg_replace(
                                '/[^a-zA-Z0-9]+/',
                                '-',
                                $title
                            )
                        )
                        . "-"
                        . time()
                        . "."
                        . $extension;


                    /* Image upload directory */

                    $upload_directory = __DIR__ . "/images/";

                    /* Create directory if it does not exist */

                    if (!is_dir($upload_directory)) {
                        mkdir($upload_directory, 0755, true);
                    }


                    /* Move uploaded image */

                    if (
                        move_uploaded_file(
                            $image['tmp_name'],
                            $upload_directory . $image_name
                        )
                    ) {

                        /* Insert package into database */

                        $stmt = $pdo->prepare("
                            INSERT INTO packages
                            (
                                title,
                                destination,
                                description,
                                price,
                                duration,
                                image,
                                created_by
                            )
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ");

                        $stmt->execute([
                            $title,
                            $destination,
                            $description,
                            $price,
                            $duration,
                            $image_name,
                            $_SESSION['user_id']
                        ]);

                        $message = "Package added successfully.";
                        $message_type = "success";

                    } else {

                        $message = "Failed to upload the package image.";
                        $message_type = "error";
                    }
                }
            }
        }
    }
}


/* Get all packages */

$stmt = $pdo->query("
    SELECT packages.*, users.name AS creator_name
    FROM packages
    LEFT JOIN users ON packages.created_by = users.id
    ORDER BY packages.id DESC
");

$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        Manage Packages | GlobeTrek Adventures
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body>


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
                <a href="staff/dashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </div>

</nav>


<section class="section">

    <div class="container">


        <h1>
            Manage Travel Packages
        </h1>


        <p>
            Add and manage GlobeTrek Adventures travel packages.
        </p>


        <?php if (!empty($message)): ?>

            <div class="alert alert-<?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- Add Package -->

        <div class="form-container">

            <h2>
                Add New Package
            </h2>


            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <div class="form-group">

                    <label for="title">
                        Package Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Example: Galle Fort Heritage Walk"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="destination">
                        Destination
                    </label>

                    <input
                        type="text"
                        id="destination"
                        name="destination"
                        placeholder="Example: Galle, Sri Lanka"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="duration">
                        Duration
                    </label>

                    <input
                        type="text"
                        id="duration"
                        name="duration"
                        placeholder="Example: 2 Days / 1 Night"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="price">
                        Price per Traveller (LKR)
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        min="1"
                        step="0.01"
                        placeholder="45000"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Enter package description..."
                        required
                    ></textarea>

                </div>


                <!-- Package Image -->

                <div class="form-group">

                    <label for="image">
                        Package Image
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >

                    <small>
                        Allowed formats: JPG, PNG, WEBP.
                        Maximum size: 5 MB.
                    </small>

                </div>


                <button
                    type="submit"
                    name="add_package"
                    class="btn btn-primary"
                >
                    Add Package
                </button>


            </form>

        </div>


        <!-- Existing Packages -->

        <h2 style="margin-top: 40px;">

            Existing Packages

        </h2>


        <div class="package-grid">


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


                        <p>

                            <?php
                            echo htmlspecialchars(
                                $package['description']
                            );
                            ?>

                        </p>


                        <form
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this package?');"
                        >

                            <input
                                type="hidden"
                                name="delete_id"
                                value="<?php echo $package['id']; ?>"
                            >


                            <button
                                type="submit"
                                class="btn btn-secondary"
                            >
                                Delete Package
                            </button>

                        </form>


                    </div>

                </div>


            <?php endforeach; ?>


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