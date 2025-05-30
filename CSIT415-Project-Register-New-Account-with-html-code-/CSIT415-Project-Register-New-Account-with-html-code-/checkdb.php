<?php
session_start();
// Include the database connection file
require_once("connect.php");
require_once("libraryfuncts.php");

// Connect to the database
$db = new Dbh();
$conn = $db->connectDB();

if (!isset($_SESSION['userID'])) {
    header("Location: university_library.php");
    exit();
}

// Check if the user has an active hold
$userID = $_SESSION['userID'];
$holdCheckSql = "SELECT holdActive FROM users WHERE userID = ?";
$stmt = $conn->prepare($holdCheckSql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($holdActive);
$stmt->fetch();
$stmt->close();

// Check if a book is being checked out
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookID']) && $holdActive == 0) {
    $bookID = intval($_POST['bookID']);
    // Update the book's availability
    $updateSql = "UPDATE books SET is_available = 0 WHERE id = ? AND is_available = 1";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    
    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Calculate the due date (7 days from now)
        $dueDate = date('Y-m-d H:i:s', strtotime('+7 days'));
    
        // Insert a new row into the activityhistory table
        $insertSql = 'INSERT INTO activityhistory (userID, bookID, activityType, dueDate) VALUES (?, ?, "Checked Out", ?)';
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iis", $userID, $bookID, $dueDate);
        $stmt->execute();
    }
    
    $stmt->close();
}

// Query to fetch all data from the 'books' table
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// Check if the query returned any rows
if ($result->num_rows > 0) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Library Catalogue</title>';
    echo '<style>';
    echo 'table { border-collapse: collapse; width: 100%; margin: 20px 0; }';
    echo 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
    echo 'th { background-color: #f4f4f4; }';
    echo 'tr:nth-child(even) { background-color: #f9f9f9; }';
    echo 'tr:hover { background-color: #f1f1f1; }';
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '<h1>Library Catalogue</h1>';

    // Display a message if the user has an active hold
    if ($holdActive == 1) {
        echo '<p style="color: red; font-weight: bold;">Your account has an active hold. You cannot check out books at this time.</p>';
    }

    echo '<table>';
    echo '<thead>';
    echo '<tr>';

    // Fetch column names dynamically
    $fields = $result->fetch_fields();
    foreach ($fields as $field) {
        echo '<th>' . htmlspecialchars($field->name) . '</th>';
    }
    echo '<th>Action</th>'; // Add a column for the action button
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Fetch and display rows
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        foreach ($row as $key => $value) {
            echo '<td>' . htmlspecialchars($value) . '</td>';
        }

        // Add a button if the book is available and the user does not have an active hold
        if ($row['is_available'] == 1 && $holdActive == 0) {
            echo '<td>';
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="bookID" value="' . htmlspecialchars($row['id']) . '">';
            echo '<button type="submit">Check Out</button>';
            echo '</form>';
            echo '</td>';
        } elseif ($holdActive == 1) {
            echo '<td>Hold Active</td>';
        } else {
            echo '<td>Not Available</td>';
        }

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</body>';
    echo '</html>';
} else {
    echo '<p>No records found in the books table.</p>';
}

// Close the database connection
$conn->close();
?>
<a href="logout.php"><button type="submit">Logout</button></a>
<a href="home.php"><button type="submit">Home</button></a>