<?php
session_start();
require_once("connect.php");
require_once("libraryfuncts.php");

// Connect to the database
$db = new Dbh();
$conn = $db->connectDB();

if (!isset($_SESSION['userID'])) {
    header("Location: university_library.php");
    exit();
}

$userID = $_SESSION['userID'];

// Handle the "Return" button action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookID'])) {
    $bookID = intval($_POST['bookID']);

    // Update the book's availability
    $updateSql = "UPDATE books SET is_available = 1 WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    // Insert a new row into the activityHistory table for the "Returned" activity
    $insertSql = "INSERT INTO activityhistory (userID, bookID, activityType) VALUES (?, ?, 'Returned')";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ii", $userID, $bookID);
    $stmt->execute();

    $stmt->close();
}

// Query to find books currently checked out by the user
$sql = "
    SELECT b.*
    FROM books b
    INNER JOIN activityhistory ah ON b.id = ah.bookID
    WHERE ah.userID = ? 
      AND ah.activityType = 'Checked Out'
      AND NOT EXISTS (
          SELECT 1 
          FROM activityhistory ah2 
          WHERE ah2.bookID = ah.bookID 
            AND ah2.activityTimestamp > ah.activityTimestamp
      )
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Display the books in an HTML table
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Account Status</title>';
echo '<style>';
echo 'table { border-collapse: collapse; width: 100%; margin: 20px 0; }';
echo 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
echo 'th { background-color: #f4f4f4; }';
echo 'tr:nth-child(even) { background-color: #f9f9f9; }';
echo 'tr:hover { background-color: #f1f1f1; }';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<h1>Books Currently Checked Out</h1>';

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Title</th>';
    echo '<th>Author</th>';
    echo '<th>Genre</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['author']) . '</td>';
        echo '<td>' . htmlspecialchars($row['genre']) . '</td>';
        echo '<td>';
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="bookID" value="' . htmlspecialchars($row['id']) . '">';
        echo '<button type="submit">Return</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No books currently checked out.</p>';
}
echo ' <a href="logout.php"><button type="submit">Logout</button></a></form></div><br><a href="home.php"><button type="submit">Home</button></a>';
echo '</body>';
echo '</html>';

$stmt->close();
$conn->close();
?>