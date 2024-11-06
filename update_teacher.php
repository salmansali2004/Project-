<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    echo "Session not set. You are not logged in.";
    exit();
}

$host = 'localhost';
$db = 'user_login_system'; 
$user = 'root'; 
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit();
}

// Processing form submission for updating teacher details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Validating fields
    if (!empty($teacher_id) && !empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($phone)) {
        $stmt = $pdo->prepare("UPDATE teachers SET name = ?, email = ?, phone_number = ? WHERE teacher_id = ?");
        if ($stmt->execute([$name, $email, $phone, $teacher_id])) {
            $_SESSION['success'] = "Teacher updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update teacher.";
        }
        header("Location: update_teacher.php?id=$teacher_id");
        exit();
    } else {
        $_SESSION['error'] = "Please fill all fields correctly.";
    }
}

// Fetching teacher details for editing
if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
    $stmt->execute([$teacher_id]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        $_SESSION['error'] = 'No teacher found with that ID.';
        header("Location: view_teachers.php");
        exit();
    }
} else {
    header("Location: view_teachers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Teacher</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
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
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .update-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: auto;
            transition: background 0.3s;
        }
        .update-button:hover {
            background: #0056b3;
        }
        .message {
            color: green;
            text-align: center;
            margin: 10px 0;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="teacher_dashboard.php" class="back-button" title="Back to Teachers List">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <h1>Update Teacher</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($teacher['phone_number']); ?>" required>
        </div>
        <button type="submit" class="update-button">Update Teacher</button>
    </form>
</div>

</body>
</html>
