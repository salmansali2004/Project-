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

    // Fetching student details if register number is provided
    if (isset($_GET['register_number'])) {
        $register_number = $_GET['register_number'];

        $stmt = $pdo->prepare("SELECT * FROM students WHERE register_number = ?");
        $stmt->execute([$register_number]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            echo "<h3>No details found for this register number.</h3>";
            exit();
        }
    } else {
        echo "<h3>No register number provided.</h3>";
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
    <title>View Student Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .back-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #007bff;
            font-size: 24px;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .back-button:hover {
            color: #0056b3;
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <button type="button" class="back-button" onclick="window.location.href='dashboard.php'">
        <i class="fas fa-arrow-left"></i>
    </button>
    <h2>Student Details</h2>
    <form>
        <label for="register_number">Register Number:</label>
        <p><?php echo htmlspecialchars($student['register_number']); ?></p>

        <label for="name">Name:</label>
        <p><?php echo htmlspecialchars($student['name']); ?></p>

        <label for="email">Email:</label>
        <p><?php echo htmlspecialchars($student['email']); ?></p>

        <label for="phone_number">Phone Number:</label>
        <p><?php echo htmlspecialchars($student['phone_number']); ?></p>

        <label for="fathers_name">Father's Name:</label>
        <p><?php echo htmlspecialchars($student['fathers_name']); ?></p>

        <label for="mothers_name">Mother's Name:</label>
        <p><?php echo htmlspecialchars($student['mothers_name']); ?></p>

        <label for="address">Address:</label>
        <p><?php echo nl2br(htmlspecialchars($student['address'])); ?></p>
    </form>
</div>

</body>
</html>
