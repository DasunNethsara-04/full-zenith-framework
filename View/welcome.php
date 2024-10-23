<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZenithPHP</title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo "assets/images/ZenithPHP/web/icon-192.png"; ?>" sizes="any">
    <link rel="apple-touch-icon" href="<?php echo "assets/images/ZenithPHP/web/apple-touch-icon.png"; ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo "assets/css/styles.css" ?>">
</head>
<body>

<!-- Hero Section -->
<header id="hero">
    <div class="container">
        <img src="<?php echo "assets/images/ZenithPHP/web/icon-192.png"; ?>" alt="">
        <h1 class="mt-2 hero-heading">Welcome to ZenithPHP!</h1>
        <p class="lead">Your <i><?php echo APP_NAME; ?></i> has been successfully created.</p>
    </div>
</header>

<!-- How to Get Started Section -->
<section id="how-to-start">
    <div class="container">
        <h2>How to Get Started</h2>
        <p>To start building with <?php echo APP_NAME; ?>, follow the steps below:</p>
        <ul>
            <li><strong>Step 1:</strong> Review the <a
                        href="https://github.com/DasunNethsara-04/zenithphp?tab=readme-ov-file#readme">documentation</a>
                to familiarize yourself with the
                framework's structure.
            </li>
            <li><strong>Step 2:</strong> Configure your environment by editing the <code>.env</code> file and setting up
                your database connection.
            </li>
            <li><strong>Step 3:</strong> Create your first route in the <code>Init.php</code> file, and link it to a
                controller.
            </li>
            <li><strong>Step 4:</strong> Use the MVC pattern to create models, views, and controllers for your
                application.
            </li>
            <li><strong>Step 5:</strong> Test your application by running it locally (<code>php -S localhost:8000</code>)
                and making sure routes and views
                are working properly.
            </li>
        </ul>
        <a href="https://github.com/DasunNethsara-04/zenithphp?tab=readme-ov-file#readme" class="btn btn-primary">View
            Documentation</a>
    </div>
</section>

<!-- Main Content Section -->
<section id="content">
    <div class="container">
        <p>This framework is designed to make building web applications simple and efficient. Get started with
            controllers, routes, and models, and extend the framework to meet your application's needs.</p>
        <p>If you need more guidance, check the <a
                    href="https://github.com/DasunNethsara-04/zenithphp?tab=readme-ov-file#readme">documentation</a> or
            reach out to the community for help.
        </p>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> ZenithPHP <?php echo APP_VERSION; ?>. All rights reserved.</p>
        <p>Built with <a href="https://getbootstrap.com/" target="_blank">Bootstrap</a> | <a href="#">Privacy Policy</a>
            | <a href="#">Terms of Service</a></p>
    </div>
</footer>
<script src="<?php echo "assets/js/script.js" ?>"></script>
</body>
</html>
