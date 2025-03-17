<?php
include("db_connect.php"); // Ensure database connection

// Fetch book details from the database
$query = "SELECT title, author, date_borrowed, date_due_back, date_returned FROM library_records ORDER BY date_borrowed DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Check if records exist
if (mysqli_num_rows($result) > 0) {
    $book = mysqli_fetch_assoc($result);
} else {
    $book = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 50px;
        }
        h2 {
            color: #008080;
        }
        p {
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book Details</h2>
        <?php if ($book): ?>
            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Date Borrowed:</strong> <?php echo htmlspecialchars($book['date_borrowed']); ?></p>
            <p><strong>Date Due Back:</strong> <?php echo htmlspecialchars($book['date_due_back']); ?></p>
            <p><strong>Date Returned:</strong> <?php echo $book['date_returned'] ? htmlspecialchars($book['date_returned']) : "Not Returned Yet"; ?></p>
        <?php else: ?>
            <p>No book records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
