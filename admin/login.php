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
    $username = $_POST['username'];
    $password = $_POST['password'];
}

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
   
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging
    }
   
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

   
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: admin.php?page=dashboard"); 
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    } 


// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User registered successfully!";
        header("Location: login.php?page=login");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: login.php?page=register");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link href='https://fonts.googleapis.com/css?family=Source Sans 3' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color:#d9e8e6;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: white;
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
    background-color: #008080;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 5px;
    width: 100%;
}

button:hover {
    background-color: rgb(67,229,189);
}
</style>

<div class="login-container">
    <img src="booklogo.png" alt="Library Logo" class="logo">

    <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green; text-align:center;'>$success</p>"; ?>

    <?php if (!isset($_GET['page']) || $_GET['page'] == "login") { ?>
        <h2>Login</h2>
        <form action="index.php?page=login" method="POST">           
             <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit" name="login">Login</button>
        
        </form>
        <p>Don't have an account? <a href="login.php?page=register">Register</a></p>

    <?php } elseif ($_GET['page'] == "register") { ?>
        <h2>Register</h2>
        <form action="login.php?page=register" method="POST">
        <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit" name="register">Register</button>
            
        </form>
        <p>Already have an account? <a href="login.php?page=login">Login</a></p>
    <?php } ?>
</div>
    
</body>
</html>
