<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

// Initialize the $student variable and other variables
$student = null;
$errorMessage = '';
$successMessage = '';
$errorMessageManage = '';
$successMessageManage = '';

// Handle adding a student (if the add student form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admissionNo']) && !isset($_POST['updateStudent'])) {
    // Collect form data and escape values
    $admissionNo = mysqli_real_escape_string($conn, $_POST['admissionNo']);
    $nameWithInitials = mysqli_real_escape_string($conn, $_POST['nameWithInitials']);
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $contactNumber = mysqli_real_escape_string($conn, $_POST['contactNumber']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $medium = mysqli_real_escape_string($conn, $_POST['medium']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $extraCurricular = mysqli_real_escape_string($conn, $_POST['extraCurricular']);
    $achievements = mysqli_real_escape_string($conn, $_POST['achievements']);

    // Insert into the studentsdetails table
    $sql = "INSERT INTO studentsdetails (admissionNo, nameWithInitials, fullName, contactNumber, address, medium, grade, class, dob, age, extraCurricular, achievements) 
            VALUES ('$admissionNo', '$nameWithInitials', '$fullName', '$contactNumber', '$address', '$medium', '$grade', '$class', '$dob', '$age', '$extraCurricular', '$achievements')";

    if ($conn->query($sql) === TRUE) {
        // Set success message for adding a student, including admission number
        $successMessage = "New student <strong>$fullName</strong> (Admission No: <strong>$admissionNo</strong>) added successfully!<br>Grade: <strong>$grade</strong>, Class: <strong>$class</strong>";
    } else {
        // Set error message for adding a student
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle searching for a student (if the search form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchAdmissionNo'])) {
    // Collect the search input and escape values
    $searchAdmissionNo = mysqli_real_escape_string($conn, $_POST['searchAdmissionNo']);

    // Fetch the student details from the studentsdetails table
    $sql = "SELECT * FROM studentsdetails WHERE admissionNo = '$searchAdmissionNo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the student data
        $student = $result->fetch_assoc();
        
        // Set success message when student is found
        $successMessage = "Student found: <strong>" . $student['fullName'] . "</strong> (Admission No: <strong>" . $student['admissionNo'] . "</strong>)";
    } else {
        // Set error message if no student found
        $errorMessage = "No student found with Admission No: " . $searchAdmissionNo;
    }
}

// Handle updating student details (if the update form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStudent']) && isset($_POST['admissionNo'])) {
    // Collect updated form data
    $admissionNo = mysqli_real_escape_string($conn, $_POST['admissionNo']);
    $updatedNameWithInitials = mysqli_real_escape_string($conn, $_POST['nameWithInitials']);
    $updatedFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $updatedContactNumber = mysqli_real_escape_string($conn, $_POST['contactNumber']);
    $updatedAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $updatedMedium = mysqli_real_escape_string($conn, $_POST['medium']);
    $updatedGrade = mysqli_real_escape_string($conn, $_POST['grade']);
    $updatedClass = mysqli_real_escape_string($conn, $_POST['class']);
    $updatedDob = mysqli_real_escape_string($conn, $_POST['dob']);
    $updatedAge = mysqli_real_escape_string($conn, $_POST['age']);
    $updatedExtraCurricular = mysqli_real_escape_string($conn, $_POST['extraCurricular']);
    $updatedAchievements = mysqli_real_escape_string($conn, $_POST['achievements']);

    // Update the student's details
    $updateSql = "UPDATE studentsdetails SET 
                    nameWithInitials = '$updatedNameWithInitials', 
                    fullName = '$updatedFullName', 
                    contactNumber = '$updatedContactNumber', 
                    address = '$updatedAddress', 
                    medium = '$updatedMedium', 
                    grade = '$updatedGrade', 
                    class = '$updatedClass', 
                    dob = '$updatedDob', 
                    age = '$updatedAge', 
                    extraCurricular = '$updatedExtraCurricular', 
                    achievements = '$updatedAchievements' 
                WHERE admissionNo = '$admissionNo'";

    if ($conn->query($updateSql) === TRUE) {
        // Set success message for updating the student
        $successMessageManage = "Student's details updated successfully!";
        // Refresh the student data after the update
        $sql = "SELECT * FROM studentsdetails WHERE admissionNo = '$admissionNo'";
        $result = $conn->query($sql);
        $student = $result->fetch_assoc();
    } else {
        // Set error message if update failed
        $errorMessageManage = "Error updating record: " . $conn->error;
    }
}

// Initialize variables for dashboard counts
$totalStudents = 0;

// Query to count total students from the studentsdetails table
$sql = "SELECT COUNT(*) AS total_students FROM studentsdetails";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalStudents = $row['total_students'];
} else {
    $totalStudents = 0; // Set to 0 if no data found
}

