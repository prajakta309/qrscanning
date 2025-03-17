<?php
session_start();

$servername = "localhost"; 
$usernameDB = "root"; 
$passwordDB = ""; 
$database = "library"; 

$conn = new mysqli($servername, $usernameDB, $passwordDB, $database);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

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
            header("Location: main.php?page=dashboard"); 
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    }

    header("Location: main.php?page=login");
    exit();
}

// Handle user registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    $sql = "INSERT INTO users (username, password) VALUES (?, ? )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User registered successfully!";
        header("Location: main.php?page=login");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: main.php?page=register");
    }
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: main.php?page=login");
    exit();
}

// Check session for dashboard access
if (isset($_GET['page']) && $_GET['page'] == "dashboard" && !isset($_SESSION['username'])) {
    header("Location: main.php?page=register");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - Login</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
 <style>
    body{
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
        <img src="booklogo.png" alt="Book " class="logo">

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
            <form class="login-form" action="main.php" method="POST">
                <input type="hidden" name="login" value="1">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login >></button>
            </form>
            <p>Don't have an account? <a href="main.php?page=register">Register</a></p>
        <?php } elseif ($_GET['page'] == "register") { ?>
            <h2>Register</h2>
            <form class="login-form" action="main.php" method="POST">
                <input type="hidden" name="register" value="1">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Register >></button>
            </form>
            <p>Already have an account? <a href="main.php?page=login">Login</a></p>
        <?php } elseif ($_GET['page'] == "dashboard") { ?>
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <a href="main.php?logout=true">Logout</a>
        <?php } ?>
    </div>

</body>
</html>
