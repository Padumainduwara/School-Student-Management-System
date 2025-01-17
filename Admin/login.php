
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your CSS styling here */
    </style>
    <script>
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = message; // Set the message
            messageDiv.className = `message ${type}`; // Set the class based on message type
            messageDiv.style.display = 'block'; // Show the message
        }
    </script>
</head>
<body>
    <div class="container">
        <form class="register-form" method="POST" action="">
            <h2>Admin Login</h2>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit" name="submit">Login</button> <br>
            <a href="register.php" class="register-link">Don't have an account? Register now</a>
        </form>
        <div id="message" class="message"></div> <!-- Message div -->
    </div>

    <?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

// PHP code to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $username = $_POST['username'];
    $password = $_POST['password'];  // Assuming plain text password for simplicity

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid login
        $_SESSION['admin_logged_in'] = true; // Set session or other actions if needed
        echo "<script>showMessage('Login successful! Redirecting to dashboard...', 'success');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'dashboard.php'; }, 1500);</script>";
    } else {
        // Invalid login
        echo "<script>showMessage('Invalid username or password. Please try again.', 'error');</script>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>


</body>
</html>