// Query to get the last 5 activities (students added/updated)
$recentActivities = [];
$sql = "SELECT admissionNo, fullName, grade, class, updated_at FROM studentsdetails ORDER BY updated_at DESC LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the recent activities
    while ($row = $result->fetch_assoc()) {
        $recentActivities[] = $row;
    }
}

// Initialize variables for dashboard counts
$totalStudents = 0;
$classwiseCount = [];

// Query to count total students from the studentsdetails table
$sql = "SELECT COUNT(*) AS total_students FROM studentsdetails";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalStudents = $row['total_students'];
} else {
    $totalStudents = 0; // Set to 0 if no data found
}

// Fetch total teacher count
$totalTeachers = 0;
$sql = "SELECT COUNT(*) AS total_teachers FROM teacher";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalTeachers = $row['total_teachers'];
}

// Query to get the count of students in each class
$sql = "SELECT class, grade, COUNT(*) as student_count FROM studentsdetails GROUP BY class, grade";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the count of students per class and grade
    while ($row = $result->fetch_assoc()) {
        // Check if it's grade 12 or 13 for "Class B - Bio"
        if ($row['class'] == 'B' && in_array($row['grade'], [12, 13])) {
            $classwiseCount['B-Bio'] = isset($classwiseCount['B-Bio']) ? $classwiseCount['B-Bio'] + $row['student_count'] : $row['student_count'];
        } elseif ($row['class'] == 'B' && $row['grade'] <= 11) {
            // For grades 1 to 11, count it as "Class B" general
            $classwiseCount['B-General'] = isset($classwiseCount['B-General']) ? $classwiseCount['B-General'] + $row['student_count'] : $row['student_count'];
        } else {
            // Count all other classes
            $classwiseCount[$row['class']] = isset($classwiseCount[$row['class']]) ? $classwiseCount[$row['class']] + $row['student_count'] : $row['student_count'];
        }
    }
}

// Define classes that need to be displayed even if there are no students
$allClasses = [
    'A' => 'Class A',
    'B-General' => 'Class B ',
    'C' => 'Class C (English Medium)',
    'D' => 'Class D (English Medium)',
    'A1' => 'Class A1 - Arts',
    'A2' => 'Class A2 - Arts',
    'C1' => 'Class C1 - Commerce',
    'C2' => 'Class C2 - Commerce',
    'C3' => 'Class C3 - Commerce',
    'M' => 'Class M - Maths ',
    'B-Bio' => 'Class B - Bio ',
    'E' => 'Class E - Maths/Bio (English Medium)'
];

// Query to get the count of students in each grade
$gradewiseCount = [];
$sql = "SELECT grade, COUNT(*) as student_count FROM studentsdetails GROUP BY grade";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the count of students per grade
    while ($row = $result->fetch_assoc()) {
        $gradewiseCount[$row['grade']] = $row['student_count'];
    }
}

// Fetch total teacher count
$sql = "SELECT COUNT(*) AS total_teachers FROM teacher";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalTeachers = $row['total_teachers'];
}


// Initialize variables
$teachers = [];
$searchedTeacher = null;
$searchQuery = '';
$errorMessage = '';
$successMessage = '';

