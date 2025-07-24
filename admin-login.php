<?php
session_start();

$credentialsFile = "admin_credentials.txt";
$storedUsername = "";
$storedPassword = "";

if (file_exists($credentialsFile)) {
    $fileContent = file($credentialsFile, FILE_IGNORE_NEW_LINES);
    if (!empty($fileContent)) {
        [$storedUsername, $storedPassword] = explode(",", trim($fileContent[0]));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $storedUsername && $password === $storedPassword) {
        $_SESSION['loggedin'] = true;
    } else {
        $error = "Invalid username or password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Admin Login</h3>

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger text-center"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if (!isset($_SESSION['loggedin'])) : ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    <?php else : ?>
                        <div class="text-center">
                            <p class="text-success">Login successful!</p>
                            <a href="admin.php" class="btn btn-secondary mb-2 w-100">View Contact Form Data</a>
                            <a href="bouncer-admin.php" class="btn btn-secondary w-100">View Bouncer Bookings</a>
                            <a href="logout.php" class="btn btn-outline-danger w-100 mt-3">Logout</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
