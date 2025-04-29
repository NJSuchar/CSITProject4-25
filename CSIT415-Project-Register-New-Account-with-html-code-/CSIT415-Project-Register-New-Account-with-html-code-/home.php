<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        nav {
            margin: 20px;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }
        nav a:hover {
            color: #007BFF;
        }
        .logout {
            margin-top: 20px;
            text-align: center;
        }
        .logout form {
            display: inline-block;
        }
        .logout button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .logout button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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
?>

    <header>
        <h1>Welcome to the Library System <?php echo $_SESSION['username']; ?></h1>
    </header>
    <nav>
        <a href="checkdb.php">Browse Library</a>
        <a href="account_status.php">Account Status</a>
        <a href="my_history.php">My History</a>
    </nav>
    <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
    <?php 
    if ($_SESSION['isAdmin'] == 1) {
        echo '<div class="admin-panel">';
        echo '<h2>Admin Panel</h2>';
        echo '<a href="manage_books.php">Add/Remove Book</a><br>';
        echo '<a href="manage_users.php">Manage Users</a><br>';
        echo '<a href="view_history.php">View History</a><br>';
        echo '</div>';
    }
    ?>
</body>
</html>