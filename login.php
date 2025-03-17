<?php
session_start();

// Include database configuration
require_once 'config.php';

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            
            if ($row['role'] == "admin") {
                header("Location: admin/admin.php");
            } else {
                header("Location: admin/admin.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User registered successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-container">
    <img src="booklogo.png" alt="Library Logo" class="logo">

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red; text-align:center;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); 
    }
    if (isset($_SESSION['success'])) {
        echo "<p style='color:green; text-align:center;'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']); 
    }
    ?>

    <?php if (!isset($_GET['page']) || $_GET['page'] == "login") { ?>
        <h2>Login</h2>
        <form class="login-form" action="login.php" method="POST">
            <input type="hidden" name="login" value="1">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="login.php?page=register">Register</a></p>

    <?php } elseif ($_GET['page'] == "register") { ?>
        <h2>Register</h2>
        <form class="login-form" action="login.php" method="POST">
            <input type="hidden" name="register" value="1">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="hidden" name="role" value="user">
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php?page=login">Login</a></p>
    <?php } ?>
</div>
</body>
</html>


