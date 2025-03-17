<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #DFF6F0;
            display: flex;
            height: 100vh;
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
            margin-bottom: 30px;
            font-size: 24px;
            color: #fff;
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
        .main-content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            background-color: #DFF6F0;
            text-align: center;
        }
        button {
            background: #008080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background: #006666;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px; right: 15px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="enter_book.php" class="BookDetails">Book Details</a></li>            
            <li><a href="genrec.php" class="libraryRecords">Library Records</a></li>
            <li><a href="returnbook.php" class="ReturnBook">Return Books</a></li>
        </ul>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    
    <div class="main-content">
        <h1>Welcome to Admin Dashboard</h1>
        <button onclick="generateQRCode()">Generate QR Code</button>
        <div id="qrModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h3>Scan this QR Code</h3>
                <img id="qrImage" src="" alt="QR Code">
                <p id="qrText"></p>
            </div>
        </div>
    </div>
    
    <script>
        function generateQRCode() {
            const qrCodeData = "http://yourwebsite.com/scan_result.php";
            const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200`;
            document.getElementById("qrImage").src = qrCodeUrl;
            document.getElementById("qrText").textContent = qrCodeData;
            document.getElementById("qrModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("qrModal").style.display = "none";
        }
    </script>
</body>
</html>
