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

    // Validation for ISBN (3 uppercase letters + 3 digits)
    if (!preg_match("/^[A-Z]{3}[0-9]{3}$/", $isbn)) {
        $_SESSION['error'] = "Invalid ISBN format. Use 3 uppercase letters followed by 3 numbers (e.g., ABC123).";
        header("Location: enter_books.php");
        exit();
    }

    // Validation for Edition (only numbers)
    if (!preg_match("/^[0-9]+$/", $edition)) {
        $_SESSION['error'] = "Edition must contain only numbers.";
        header("Location: enter_books.php");
        exit();
    }

    // Insert data into database
    $sql = "INSERT INTO add_books (isbn, title, author, edition, publication) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $isbn, $title, $author, $edition, $publication);

    if ($stmt->execute()) {
        $_SESSION['success'] ;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    $stmt->close();
    header("Location: enter_books.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <style>
        body { 
            margin: 0; 
            padding: 20px;
             background-color: #87CEEB; 
             font-family: Arial, sans-serif;
             }
        .container { 
            max-width: 600px;
             margin: 0 auto; 
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
            let isbnPattern = /^[A-Z]{3}[0-9]{3}$/;
            let editionPattern = /^[0-9]+$/;

            if (!isbnPattern.test(isbn)) {
                alert("Invalid ISBN format. Use 3 uppercase letters followed by 3 numbers (e.g., ABC123).");
                return false;
            }
            if (!editionPattern.test(edition)) {
                alert("Edition must contain only numbers.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Library Management System</h1>

        <?php 
        if (isset($_SESSION['error'])) {
            echo "<p class='message' style='color:red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='message' style='color:green;'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        ?>

        <form action="enter_books.php" method="POST" onsubmit="return validateForm()">
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

</body>
</html>
