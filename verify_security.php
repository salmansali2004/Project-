<?php
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

$email = '';
$security_key_verified = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $security_key = $_POST['security_key'];

    // Prepare and execute the query to fetch user data
    $stmt = $conn->prepare("SELECT security_key FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_security_key);
        $stmt->fetch();

        // Verify the security key
        if (password_verify($security_key, $stored_security_key)) {
            $security_key_verified = true;
        } else {
            echo "<h1>Invalid security key!</h1>";
        }
    } else {
        echo "<h1>Email not found!</h1>";
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
    <title>Set New Password</title>
</head>
<body>
    <h1>Set New Password</h1>
    <?php if ($security_key_verified): ?>
        <form action="reset_password.php" method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <label for="new_password">Enter your new password:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>

            <input type="submit" value="Reset Password">
        </form>
    <?php else: ?>
        <p><a href="forgot_password.php">Try Again</a></p>
    <?php endif; ?>
</body>
</html>
