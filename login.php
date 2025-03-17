<?php
session_start();

// Database Connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$database = "library";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                header("Location: admin.php");
            } else {
                header("Location: admin.php");
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
    $role = $_POST['role'];

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
    <title>Library Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: #d4c7f5;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 300px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.logo {
    width: 100px;
    margin-bottom: 10px;
}

.login-form {
    display: flex;
    flex-direction: column;
}

label {
    text-align: left;
    font-weight: bold;
    margin-top: 10px;
}

input {
    width: 100%; 
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #000;
    color: white;
    border: none;
    padding: 10px;
    margin-top: 15px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
}

button:hover {
    background-color: #444;
}
</style>

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
            <label>Role:</label>
            
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php?page=login">Login</a></p>
    <?php } ?>
</div>

</body>
</html>


