<?php
  session_start();


$adminCredentials = [
    'username' => 'admin', 
    'password' => '123' 
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $adminCredentials['username'] && $_POST['password'] === $adminCredentials['password']) {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid credentials!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style> 
        body {
            font-family: Arial, sans-serif;
            background-color: #d9e8e6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #008080;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color:rgb(67, 229, 189);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
        <form method="POST">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>



