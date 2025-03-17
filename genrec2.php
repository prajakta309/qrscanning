<?php

$host = "localhost"; 
$user = "root";
$pass = ""; 
$dbname = "library";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerID = $_POST['customerID'];
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $postCode = $_POST['postCode'];
    $dob = $_POST['dob'];
    
    $bookID = $_POST['bookID'];
    $bookTitle = $_POST['bookTitle'];
    $author = $_POST['author'];
    $rrp = $_POST['rrp'];
    $sellingPrice = $_POST['sellingPrice'];

    $dateBorrowed = $_POST['dateBorrowed'];
    $numberOfDays = $_POST['numberOfDays'];
    $dateDueBack = $_POST['dateDueBack'];
    $dateReturned = $_POST['dateReturned'];
    $dateOverDue = $_POST['dateOverDue'];
    $lateReturnFine = $_POST['lateReturnFine'];

    // Insert data into the database
    $sql = "INSERT INTO library_records (customerID, title, firstName, lastName, address1, address2, postCode, dob, 
            bookID, bookTitle, author, RRP, sellingPrice, 
            dateBorrowed, numberOfDays, dateDueBack, dateReturned, dateOverDue, lateReturnFine) 
            VALUES ('$customerID', '$title', '$firstName', '$lastName', '$address1', '$address2', '$postCode', '$dob', 
            '$bookID', '$bookTitle', '$author', '$rrp', '$sellingPrice', 
            '$dateBorrowed', '$numberOfDays', '$dateDueBack', '$dateReturned', '$dateOverDue', '$lateReturnFine')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data inserted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch data from LibraryRecords table
$sql = "SELECT * FROM library_records ORDER BY DateBorrowed DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="container">
        <div class="header">
            <h1>Library Management System</h1>
    <style>
/* {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .section-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .section {
            flex: 1;
            min-width: 300px;
            max-width: 30%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        .section p {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }

        .section input {
            flex-grow: 1;
            padding: 5px;
            margin-left: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }

        button {
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

         td{
            text-align: center;
         }
        @media (max-width: 900px) {
            .section {
                max-width: 100%;*/
                body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #87CEEB; 
    color: black;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.section-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.section {
    flex: 1;
    min-width: 320px;
    background:#87CEEB; 
    padding: 8px;
    border-radius: 8px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
}

.section h2 {
    text-align: center;
    margin-bottom: 15px;
    color:rgb(15, 12, 16);
}

.section p {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0;
}

.section input {
    flex-grow: auto;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}


/* Responsive */
@media (max-width: 900px) {
    .section {
        max-width: 100%;
    }

    table {
        font-size: 14px;
    }

    th, td {
        padding: 8px;
    }
}
.buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 15px;
}

.borrow-btn, .return-btn {
    padding: 8px 12px;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}


.borrow-btn {
    background-color: #28a745; 
}

.borrow-btn:hover {
    background-color: #1e7e34; 
}


.return-btn {
    background-color: #28a745; 
}

.return-btn:hover {
    background-color: #1e7e34; 
}
.reset-btn{
    background-color: #f44336;
}
.reset-btn:hover{
      background-color:  rgb(192, 62, 58);
}

    </style>
</head>
<body>

<form method="POST" action="" onsubmit="return validateForm()">
    <div class="section-container">
        <div class="section">
            <h2>Library Membership</h2>
            <p>Customer ID: <input type="text" name="customerID"></p>
            <p>Title: 
            <select name="title" id="title">
                  <option value="">-- Select Title --</option>
                  <option value="Miss">Miss</option>
                  <option value="Mr">Mr</option>
                  <option value="Mrs">Mrs</option>
    </select>
