<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database configuration
$host = 'localhost'; 
$db = 'user_login_system'; 
$user = 'root'; 
$pass = ''; 

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $security_key = $_POST['security_key'];
    $security_key_value = $_POST['security_key_value'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];

    // Check for existing username or email
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Duplicate found
        $error_message = "Username or email already exists. Please try another.";
    } else {
        // Hashing password and security details
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_security_key = password_hash($security_key, PASSWORD_DEFAULT);
        $hashed_security_key_value = password_hash($security_key_value, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, security_key, security_key_value, gender, phone_number, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $hashed_password, $email, $hashed_security_key, $hashed_security_key_value, $gender, $phone_number, $date_of_birth);

        // Execute and check for success
        if ($stmt->execute()) {
            $success_message = "User Registered Successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();

    // Return a response
    if ($success_message) {
        echo json_encode(["success" => true, "message" => $success_message]);
    } else {
        echo json_encode(["success" => false, "message" => $error_message]);
    }
    exit; // Ensure exit is called to prevent further outputs    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 4px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .security-key-input {
            display: none;
        }
    </style>
    <script>
         function toggleSecurityKeyInput() {
            const selectBox = document.getElementById("security_key");
            const inputField = document.getElementById("security_key_value");
            inputField.style.display = selectBox.value ? "block" : "none";
        }

        function showError(message) {
            alert(message);
        }
   function handleFormSubmit(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(event.target);
    fetch('/salman/user_registration.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '/salman/login.php'; // Redirect to the login page
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        showError("An error occurred: " + error.message);
    });
}
</script>
</head>
<body>
    <h1>User Registration</h1>
    <form class="form-container" action="" method="post" onsubmit="handleFormSubmit(event)">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="security_key">Security Key (Select One):</label>
        <select id="security_key" name="security_key" onchange="toggleSecurityKeyInput()" required>
            <option value="">--Select--</option>
            <option value="Mother's Name">Mother's Name</option>
            <option value="Father's Name">Father's Name</option>
            <option value="Pet's Name">Pet's Name</option>
            <option value="Best Friend's Name">Best Friend's Name</option>
            <option value="Favorite Teacher's Name">Favorite Teacher's Name</option>
        </select>

        <label for="security_key_value">Enter Your Security Key:</label>
        <input type="text" id="security_key_value" name="security_key_value" style="display:none;" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" required pattern="\d{10}" title="Please enter exactly 10 digits.">

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>

        <input type="submit" value="Register">
    </form>
</body>
</html>
