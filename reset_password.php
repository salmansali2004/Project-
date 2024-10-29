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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update_stmt->bind_param("ss", $hashed_new_password, $email);

    if ($update_stmt->execute()) {
        $success_message = "Password reset successfully!";
    } else {
        $error_message = "Error updating password!";
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
        input[type="email"],
        input[type="password"] {
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
         a {
            margin-left: 38%;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            color: #d9534f; /* Red for error messages */
        }
        .success {
            color: #5cb85c; /* Green for success messages */
        }
    </style>
    <script>
    function showMessage(message, isSuccess) {
        if (isSuccess) {
            alert(message); // Show success message
        } else {
            alert("Error: " + message); // Show error message
        }
    }

    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(event.target);
        fetch('/salman/reset_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Assuming the server returns plain text: "Password reset successfully!" or "Error updating password!"
            if (data.includes("successfully")) {
                showMessage("Password updated successfully!", true);
            } else {
                showMessage(data, false);
            }
        })
        .catch(error => {
            showMessage("An error occurred: " + error.message, false);
        });
    }
</script>

</head>
<body>
    <h1>Password Reset</h1>
    <form class="form-container" action="" method="post" onsubmit="handleFormSubmit(event)">
        <input type="hidden" name="reset_password" value="1">
        <label for="reset_email">Email:</label>
        <input type="email" id="reset_email" name="email" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <input type="submit" value="Reset Password">
    </form>
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <p><a href="/salman/login.php" class="back">Back to login</a></p>
</body>
</html>