// Fetch all teachers by default
$sql = "SELECT id, full_name, username, address, phone, dob, age, first_appointment, current_school_appointment, time_in_school, appointment_type, subject, professional_qualifications, educational_qualifications 
        FROM teacher";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch all teachers
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Handle searching for a teacher by name
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchTeacher'])) {
    // Collect the search input and escape it
    $searchQuery = mysqli_real_escape_string($conn, $_POST['searchTeacher']);

    // Fetch the teacher details based on the search input (by full_name)
    $sql = "SELECT id, full_name, username, address, phone, dob, age, first_appointment, current_school_appointment, time_in_school, appointment_type, subject, professional_qualifications, educational_qualifications 
            FROM teacher 
            WHERE full_name LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the teacher data
        $searchedTeacher = $result->fetch_assoc();
        // Clear teachers list to only show the searched teacher
        $teachers = [$searchedTeacher];
    } else {
        // Set error message if no teacher found
        $errorMessage = "No teacher found with the name: $searchQuery";
        // Clear teachers list since no result was found
        $teachers = [];
    }
}

// Handle deletion of a teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteTeacher'])) {
    $teacherId = mysqli_real_escape_string($conn, $_POST['teacherId']);

    $sql = "DELETE FROM teacher WHERE id = '$teacherId'";
    if ($conn->query($sql) === TRUE) {
        $successMessage = "Teacher deleted successfully!";
    } else {
        $errorMessage = "Error deleting teacher: " . $conn->error;
    }

    // Refresh the page to show updated data after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <!-- Icons and Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
    .badge {
        font-size: 1.0em; /* Larger number size */
        padding: 0px;
        display: inline-block;
        text-align: center;
        min-width: 25px;
        min-height: 25px;
        line-height: 1.4em;
    }

    .class-badge {
        background-color: #28a745; /* Green color for class-wise counts */
        color: white;
    }

    .grade-badge {
        background-color: #28a745; /* Green color for grade-wise counts */
        color: white;
    }
