<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $first_appointment = $_POST['first_appointment'];
    $current_school_appointment = $_POST['current_school_appointment'];
    $time_in_school = $_POST['time_in_school'];
    $appointment_type = $_POST['appointment_type'];
    $subject = $_POST['subject'];
    $professional_qualifications = $_POST['professional_qualifications'];
    $educational_qualifications = $_POST['educational_qualifications'];

    // Check if username already exists
    $check_username_query = "SELECT * FROM teacher WHERE username = ?";
    $stmt = $conn->prepare($check_username_query);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        echo "<script>alert('Username already exists. Please choose a different one.');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Insert new teacher data into the database
        $insert_query = "INSERT INTO teacher (full_name, username, password, address, phone, dob, age, first_appointment, current_school_appointment, time_in_school, appointment_type, subject, professional_qualifications, educational_qualifications) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param(
            "ssssssssssssss",
            $full_name,
            $user_name,
            $hashed_password,
            $address,
            $phone,
            $dob,
            $age,
            $first_appointment,
            $current_school_appointment,
            $time_in_school,
            $appointment_type,
            $subject,
            $professional_qualifications,
            $educational_qualifications
        );

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error occurred during registration. Please try again.');</script>";
        }
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
    <title>Teacher Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS -->
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h2>Teacher Register Form</h2>
            <form id="appointmentForm" method="post" action="register.php"> <!-- Changed action to register.php -->
                <div class="form-group">
                    <div class="column">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>

                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter a username" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter a password" required>

                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter your address" required></textarea>

                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>

                        <label for="age">Age</label>
                        <input type="text" id="age" name="age" placeholder="Your age will be calculated" readonly>
                    </div>

                    <div class="column">
                        <label for="first_appointment">Date of First Appointment</label>
                        <input type="date" id="first_appointment" name="first_appointment" required>

                        <label for="current_school_appointment">Date of Appointment to Current School</label>
                        <input type="date" id="current_school_appointment" name="current_school_appointment" required>

                        <label for="time_in_school">Time in Current School (Years, Months)</label>
                        <input type="text" id="time_in_school" name="time_in_school" placeholder="Automatically calculated" readonly>

                        <label for="appointment_type">Appointment Type</label>
                        <select id="appointment_type" name="appointment_type" required>
                            <option value="Graduate">Graduate</option>
                            <option value="Training">Training</option>
                        </select>

                        <label for="subject">Subject</label>
                        <textarea id="subject" name="subject" rows="3" placeholder="Enter the subject you teach" required></textarea>

                        <label for="professional_qualifications">Professional Qualifications</label>
                        <textarea id="professional_qualifications" name="professional_qualifications" rows="3" placeholder="Enter your professional qualifications" required></textarea>

                        <label for="educational_qualifications">Educational Qualifications</label>
                        <textarea id="educational_qualifications" name="educational_qualifications" rows="3" placeholder="Enter your educational qualifications" required></textarea>
                    </div>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('dob').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const month = today.getMonth() - dob.getMonth();

            if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });

        document.getElementById('current_school_appointment').addEventListener('change', function() {
            const schoolDate = new Date(this.value);
            const today = new Date();
            const yearsInSchool = today.getFullYear() - schoolDate.getFullYear();
            const monthsInSchool = today.getMonth() - schoolDate.getMonth() + (yearsInSchool * 12);

            let years = Math.floor(monthsInSchool / 12);
            let months = monthsInSchool % 12;

            document.getElementById('time_in_school').value = `${years} years, ${months} months`;
        });
    </script>
</body>
</html>
