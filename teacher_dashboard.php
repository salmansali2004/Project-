<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
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
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit();
}

// Fetch teacher details
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
$stmt->execute([$_SESSION['teacher_id']]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f5f5f5;
            color: #333;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #007bff;
            color: #fff;
            height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar h1 {
            margin-bottom: 40px;
            font-size: 24px;
        }

        .sidebar .profile {
            text-align: center;
        }

        .sidebar .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .sidebar .profile h2 {
            margin: 0;
            font-size: 18px;
        }

        .sidebar .menu {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar .menu a {
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #0056b3;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            transition: background-color 0.3s;
        }

        .sidebar .menu a:hover {
            background-color: #003d7a;
        }

        /* Main Content */
        .content {
            flex: 1;
            padding: 30px;
            background-color: #fff;
            min-height: 100vh;
        }

        .content h2 {
            font-size: 30px;
            margin-bottom: 20px;
        }

        .content .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .content .card h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content .card p {
            margin-bottom: 10px;
        }

        .content .card .edit-button {
            display: inline-block;
            background-color: #28a745;
            padding: 10px 15px;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .content .card .edit-button:hover {
            background-color: #218838;
        }

        /* Footer */
        footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                padding: 15px;
            }

            .content {
                padding: 20px;
            }

            .sidebar .menu a {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h1>Teacher Dashboard</h1>
    <div class="profile">
        <img src="https://via.placeholder.com/80" alt="Profile Image">
        <h2><?php echo htmlspecialchars($teacher['name']); ?></h2>
    </div>
    <div class="menu">
        <a href="view_students.php"><i class="fas fa-users"></i> View Students</a>
        <a href="update_marks.php"><i class="fas fa-pencil-alt"></i> Update Marks</a>
        <a href="delete_marks.php"><i class="fas fa-trash-alt"></i> Delete Marks</a>
        <a href="view_marks.php"><i class="fas fa-list"></i> View Marks</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="content">
    <h2>Welcome back, <?php echo htmlspecialchars($teacher['name']); ?>!</h2>

    <div class="card">
        <h3>Your Details</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($teacher['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($teacher['phone_number']); ?></p>
        <p><strong>Subject Specialization:</strong> <?php echo htmlspecialchars($teacher['subject_specialization']); ?></p>
        <a href="update_teacher.php?id=<?php echo htmlspecialchars($teacher['teacher_id']); ?>" class="edit-button">Edit Profile</a>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; <?php echo date("Y"); ?> Your School Name. All rights reserved.
</footer>

</body>
</html>