</style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <h5 class="text-white text-center my-4">Admin Dashboard</h5>
                    <a href="javascript:void(0);" class="nav-link" data-section="overview"><i class="fas fa-home"></i> Overview</a>
                    <a href="javascript:void(0);" class="nav-link" data-section="search"><i class="fas fa-search"></i> Search Students</a>
                    <a href="javascript:void(0);" class="nav-link" data-section="addStudent"><i class="fas fa-user-plus"></i> Add Student</a>
                    <a href="javascript:void(0);" class="nav-link" data-section="manageStudent"><i class="fas fa-user-cog"></i> Manage Students</a>
                    <a href="javascript:void(0);" class="nav-link" data-section="teacherdetails"><i class="fas fa-chalkboard-teacher"></i> Teachers Details</a>
                    <a href="#" class="nav-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <!-- Success/Error Message for Adding or Managing a Student -->
                <?php if (!empty($successMessageManage)): ?>
                    <div class="alert alert-success">
                        <?php echo $successMessageManage; ?>
                    </div>
                <?php elseif (!empty($errorMessageManage)): ?>
                    <div class="alert alert-danger">
                        <?php echo $errorMessageManage; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <?php echo $successMessage; ?>
                    </div>
                <?php elseif (!empty($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <!-- Dashboard Overview Section -->
<div id="overview" class="content-section">
    <h2 class="my-4">Admin Dashboard Overview</h2>
    <div class="row">
        <!-- Total Students Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Students Count</h5>
                    <p class="card-text"><?php echo $totalStudents; ?></p> <!-- Dynamic student count -->
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Teachers</h5>
                    <p class="card-text"><?php echo $totalTeachers; ?></p> <!-- Dynamic teacher count -->
        </div>
            </div>
        </div>

        <!-- Public Notice Card -->
        <div class="col-md-4">
    <div class="card text-white" style="background-color: #800080;">
        <div class="card-body">
            <h5 class="card-title">Total classes</h5>
            <p class="card-text">12</p> <!-- This is static for now, can be made dynamic if needed -->
                </div>
    </div>
</div>
    </div>

   <!-- Class-wise Student Count -->
<div class="mt-5">
    <h4>Class-Wise Student Count</h4>
    <ul class="list-group">
        <?php foreach ($allClasses as $classCode => $className): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $className; ?>
                <span class="badge class-badge"><?php echo isset($classwiseCount[$classCode]) ? $classwiseCount[$classCode] : 0; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Grade-wise Student Count -->
<div class="mt-5">
    <h4>Grade-Wise Student Count</h4>
    <ul class="list-group">
        <?php for ($grade = 1; $grade <= 13; $grade++): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Grade <?php echo $grade; ?>
                <span class="badge grade-badge"><?php echo isset($gradewiseCount[$grade]) ? $gradewiseCount[$grade] : 0; ?></span>
            </li>
        <?php endfor; ?>
    </ul>
</div>


    <!-- Recent Activities Section -->
<div class="my-4">
    <h4>Recent Activities</h4>
    <ul class="list-group">
        <?php if (!empty($recentActivities)): ?>
            <?php foreach ($recentActivities as $activity): ?>
                <li class="list-group-item">
                    <strong><?php echo $activity['fullName']; ?></strong> (Admission No: <?php echo $activity['admissionNo']; ?>)
                    - Grade: <?php echo $activity['grade']; ?>, Class: <?php echo $activity['class']; ?>
                    <br>
                    <small>Last updated: <?php echo date('Y-m-d H:i:s', strtotime($activity['updated_at'])); ?></small>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">No recent activities available.</li>
        <?php endif; ?>
    </ul>
</div>

</div>

                <!-- Add Student Section (Initially hidden) -->
                <div id="addStudent" class="content-section d-none">
                    <h2 class="my-4">Add Student</h2>
                    <form action="" method="POST">
                        <!-- Admission No -->
                        <div class="mb-3">
                            <label for="admissionNo" class="form-label">Admission No</label>
                            <input type="text" class="form-control" id="admissionNo" name="admissionNo" placeholder="Enter admission number" required>
                        </div>
                        <!-- Name with Initials -->
                        <div class="mb-3">
                            <label for="nameWithInitials" class="form-label">Name with Initials</label>
                            <input type="text" class="form-control" id="nameWithInitials" name="nameWithInitials" placeholder="Enter name with initials" required>
                        </div>
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter full name" required>
                        </div>
                        <!-- Contact Number -->
                        <div class="mb-3">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" required>
                        </div>
                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Enter address" required></textarea>
                        </div>
                        <!-- Select Medium of Instruction -->
                        <div class="mb-3">
                            <label for="medium" class="form-label">Select Medium</label>
                            <select class="form-select" id="medium" name="medium" required>
                                <option selected>Select Medium</option>
                                <option value="English">English Medium</option>
                                <option value="Sinhala">Sinhala Medium</option>
                            </select>
                        </div>
                        <!-- Select Grade -->
                        <div class="mb-3">
                            <label for="grade" class="form-label">Select Grade</label>
                            <select class="form-select" id="grade" name="grade" required>
                                <option selected>Select Grade</option>
                                <option value="1">Grade 1</option>
                                <option value="2">Grade 2</option>
                                <option value="3">Grade 3</option>
                                <option value="4">Grade 4</option>
                                <option value="5">Grade 5</option>
                                <option value="6">Grade 6</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                                <option value="13">Grade 13</option>
                            </select>
                        </div>
                        <!-- Select Class -->
                        <div class="mb-3">
                            <label for="class" class="form-label">Select Class</label>
                            <select class="form-select" id="class" name="class" required>
                                <option selected>Select Class</option>
                                <option value="A">Class A</option>
                                <option value="B">Class B</option>
                                <option value="C">Class C (English Medium)</option>
                                <option value="D">Class D (English Medium)</option>
                                <option value="A1">A1 - Arts</option>
                                <option value="A2">A2 - Arts</option>
                                <option value="C1">C1 - Commerce</option>
                                <option value="C2">C2 - Commerce</option>
                                <option value="C3">C3 - Commerce</option>
                                <option value="M">M - Maths</option>
                                <option value="B">B - Bio</option>
                                <option value="E">E - Maths/Bio (English Medium)</option>
                            </select>
                        </div>
                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" onchange="calculateAge()" required>
                        </div>
                        <!-- Age (Auto-generated from DOB) -->
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="text" class="form-control" id="age" name="age" placeholder="Age will be auto-generated" readonly>
                        </div>
                        <!-- Extra Curricular Activities -->
                        <div class="mb-3">
                            <label for="extraCurricular" class="form-label">Extra Curricular Activities</label>
                            <textarea class="form-control" id="extraCurricular" name="extraCurricular" placeholder="Enter activities"></textarea>
                        </div>
                        <!-- Achievements -->
                        <div class="mb-3">
                            <label for="achievements" class="form-label">Achievements</label>
                            <textarea class="form-control" id="achievements" name="achievements" placeholder="Enter achievements (if any)"></textarea>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </form>
                </div>

                <!-- Manage Students Section -->
                <div id="manageStudent" class="content-section d-none">
                    <h2 class="my-4">Manage Students</h2>
                    
                    <!-- Search Form -->
                    <form action="" method="POST" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchAdmissionNo" placeholder="Enter Admission No" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <!-- Display Student Details If Found and Allow Editing -->
                    <?php if ($student): ?>
                        <h3>Edit Student Details</h3>
                        <form action="" method="POST">
                            <input type="hidden" name="admissionNo" value="<?php echo $student['admissionNo']; ?>">

                            <!-- Editable fields for all student details -->
                            <div class="mb-3">
                                <label for="nameWithInitials" class="form-label">Name with Initials</label>
                                <input type="text" class="form-control" id="nameWithInitials" name="nameWithInitials" value="<?php echo $student['nameWithInitials']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $student['fullName']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contactNumber" value="<?php echo $student['contactNumber']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" required><?php echo $student['address']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="medium" class="form-label">Medium</label>
                                <select class="form-select" id="medium" name="medium" required>
                                    <option value="English" <?php if ($student['medium'] == 'English') echo 'selected'; ?>>English</option>
                                    <option value="Sinhala" <?php if ($student['medium'] == 'Sinhala') echo 'selected'; ?>>Sinhala</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="grade" class="form-label">Grade</label>
                                <select class="form-select" id="grade" name="grade" required>
                                    <option value="1" <?php if ($student['grade'] == 1) echo 'selected'; ?>>Grade 1</option>
                                    <option value="2" <?php if ($student['grade'] == 2) echo 'selected'; ?>>Grade 2</option>
                                    <option value="3" <?php if ($student['grade'] == 3) echo 'selected'; ?>>Grade 3</option>
                                    <option value="4" <?php if ($student['grade'] == 4) echo 'selected'; ?>>Grade 4</option>
                                    <option value="5" <?php if ($student['grade'] == 5) echo 'selected'; ?>>Grade 5</option>
                                    <option value="6" <?php if ($student['grade'] == 6) echo 'selected'; ?>>Grade 6</option>
                                    <option value="7" <?php if ($student['grade'] == 7) echo 'selected'; ?>>Grade 7</option>
                                    <option value="8" <?php if ($student['grade'] == 8) echo 'selected'; ?>>Grade 8</option>
                                    <option value="9" <?php if ($student['grade'] == 9) echo 'selected'; ?>>Grade 9</option>
                                    <option value="10" <?php if ($student['grade'] == 10) echo 'selected'; ?>>Grade 10</option>
                                    <option value="11" <?php if ($student['grade'] == 11) echo 'selected'; ?>>Grade 11</option>
                                    <option value="12" <?php if ($student['grade'] == 12) echo 'selected'; ?>>Grade 12</option>
                                    <option value="13" <?php if ($student['grade'] == 13) echo 'selected'; ?>>Grade 13</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="class" class="form-label">Class</label>
                                <select class="form-select" id="class" name="class" required>
                                    <option value="A" <?php if ($student['class'] == 'A') echo 'selected'; ?>>Class A</option>
                                    <option value="B" <?php if ($student['class'] == 'B') echo 'selected'; ?>>Class B</option>
                                    <option value="C" <?php if ($student['class'] == 'C') echo 'selected'; ?>>Class C (English Medium)</option>
                                    <option value="D" <?php if ($student['class'] == 'D') echo 'selected'; ?>>Class D (English Medium)</option>
                                    <option value="A1" <?php if ($student['class'] == 'A1') echo 'selected'; ?>>A1 - Arts</option>
                                    <option value="A2" <?php if ($student['class'] == 'A2') echo 'selected'; ?>>A2 - Arts</option>
                                    <option value="C1" <?php if ($student['class'] == 'C1') echo 'selected'; ?>>C1 - Commerce</option>
                                    <option value="C2" <?php if ($student['class'] == 'C2') echo 'selected'; ?>>C2 - Commerce</option>
                                    <option value="C3" <?php if ($student['class'] == 'C3') echo 'selected'; ?>>C3 - Commerce</option>
                                    <option value="M" <?php if ($student['class'] == 'M') echo 'selected'; ?>>M - Maths</option>
                                    <option value="B" <?php if ($student['class'] == 'B') echo 'selected'; ?>>B - Bio</option>
                                    <option value="E" <?php if ($student['class'] == 'E') echo 'selected'; ?>>E - Maths/Bio (English Medium)</option>
                                </select>
                            </div>

                            <!-- Date of Birth and Age -->
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student['dob']; ?>" required onchange="calculateAge()">
                            </div>
                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="text" class="form-control" id="age" name="age" value="<?php echo $student['age']; ?>" readonly>
                            </div>

                            <!-- Extra Curricular Activities -->
                            <div class="mb-3">
                                <label for="extraCurricular" class="form-label">Extra Curricular Activities</label>
                                <textarea class="form-control" id="extraCurricular" name="extraCurricular"><?php echo $student['extraCurricular']; ?></textarea>
                            </div>

                            <!-- Achievements -->
                            <div class="mb-3">
                                <label for="achievements" class="form-label">Achievements</label>
                                <textarea class="form-control" id="achievements" name="achievements"><?php echo $student['achievements']; ?></textarea>
                            </div>

                            <!-- Submit Button for Update -->
                            <button type="submit" class="btn btn-success" name="updateStudent">Update Student</button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Search Section -->
                <div id="search" class="content-section d-none">
                    <h2 class="my-4">Search Students</h2>
                    <!-- Search Form -->
                    <form action="" method="POST" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchAdmissionNo" placeholder="Enter Admission No" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <!-- Success/Error Message for Searching a Student -->
                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo $successMessage; ?>
                        </div>
                    <?php elseif (!empty($errorMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display Student Details If Found -->
                    <?php if ($student): ?>
                        <h3>Student Details</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admission No</td>
                                    <td><?php echo $student['admissionNo']; ?></td>
                                </tr>
                                <tr>
                                    <td>Name with Initials</td>
                                    <td><?php echo $student['nameWithInitials']; ?></td>
                                </tr>
                                <tr>
                                    <td>Full Name</td>
                                    <td><?php echo $student['fullName']; ?></td>
                                </tr>
                                <tr>
                                    <td>Contact Number</td>
                                    <td><?php echo $student['contactNumber']; ?></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?php echo $student['address']; ?></td>
                                </tr>
                                <tr>
                                    <td>Medium</td>
                                    <td><?php echo $student['medium']; ?></td>
                                </tr>
                                <tr>
                                    <td>Grade</td>
                                    <td><?php echo $student['grade']; ?></td>
                                </tr>
                                <tr>
                                    <td>Class</td>
                                    <td><?php echo $student['class']; ?></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td><?php echo $student['dob']; ?></td>
                                </tr>
                                <tr>
                                    <td>Age</td>
                                    <td><?php echo $student['age']; ?></td>
                                </tr>
                                <tr>
                                    <td>Extra Curricular Activities</td>
                                    <td>
                                        <?php 
                                        // Display activities one by one
                                        $activities = explode(',', $student['extraCurricular']);
                                        foreach ($activities as $activity) {
                                            echo trim($activity) . "<br>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Achievements</td>
                                    <td>
                                        <?php 
                                        // Display achievements one by one
                                        $achievements = explode(',', $student['achievements']);
                                        foreach ($achievements as $achievement) {
                                            echo trim($achievement) . "<br>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Teacher Details Section -->
<div id="teacherdetails" class="content-section d-none">
    <h2 class="my-4">Teacher Details</h2>

 
<!-- Success and Error Messages -->
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-success">
        <?php echo $successMessage; ?>
    </div>
<?php elseif (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>

<!-- Search Form for Teacher by Name -->
<form action="" method="POST" class="mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="searchTeacher" placeholder="Enter Teacher Name" value="<?php echo htmlspecialchars($searchQuery); ?>" required>
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<!-- PDF Button to Generate PDF from the Teacher Details -->
<form action="generate_pdf.php" method="POST" class="mb-4">
    <button type="submit" class="btn btn-secondary">Generate PDF</button>
</form>

<!-- Table to Display All Teachers Details -->
<?php if (!empty($teachers)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>First Appointment</th>
                <th>Current School Appointment</th>
                <th>Time in School</th>
                <th>Appointment Type</th>
                <th>Subject</th>
                <th>Professional Qualifications</th>
                <th>Educational Qualifications</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?php echo htmlspecialchars($teacher['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['username']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['address']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['phone']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['dob']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['age']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['first_appointment']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['current_school_appointment']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['time_in_school']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['appointment_type']); ?></td>
                    
                    <!-- Split Subjects by commas and display in separate rows -->
                    <td>
                        <?php 
                        $subjects = explode(',', $teacher['subject']);
                        foreach ($subjects as $subject) {
                            echo htmlspecialchars(trim($subject)) . "<br>";
                        }
                        ?>
                    </td>
                    
                    <!-- Split Professional Qualifications by commas and display in separate rows -->
                    <td>
                        <?php 
                        $professional_qualifications = explode(',', $teacher['professional_qualifications']);
                        foreach ($professional_qualifications as $qualification) {
                            echo htmlspecialchars(trim($qualification)) . "<br>";
                        }
                        ?>
                    </td>

                    <!-- Split Educational Qualifications by commas and display in separate rows -->
                    <td>
                        <?php 
                        $educational_qualifications = explode(',', $teacher['educational_qualifications']);
                        foreach ($educational_qualifications as $qualification) {
                            echo htmlspecialchars(trim($qualification)) . "<br>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No teachers found.</p>
<?php endif; ?>


                    <!-- Edit and Delete Buttons (Only when a teacher is searched) -->
                    <?php if ($searchedTeacher): ?>
                    <div class="d-flex mt-3">
                        <a href="edit_teacher.php?id=<?php echo $searchedTeacher['id']; ?>" class="btn btn-warning me-2">Edit</a>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="teacherId" value="<?php echo $searchedTeacher['id']; ?>">
                            <button type="submit" name="deleteTeacher" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

           

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Logout Redirect Script -->
    <script>
        document.getElementById('confirmLogout').addEventListener('click', function () {
            window.location.href = 'login.php';
        });

        // Function to handle section switching
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                const targetSection = link.getAttribute('data-section');

                // Hide all sections
                sections.forEach(section => section.classList.add('d-none'));

                // Show the targeted section
                document.getElementById(targetSection).classList.remove('d-none');

                // Remove 'active' class from all links and add to the clicked one
                navLinks.forEach(link => link.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Function to calculate age based on Date of Birth
        function calculateAge() {
            var dob = document.getElementById("dob").value;
            var dobDate = new Date(dob);
            var currentDate = new Date();
            var age = currentDate.getFullYear() - dobDate.getFullYear();
            var monthDifference = currentDate.getMonth() - dobDate.getMonth();

            // Adjust if birthdate hasn't occurred this year yet
            if (monthDifference < 0 || (monthDifference === 0 && currentDate.getDate() < dobDate.getDate())) {
                age--;
            }

            document.getElementById("age").value = age;
        }
    </script>

</body>

</html>
