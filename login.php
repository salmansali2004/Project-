<?php
session_start();
// Database configuration
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'user_login_system'; // Change to your database name
$user = 'root'; // Change to your database username
$pass = ''; // Change to your database password

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query to fetch user data
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password against the hashed password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username; // Store the logged-in username
            header("Location: dashboard.php");
            exit(); // Make sure to exit after redirection
        } else {
            echo "<h1>Invalid password!</h1>";
        }
    } else {
        echo "<h1>User not found!</h1>";
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
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #80bdff;
            outline: none;
        }
        .login-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .forgot_password {
            margin-top: 15px;
            font-size: 14px;
        }
        .forgot_password a {
            text-decoration: none;
            color: #007bff;
        }
        .forgot_password a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>User Login</h1>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <div class="forgot_password">
            <p><a href="reset_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>
