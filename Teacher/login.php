<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    // Check if username exists
    $check_user_query = "SELECT * FROM teacher WHERE username = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username found, check password
        $row = $result->fetch_assoc();
        if (password_verify($login_password, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $row['id']; // Save user id in session
            $_SESSION['username'] = $row['username']; // Save username in session

            echo "<script>
                alert('Login successful!');
                window.location.href = 'dashboard.php'; // Redirect to dashboard or desired page
            </script>";
        } else {
            // Password is incorrect
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        // Username does not exist
        echo "<script>alert('Username does not exist. Please register.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS -->
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Teacher Login</h2>
            <form method="post" action="login.php">
                <label for="login_username">Username</label>
                <input type="text" id="login_username" name="login_username" placeholder="Enter your username" required>

                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="login_password" placeholder="Enter your password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

