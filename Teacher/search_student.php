<?php
// search_student.php
include 'db_connection.php'; // Include your database connection file

if (isset($_POST['admission_no'])) {
    $admissionNo = $_POST['admission_no'];

    // Query to search student by admission number
    $query = "SELECT * FROM students WHERE admission_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $admissionNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo "<p><strong>Admission No:</strong> " . $student['admission_no'] . "</p>";
        echo "<p><strong>Name:</strong> " . $student['name'] . "</p>";
        echo "<p><strong>Grade:</strong> " . $student['grade'] . "</p>";
        echo "<p><strong>Address:</strong> " . $student['address'] . "</p>";
        echo "<p><strong>Phone:</strong> " . $student['phone'] . "</p>";
        echo "<p><strong>Email:</strong> " . $student['email'] . "</p>";
    } else {
        echo "<p>No student found with Admission No: " . htmlspecialchars($admissionNo) . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
