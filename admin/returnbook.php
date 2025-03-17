<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "library";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $isbn = $author = $issued_date = $due_date = $returned_date = "";

// Check if ISBN is passed in the URL
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Fetch book and issue details
    $query = "SELECT b.title, b.isbn, b.author, r.dateBorrowed AS issued_date, r.dateDueBack AS due_date, r.dateReturned AS returned_date
              FROM add_books b 
              LEFT JOIN library_records r ON b.isbn = r.isbn 
              WHERE b.isbn = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $title = $row['title']; 
        $isbn = $row['isbn'];
        $author = $row['author'];
        $issued_date = $row['issued_date'];
        $due_date = $row['due_date'];
        $returned_date = date("Y-m-d");
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $author = $_POST['author'];
    $issued_date = $_POST['issued_date'];
    $due_date = $_POST['due_date'];
    $returned_date = $_POST['returned_date'];

    if (empty($title) || empty($isbn) || empty($author) || empty($issued_date) || empty($due_date) || empty($returned_date)) {
        die("Error: Some fields are empty.");
    }

    $insertSql = "INSERT INTO returned_books (title, isbn, author, issued_date, due_date, returned_date) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssssss", $title, $isbn, $author, $issued_date, $due_date, $returned_date);

    if ($insertStmt->execute()) {
        $_SESSION['success'] = "Book return recorded successfully!";
        header("Location: returnbook.php"); // Redirect to admin panel
    exit();
    } else {
        die("Insert Error: " . $insertStmt->error);
    }
    $stmt->close();

 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
</head>
<body>
    <style>
        
            
* {
    margin: 0; 
    padding: 0; 
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
    height: 100vh;
    margin: 0;
    background: url('libraryimage.jpg') no-repeat center center fixed;
    background-size: cover;
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
    
    color: white;

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
    background-color: rgb(189, 0, 0);
}


.main-content {
    margin-left: 250px; 
    padding: 30px; 
    width: 100%;
     
}


.container {
    background: #87CEEB;
    padding: 20px;
    border-radius: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    width: 400px;
    margin: 0 auto;
    height: 60%;

}


h2 {
    text-align: center;
    color: black;
}


.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.form-group label {
    flex: 1;
    font-weight: bold;
    text-align: left;
    padding-right: 10px;
}

.form-group input {
    flex: 2;
    padding: 8px;
    border: none;
    border-radius: 5px;
    background: #f9f9f9;
}

.buttons {
    text-align: center;
    margin-top: 15px;
}

button {
    background: white;
    color: #00796b;
    border: none;
    padding: 10px 15px;
    margin: 5px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #00796b;
    color: white;
}

        </style>
        
        <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php">QR</a></li>
            <li><a href="enter_book.php">Book Details</a></li>
            <li><a href="genrec.php">Library Records</a></li>
            <li><a href="returnbook.php">Return Book</a></li>
        </ul>
        <a href="logout.php" class="logout">Logout</a>
    </div>


</div>
<div class="main-content">
        <div class="container">
            <h2>Return Book</h2>
            <form method="POST" action="returnbook.php">
                <?php


            $fields = [
                "Title" => "title",
                "ISBN Number" => "isbn",
                "Author" => "author",
                "Issued Date" => "issued_date",
                "Due Date" => "due_date",
                "Returned Date" => "returned_date"
            ];
            foreach ($fields as $label => $name) {
                $inputType = in_array($name, ['issued_date', 'due_date', 'returned_date']) ? 'date' : 'text';
                echo "<div class='form-group'>
                      <label for='$name'>$label:</label>
                      <input type='$inputType' id='$name' name='$name' value='" . htmlspecialchars($$name ?? '') . "' required>
                      </div>";
            }
            ?>

            <div class="buttons">
                <button type="submit" name="return_book">Return Book</button>
                <button type="button" onclick="window.location.href='returnbook.php'">Exit</button>
            </div>
        </form>
    </div>
        </div>
</body>
</html>


