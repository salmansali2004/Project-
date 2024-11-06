<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = 'localhost';
$db = 'user_login_system'; // Update with your database name
$user = 'root'; // Update with your database user
$pass = ''; // Update with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone_number = trim($_POST['phone_number']);
        $subject_specialization = trim($_POST['subject_specialization']);
        $hire_date = $_POST['hire_date'];
        $address = trim($_POST['address']);
        $password = trim($_POST['password']); // Get the password

        // Basic validation
        if (empty($name) || empty($email) || empty($password) || empty($hire_date)) {
            $error = "Name, email, password, and hire date are required fields.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert into the database
            $stmt = $pdo->prepare("INSERT INTO teachers (name, email, phone_number, subject_specialization, hire_date, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone_number, $subject_specialization, $hire_date, $address, $hashed_password]);

            // Success message
            $success = "Teacher details added successfully!";
        }
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
    <title>Insert Teacher</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .back-button {
            position: absolute;
            top: 15px;
            left: 15px;
            text-decoration: none;
            color: #333;
            font-size: 24px;
            display: flex;
            align-items: center;
        }
        .back-button svg {
            width: 24px;
            height: 24px;
            margin-right: 5px;
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .success {
            color: green;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
     <!-- Back Button Icon -->
     <a href="dashboard.php" class="back-button" title="Back to Dashboard">
            <!-- SVG for Back Arrow Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    <h1>Add Teacher</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif (isset($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number">
        </div>
        <div class="form-group">
            <label for="subject_specialization">Subject Specialization:</label>
            <input type="text" id="subject_specialization" name="subject_specialization">
        </div>
        <div class="form-group">
            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
        </div>
        <button type="submit">Add Teacher</button>
    </form>
</div>

</body>
</html>
