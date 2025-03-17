<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: panel.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F5DC;
            display: flex;
            height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #008080;
            color: white;
            padding: 30px 20px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .sidebar ul {
            list-style: none;
            flex-grow: 1;
        }
        
        .sidebar ul li {
            margin: 20px 0;
        }
        
        .sidebar ul li a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 5px;
            text-align: center;
        }
        
        .sidebar ul li a:hover {
            background-color: #34495e;
        }
        
        .logout {
            background-color: red;
            padding: 10px 20px;
            color: white;
            border-radius: 50px;
            text-decoration: none;
            text-align: center;
            display: block;
        }
        
        .logout:hover {
            background-color: #b30000;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            background-color: #D9E8E6;
            min-height: 100vh;
        }
        
        h1, h3 {
            color: #2166ab;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_panel.php?page=enter_books">Enter Books</a></li>
            <li><a href="admin_panel.php?page=generate_books">Generate Books</a></li>
            <li><a href="admin_panel.php?page=return_books">Return Books</a></li>
        </ul>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    
    <div class="main-content">
        <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                if ($page == "enter_books") {
                    include("enter_books.php");
                } elseif ($page == "generate_books") {
                    include("generate_books.php");
                } elseif ($page == "return_books") {
                    include("return_books.php");
                } else {
                    echo "<h1>Welcome To The Admin Dashboard</h1><p>Select an option from the sidebar.</p>";
                }
            } else {
                echo "<h1>Welcome To The Admin Dashboard</h1><p>Select an option from the sidebar.</p>";
            }
        ?>
    </div>
</body>
</html>
