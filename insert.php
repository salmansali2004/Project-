<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = 'localhost';
$db = 'user_login_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $register_number = $_POST['register_number'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $fathers_name = $_POST['fathers_name'];
        $mothers_name = $_POST['mothers_name'];
        $address = $_POST['address'];

        // Insert into the database
        $stmt = $pdo->prepare("INSERT INTO students (register_number, name, email, phone_number, fathers_name, mothers_name, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$register_number, $name, $email, $phone_number, $fathers_name, $mothers_name, $address]);

        // Redirect or show a success message
        header("Location: success.php"); // Create a success page to show a confirmation
        exit();
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Student Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .container {
            position: relative;
            max-width: 600px;
            margin: auto;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d; /* Gray color */
            font-size: 24px; /* Icon size */
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .back-button:hover {
            color: #495057; /* Darker gray on hover */
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: -8px;
        }
    </style>
</head>
<body>

<div class="container">
    <button type="button" class="back-button" onclick="window.location.href='dashboard.php'">
        <i class="fas fa-arrow-left"></i>
    </button>
    <h2 style="text-align: center;">Enter Student Details</h2>
    <form id="studentForm" action="insert.php" method="post" onsubmit="return validateForm()">
        <label for="register_number">Register Number:</label>
        <input type="text" id="register_number" name="register_number" required pattern="\d+" title="Please enter a valid register number (digits only).">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required title="Please enter your name.">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required title="Please enter a valid email address.">

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" pattern="\d{10}" title="Please enter a valid phone number (10 digits).">

        <label for="fathers_name">Father's Name:</label>
        <input type="text" id="fathers_name" name="fathers_name" required title="Please enter your father's name.">

        <label for="mothers_name">Mother's Name:</label>
        <input type="text" id="mothers_name" name="mothers_name" required title="Please enter your mother's name.">

        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="4" required title="Please enter your address."></textarea>

        <button type="submit">Submit</button>
    </form>
</div>

<script>
function validateForm() {
    const registerNumber = document.getElementById("register_number").value;
    const email = document.getElementById("email").value;
    const phoneNumber = document.getElementById("phone_number").value;

    // Basic validation
    if (!/^\d+$/.test(registerNumber)) {
        alert("Register number must contain only digits.");
        return false;
    }

    if (phoneNumber && !/^\d{10}$/.test(phoneNumber)) {
        alert("Phone number must be 10 digits.");
        return false;
    }

    return true; // All checks passed
}
</script>

</body>
</html>
