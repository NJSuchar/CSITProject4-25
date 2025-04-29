<?php
session_start();
require_once("connect.php");

// Ensure the user is an admin
if (!isset($_SESSION['userID']) || $_SESSION['isAdmin'] != 1) {
    header("Location: home.php");
    exit();
}

// Connect to the database
$db = new Dbh();
$conn = $db->connectDB();

// Handle adding a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addBook'])) {
    $isbn = $_POST['isbn'];
    $author = $_POST['author'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $publisher = $_POST['publisher'];
    $publishDate = $_POST['publish_date'];
    $pageCount = intval($_POST['page_count']);
    $language = $_POST['language'];
    $userID = $_SESSION['userID'];

    // Insert the new book into the books table
    $insertBookSql = "INSERT INTO books (isbn, author, title, genre, publisher, publish_date, page_count, language, is_available) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($insertBookSql);
    $stmt->bind_param("ssssssis", $isbn, $author, $title, $genre, $publisher, $publishDate, $pageCount, $language);
    $stmt->execute();

    // Get the ID of the newly added book
    $bookID = $conn->insert_id;

    // Insert an activityHistory row for the added book
    $insertHistorySql = "INSERT INTO activityhistory (userID, bookID, activityType) VALUES (?, ?, 'Added Book')";
    $stmt = $conn->prepare($insertHistorySql);
    $stmt->bind_param("ii", $userID, $bookID);
    $stmt->execute();

    $stmt->close();
}

// Handle removing a book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeBook'])) {
    $bookID = intval($_POST['bookID']);
    $userID = $_SESSION['userID'];

    // Update the book's availability to 0
    $updateBookSql = "UPDATE books SET is_available = 0 WHERE id = ?";
    $stmt = $conn->prepare($updateBookSql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    // Insert an activityHistory row for the removed book
    $insertHistorySql = "INSERT INTO activityhistory (userID, bookID, activityType) VALUES (?, ?, 'Removed')";
    $stmt = $conn->prepare($insertHistorySql);
    $stmt->bind_param("ii", $userID, $bookID);
    $stmt->execute();

    $stmt->close();
}

// Fetch all books for the dropdown menu
$booksSql = "SELECT id, title FROM books";
$booksResult = $conn->query($booksSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            margin-bottom: 15px;
            padding: 8px;
            width: 100%;
            max-width: 400px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Manage Books</h1>

    <!-- Add a Book -->
    <h2>Add a Book</h2>
    <form method="POST" action="">
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" required>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" required>

        <label for="publisher">Publisher:</label>
        <input type="text" id="publisher" name="publisher" required>

        <label for="publish_date">Publish Date:</label>
        <input type="date" id="publish_date" name="publish_date" required>

        <label for="page_count">Page Count:</label>
        <input type="number" id="page_count" name="page_count" required>

        <label for="language">Language:</label>
        <input type="text" id="language" name="language" required>

        <button type="submit" name="addBook">Add Book</button>
    </form>

    <!-- Remove a Book -->
    <h2>Remove a Book</h2>
    <form method="POST" action="">
        <label for="bookID">Select a Book:</label>
        <select id="bookID" name="bookID" required>
            <?php while ($row = $booksResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id']); ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="removeBook">Remove Book</button>
    </form>

    <!-- Navigation Buttons -->
    <div>
        <a href="home.php"><button>Home</button></a>
        <a href="logout.php"><button>Log Out</button></a>
    </div>
</body>
</html>