</p>
            <p>First Name: <input type="text" name="firstName"></p>
            <p>Last Name: <input type="text" name="lastName"></p>
            <p>Address1: <input type="text" name="address1"></p>
            <p>Address2: <input type="text" name="address2"></p>
            <p>Post Code: <input type="text" name="postCode"></p>
            <p>DOB: <input type="date" name="dob"></p>
        </div>
        <div class="section">
            <h2>Library Books Data</h2>
            <p>Book ID: <input type="text" name="bookID"></p>
            <p>Book Title: <input type="text" name="bookTitle"></p>
            <p>Author: <input type="text" name="author"></p>
            <p>RRP: <input type="text" name="rrp"></p>
            <p>Selling Price: <input type="text" name="sellingPrice"></p>
            <div class="buttons">
                <button type="submit" class="borrow-btn">Borrowed</button>
                <button type="reset" class="borrow-btn">Reset</button>
            </div>
        </div>
        <div class="section">
            <h2>Books Borrowed Details</h2>
            <p>Date Borrowed: <input type="date" name="dateBorrowed"></p>
            <p>Number of Days: <input type="number" name="numberOfDays"></p>
            <p>Date Due Back: <input type="date" name="dateDueBack"></p>
            <p>Date Returned: <input type="date" name="dateReturned"></p>
            <p>Date Over Due: <input type="number" id="dateoverdue" name="dateOverDue" readonly></p>
            <p>Late Return Fine: <input type="text" name="lateReturnFine"></p>


<div class="buttons">
    <button type="submit" class="return-btn">Date Returned</button>
    <button type="button" class="return-btn" onclick="window.location.href='index.php'">Exit</button>
</div>

        </div>
    </div>
</form>
<script>
function validateForm() {
    let title = document.getElementById("title").value;
    let customerID = document.querySelector('input[name="customerID"]').value;
    let bookID = document.querySelector('input[name="bookID"]').value;
    
    // Regular expression: Starts with a capital letter followed by numbers
    let idPattern = /^[A-Z][0-9]{3,}$/;

    if (title === "") {
        alert("Please select a valid title (Miss, Mr, Mrs).");
        return false;
    }

    if (!idPattern.test(customerID)) {
        alert("Customer ID must start with an uppercase letter followed by numbers (e.g., A123).");
        return false;
    }
    
    if (!idPattern.test(bookID)) {
        alert("Book ID must start with an uppercase letter followed by numbers (e.g., B456).");
        return false;
    }

    return true; // Form will submit if all validations pass
}

function calculateOverdue() {
    let dueDate = new Date(document.querySelector('input[name="dateDueBack"]').value);
    let returnDate = new Date(document.querySelector('input[name="dateReturned"]').value);
    let overdueField = document.getElementById("dateoverdue"); // Correct ID

    if (!isNaN(dueDate.getTime()) && !isNaN(returnDate.getTime())) { 
        let timeDiff = returnDate - dueDate;
        let daysOverdue = Math.max(0, Math.ceil(timeDiff / (1000 * 60 * 60 * 24))); // Convert milliseconds to days
        overdueField.value = daysOverdue;  
    } else {
        overdueField.value = "";  // Reset if dates are invalid
    }
}

// Ensure it runs when page loads & when date fields change
window.onload = calculateOverdue;
document.querySelector('input[name="dateDueBack"]').addEventListener("change", calculateOverdue);
document.querySelector('input[name="dateReturned"]').addEventListener("change", calculateOverdue);

</script>
<h3>Library Records</h3>
        <table border="1" cellspacing="0" cellpadding="8">
           <thead>
        <tr>
                <th>Customer ID</th>
                <th>BookID</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>RRP</th>
                <th>SellingPrice</th>
                <th>Title</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Address1</th>
                <th>Address2</th>
                <th>PostCode</th>
                <th>DOB</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['customerID']}</td>
                        <td>{$row['bookID']}</td>
                        <td>{$row['bookTitle']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['RRP']}</td>
                        <td>{$row['sellingPrice']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['firstName']}</td>
                        <td>{$row['lastName']}</td>
                        <td>{$row['address1']}</td>
                        <td>{$row['address2']}</td>
                        <td>{$row['postCode']}</td>
                        <td>{$row['dob']}</td>
                       </tr>";
                }
            } else {
                echo "<tr><td colspan='13' style='text-align:center;> No records found</td></tr>";
            }
            $conn->close(); 
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
