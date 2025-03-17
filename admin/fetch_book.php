<?php
session_start();
include 'db_connect.php'; // Database connection

if (!isset($_GET['book_id'])) {
    echo json_encode(["status" => "error", "message" => "No book ID provided"]);
    exit;
}

$book_id = intval($_GET['book_id']);
$query = "SELECT title, author, issued_date, borrowed_date, due_date FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $book]);
} else {
    echo json_encode(["status" => "error", "message" => "Book not found"]);
}

$stmt->close();
$conn->close();
?>
