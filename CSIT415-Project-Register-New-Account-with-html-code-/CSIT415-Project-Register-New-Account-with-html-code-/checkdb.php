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

// Handle search functionality
$searchBy = isset($_POST['searchBy']) ? $_POST['searchBy'] : '';
$searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';
$sql = "SELECT * FROM books";

if (!empty($searchBy) && !empty($searchTerm)) {
    $sql .= " WHERE $searchBy LIKE ?";
    $searchTerm = '%' . $searchTerm . '%';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

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

    // Search form
    echo '<form method="POST" action="" style="margin-bottom: 20px;">';
    echo '<label for="searchBy">Search By:</label>';
    echo '<select id="searchBy" name="searchBy" required>';
    echo '<option value="">Select</option>';
    echo '<option value="title"' . ($searchBy === 'title' ? ' selected' : '') . '>Title</option>';
    echo '<option value="author"' . ($searchBy === 'author' ? ' selected' : '') . '>Author</option>';
    echo '<option value="genre"' . ($searchBy === 'genre' ? ' selected' : '') . '>Genre</option>';
    echo '</select>';
    echo '<input type="text" name="searchTerm" placeholder="Enter search term" value="' . htmlspecialchars($searchTerm) . '">';
    echo '<button type="submit">Search</button>';
    echo '</form>';

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
