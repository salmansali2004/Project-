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

    // Handle form submission for deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_teacher_id'])) {
        $teacher_id = $_POST['delete_teacher_id']; // Get teacher ID to delete

        // Basic validation
        if (!empty($teacher_id)) {
            // Prepare the delete statement
            $stmt = $pdo->prepare("DELETE FROM teachers WHERE teacher_id = ?");
            $stmt->execute([$teacher_id]);

            // Redirect to the same page with a success message
            header("Location: delete_teacher.php?success=Teacher deleted successfully!");
            exit();
        }
    }

    // Fetch all teachers
    $stmt = $pdo->query("SELECT * FROM teachers");
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>View Teachers</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .delete-button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background: #c82333;
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
    <h1>Teachers List</h1>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green; text-align: center;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($teachers) > 0): ?>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($teacher['teacher_id']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['phone_number']); ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="delete_teacher_id" value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>">
                                <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No teachers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
