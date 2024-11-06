<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'user_login_system'; // Update with your database name
$user = 'root'; // Update with your database user
$pass = ''; // Update with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic validation
    if (!empty($email) && !empty($password)) {
        // Prepare and execute the query
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = ?");
        $stmt->execute([$email]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($teacher && password_verify($password, $teacher['password'])) {
            // Store user information in the session
            $_SESSION['teacher_id'] = $teacher['teacher_id']; // Ensure this matches your database column name
            $_SESSION['teacher_name'] = $teacher['name']; // Ensure this matches your database column name
            // Redirect to teacher dashboard or homepage
            header("Location: teacher_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box; /* Ensures padding and border are included in width and height */
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('your-background-image.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9); /* Slightly more opaque for better readability */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%; /* Responsive width */
            box-sizing: border-box; /* Ensures padding is included */
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .input-group {
            position: relative;
            margin: 15px 0; /* Adjust margin to ensure spacing */
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 40px; /* Space for icons */
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s; /* Smooth transition on focus */
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: #dc3545;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .login-container {
                padding: 20px;
            }
            input[type="email"],
            input[type="password"] {
                padding: 10px 30px; /* Adjust padding for smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Teacher Login</h1>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
s