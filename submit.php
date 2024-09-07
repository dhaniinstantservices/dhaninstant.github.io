<?php
// Database configuration
$servername = "localhost"; // Your database server
$username = "adminpan_cashloan"; // Your database username
$password = "Amit@2020#"; // Your database password
$dbname = "adminpan_cashloan"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $loan_amount = $_POST['loan_amount'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $otp = $_POST['otp'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($mobile) || empty($email) || empty($loan_amount) || empty($gender) || empty($otp)) {
        echo 'All fields are required.';
        exit;
    }
    
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        echo 'Invalid mobile number.';
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address.';
        exit;
    }
    
    // Check if the mobile number already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    if ($count > 0) {
        echo 'Mobile number already exists.';
        exit;
    }
    
    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO users (name, mobile, email, loan_amount, gender, otp) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $mobile, $email, $loan_amount, $gender, $otp);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Failed to submit data.';
    }
    
    $stmt->close();
} else {
    echo 'Invalid request method';
}

$conn->close();
?>
