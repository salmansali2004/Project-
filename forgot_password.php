<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form action="verify_security.php" method="post">
        <label for="email">Enter your email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="security_key">Enter your Security Key:</label><br>
        <input type="text" id="security_key" name="security_key" required><br><br>

        <input type="submit" value="Verify">
    </form>
    <p><a href="login.php">Back to Login</a></p>
</body>
</html>
