<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$host = 'localhost'; // your database host
$db = 'user_login_system'; // your database name
$user = 'root'; // your database username
$pass = ''; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT email, phone_number FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($userDetails) {
        $email = $userDetails['email'];
        $phone = $userDetails['phone_number'];
    } else {
        $email = 'Not available';
        $phone = 'Not available';
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
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: linear-gradient(135deg, #f2f2f2, #e6e6e6);
            color: #333;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .banner {
            background: #0056b3;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .icon-button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 15px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .icon-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .icon-button i {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 5px;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            overflow: auto;
        }

        .profile-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .profile-button:hover {
            background: #0056b3;
        }

        .department-info {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
            max-width: 800px;
        }

        .department-info h3 {
            margin-top: 0;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-close {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .modal-close:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <h1>Admin Panel</h1>
</header>
<div class="banner">
    <button class="icon-button" onclick="window.location.href='insert.php'">
        <i class="fas fa-plus"></i>
        Insert Details
    </button>
    <button class="icon-button" onclick="window.location.href='update_marks.php'">
        <i class="fas fa-pencil-alt"></i>
        Update Details
    </button>
    <button class="icon-button" onclick="window.location.href='delete.php'">
        <i class="fas fa-trash"></i>
        Delete Details
    </button>
    <button class="icon-button" onclick="openRegisterModal()">
        <i class="fas fa-eye"></i>
        View Details
    </button>
</div>
<button class="profile-button" onclick="openUserModal()">
    <i class="fas fa-user-circle" style="font-size: 36px;"></i>
</button>
<div class="container">
    <div class="department-info">
        <h3>About the Computer Science Department</h3>
        <p>The Computer Science Department offers a comprehensive curriculum that prepares students for careers in technology and computing. Our programs cover various areas, including software development, data science, and artificial intelligence. We emphasize hands-on learning and provide opportunities for research and collaboration with industry leaders.</p>
    </div>
</div>

<!-- User Modal -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <h3>User Info</h3>
        <p>Username: <?php echo htmlspecialchars($username); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Phone: <?php echo htmlspecialchars($phone); ?></p>
        <form action="logout.php" method="post">
            <button type="submit" class="modal-close">Logout</button>
        </form>
        <button class="modal-close" onclick="closeUserModal()">Close</button>
    </div>
</div>

<!-- Modal for entering register number -->
<div class="modal" id="registerModal">
    <div class="modal-content">
        <h3>Enter Register Number</h3>
        <input type="text" id="register_number" placeholder="Register Number" />
        <button class="modal-close" onclick="viewDetails()">View Details</button>
        <button class="modal-close" onclick="closeRegisterModal()">Close</button>
    </div>
</div>

<script>
    function openUserModal() {
        document.getElementById('userModal').style.display = 'flex';
    }

    function closeUserModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    function openRegisterModal() {
        document.getElementById('registerModal').style.display = 'flex';
    }

    function closeRegisterModal() {
        document.getElementById('registerModal').style.display = 'none';
    }

    function viewDetails() {
        const registerNumber = document.getElementById('register_number').value;
        if (registerNumber) {
            window.location.href = `view_details.php?register_number=${registerNumber}`;
        } else {
            alert("Please enter a register number.");
        }
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('userModal')) {
            closeUserModal();
        } else if (event.target == document.getElementById('registerModal')) {
            closeRegisterModal();
        }
    }
</script>

</body>
</html>
