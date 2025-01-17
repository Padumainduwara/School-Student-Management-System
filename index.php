<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="icon" href="./Images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="./Images/logo.png" alt="School Logo">
                <h1>St. Joseph's Girls' School</h1>
            </div>
            <div class="toggle" id="toggle">&#9776;</div> <!-- Hamburger menu -->
            <ul class="nav-links" id="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="./Admin/login.php">Admin Panel</a></li>
                <li><a href="./Teacher/register.php">Teachers Register</a></li>
            </ul>
        </div>
    </header>

    <!-- Landing Section -->
    <section class="landing">
        <div class="landing-content">
            <img src="./Images/logo.png" alt="School Logo" class="main-logo">
            <h1>Welcome to Our Student Management System!</h1>
            <p>Manage your school efficiently with our comprehensive system.</p>
            <a href="./Teacher/login.php" class="cta-btn">Teachers Login</a>
            <a href="./Admin/login.php" class="cta-btn">Admin Panel</a>
        </div>
    </section>

    <script>
        // Toggle navbar for mobile
        const toggle = document.getElementById('toggle');
        const navLinks = document.getElementById('nav-links');

        toggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
    </script>
</body>
</html>
