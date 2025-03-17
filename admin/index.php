<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "library";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
}

$query = "SELECT id, firstName, lastName, bookTitle, author, Dateborrowed, dateDueBack, dateReturned FROM library_records";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #DFF6F0; display: flex; height: 100vh; }
        .sidebar { width: 250px; background-color: #008080; color: white; padding: 30px 20px; height: 100vh; position: fixed; top: 0; left: 0; }
        .sidebar h2 { text-align: center; }
        .sidebar ul { list-style: none; }
        .sidebar ul li { margin: 20px 0; }
        .sidebar ul li a { display: block; color: white; text-decoration: none; padding: 12px; text-align: center; border-radius: 5px; }
        .sidebar ul li a:hover { background-color: #34495e; }
        .logout { background-color: red; padding: 10px 20px; color: white; border-radius: 50px; text-align: center; display: block; margin-top: 300px; }
        .main-content { margin-left: 250px; padding: 30px; width: 100%; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: center; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-submit { background-color: #4CAF50; color: white; }
        .btn-submit:hover { background-color: rgb(103, 212, 19); }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; border-radius: 10px; text-align: center; position: relative; }
        .close-btn { position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 20px; font-weight: bold; color: red; }
    </style>
    <script>
        function generateQRCode(bookId, firstName, lastName, bookTitle, author, dateBorrowed, dateDueBack, dateReturned) {
            const bookData = `Book: ${bookTitle}\nBorrower: ${firstName} ${lastName}\nAuthor: ${author}\nBorrowed: ${dateBorrowed}\nDue: ${dateDueBack}\nReturned: ${dateReturned || 'Not Returned'}`;
            document.getElementById("qrImage").src = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(bookData)}&size=200x200`;
            document.getElementById("refIdText").innerText = "Ref ID: " + bookId;
            document.getElementById("qrModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("qrModal").style.display = "none";
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
        <li><a href="returnbook.php">Return Books</a></li>
    </ul>
    <a href="logout.php" class="logout">Logout</a>
</div>
<div class="main-content">
    <h1>Welcome to Admin Dashboard</h1>
    <table>
        <tr>
            <th>Book ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>QR Code</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['bookTitle']; ?></td>
            <td><?php echo $row['author']; ?></td>
            <td>
                <button class="scan-btn" data-refid="<?php echo $row['id']; ?>" onclick="generateQRCode(
                    '<?php echo $row['id']; ?>', 
                    '<?php echo addslashes($row['firstName']); ?>', 
                    '<?php echo addslashes($row['lastName']); ?>', 
                    '<?php echo addslashes($row['bookTitle']); ?>', 
                    '<?php echo addslashes($row['author']); ?>', 
                    '<?php echo $row['Dateborrowed']; ?>', 
                    '<?php echo $row['dateDueBack']; ?>', 
                    '<?php echo $row['dateReturned'] ? $row['dateReturned'] : 'Not Returned'; ?>'
                )">Generate QR</button>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<div id="qrModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Scan this QR Code</h3>
        <img id="qrImage" src="" alt="QR Code">
        <p id="refIdText">Ref ID: </p>
    </div>
</div>
</body>
</html>
