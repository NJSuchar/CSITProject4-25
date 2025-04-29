<?php
session_start();
error_reporting(E_ALL & ~E_DEPRECATED);
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

// Query to fetch all activity history for the logged-in user
$sql = "
    SELECT ah.activityID, ah.bookID, ah.activityType, ah.activityTimestamp, ah.dueDate, 
           b.title AS bookTitle, b.author AS bookAuthor
    FROM activityhistory ah
    LEFT JOIN books b ON ah.bookID = b.id
    WHERE ah.userID = ?
    ORDER BY ah.activityTimestamp DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Display the activity history in an HTML table
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>My Activity History</title>';
echo '<style>';
echo 'table { border-collapse: collapse; width: 100%; margin: 20px 0; }';
echo 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
echo 'th { background-color: #f4f4f4; }';
echo 'tr:nth-child(even) { background-color: #f9f9f9; }';
echo 'tr:hover { background-color: #f1f1f1; }';
echo 'button { background-color: #007BFF; color: white; border: none; padding: 10px 20px; cursor: pointer; }';
echo 'button:hover { background-color: #0056b3; }';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<h1>My Activity History</h1>';

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Activity ID</th>';
    echo '<th>Book ID</th>';
    echo '<th>Book Title</th>';
    echo '<th>Author</th>';
    echo '<th>Activity Type</th>';
    echo '<th>Activity Timestamp</th>';
    echo '<th>Due Date</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['activityID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['bookID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['bookTitle']) . '</td>';
        echo '<td>' . htmlspecialchars($row['bookAuthor']) . '</td>';
        echo '<td>' . htmlspecialchars($row['activityType']) . '</td>';
        echo '<td>' . htmlspecialchars($row['activityTimestamp']) . '</td>';
        echo '<td>' . htmlspecialchars($row['dueDate']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No activity history found.</p>';
}

// Add buttons for returning to the home page and logging out
echo '<div style="margin-top: 20px;">';
echo '<a href="home.php"><button>Home</button></a>';
echo '<a href="logout.php" style="margin-left: 10px;"><button>Logout</button></a>';
echo '</div>';

echo '</body>';
echo '</html>';

$stmt->close();
$conn->close();
?>