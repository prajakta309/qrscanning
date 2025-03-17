<?php 
session_start();

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "library"; 
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $edition = $_POST['edition'];
    $publication = $_POST['publication'];

    if (!preg_match("/^[A-Z]{3}[0-9]{3}$/", $isbn)) {
        $_SESSION['error'] = "Invalid ISBN format. Use 3 uppercase letters followed by 3 numbers (e.g., ABC123).";
    } elseif (!preg_match("/^[0-9]+$/", $edition)) {
        $_SESSION['error'] = "Edition must contain only numbers.";
    } else {
        $sql = "INSERT INTO add_books (isbn, title, author, edition, publication) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $isbn, $title, $author, $edition, $publication);
        if ($stmt->execute()) {
            $_SESSION['success'] = "";
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
        $stmt->close();
    }
    header("Location: enter_book.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * { margin: 0; 
            padding: 0; 
            box-sizing: border-box;
         }
        body {
             font-family: Arial, sans-serif;
             display: flex;
              height: 100vh;
              background-color: #DFF6F0;
             }
        .sidebar {
             width: 250px;
             background-color: #008080; 
              color: white; 
              padding: 30px 20px;
               height: 100vh;
                position: fixed; 
                top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            }
        .sidebar h2 { 
            text-align: center; 
        }
        .sidebar ul { 
            list-style: none; 
            padding: 0;
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
              text-align: center; 
              border-radius: 5px; 
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
            display: inline-block;
            text-align: center; 
             margin-top: 300px;
            }
            .logout:hover {
            background-color:rgb(189, 0, 0);
        }
        .main-content {
             margin-left: 250px; 
             padding: 30px; 
             
              width: 100%; 
            }
        .container { 
            max-width: 600px; 
            margin: auto; 
            background: white; 
            padding: 20px;
             border-radius: 8px; 
            }
        h1 { 
            text-align: center;
         }
        .form-group { 
            margin-bottom: 1rem;
         }
        .form-group label { 
            display: block;
             font-weight: bold;
             }
        .form-group input { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        .button-group { 
            text-align: center;
             margin-top: 1rem; 
            }
        .btn {
             padding: 10px 20px;
              border: none; 
              border-radius: 4px; 
              cursor: pointer; 
            }
        .btn-submit { 
            background-color: #4CAF50; 
            color: white; 
        }
        .btn-submit:hover{
            background-color:rgb(103, 212, 19);
        }
        .btn-reset { 
            background-color: #f44336;
             color: white; 
            }
            .btn-reset:hover{
                background-color:rgb(192, 62, 58);
            }
        .message {
             text-align: center;
              font-weight: bold; 
            }
    </style>
    <script>
        function validateForm() {
            let isbn = document.getElementById("isbn").value;
            let edition = document.getElementById("edition").value;
            if (!/^[A-Z]{3}[0-9]{3}$/.test(isbn)) {
                alert("Invalid ISBN format. Use 3 uppercase letters followed by 3 numbers (e.g., ABC123).");
                return false;
            }
            if (!/^[0-9]+$/.test(edition)) {
                alert("Edition must contain only numbers.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index.php">QR</a></li>
            <li><a href="enter_book.php">Book Details</a></li>
            <li><a href="genrec.php">Library Records</a></li>
            <li><a href="returnbook.php">Return Book</a></li>
        </ul>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="main-content">
        <div class="container">
            <h1>Add New Book</h1>
            <?php 
            if (isset($_SESSION['error'])) { echo "<p class='message' style='color:red;'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); }
            if (isset($_SESSION['success'])) { echo "<p class='message' style='color:green;'>" . $_SESSION['success'] . "</p>"; unset($_SESSION['success']); }
            ?>
            <form action="enter_book.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" required>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="edition">Edition:</label>
                    <input type="text" id="edition" name="edition" required>
                </div>
                <div class="form-group">
                    <label for="publication">Publication:</label>
                    <input type="text" id="publication" name="publication">
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-submit">Submit</button>
                    <button type="reset" class="btn btn-reset">Reset</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>