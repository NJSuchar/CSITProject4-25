<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Library - Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button, input[type="submit"] {
            background: #003366;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover, input[type="submit"]:hover {
            background: #002244;
        }
        .library-info {
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="login-container">
    <?php
session_start();
require_once 'Connect.php';
require_once 'libraryfuncts.php';

// Create an instance of the Dbh class and connect to the database
$db = new Dbh();
$conn = $db->connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user credentials
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch userID and set it in session
        $row = $result->fetch_assoc();
        $_SESSION['userID'] = $row['userID'];
        $_SESSION['isAdmin'] = $row['isAdmin'];
        $_SESSION['username'] = $row['username'];
        // User found, redirect to home page
        header("Location: home.php");
        exit();
    } else {
        echo "<div class='error'>Invalid username or password.</div>";
    }
}
?>
        <h2>Welcome to the University Library</h2>
        <p>Log in to access library resources.</p>
        
        <form action="university_library.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a></p>
        </form>

        <div class="library-info">
            <h3>Library Announcements</h3>
            <p>New Books available in the 'Fantasy' Genre!</p>
            <p>Library hours: Mon-Fri 8:00 AM - 10:00 PM, Sat-Sun 9:00 AM - 6:00 PM.</p>
        </div>
    </div>
</body>
</html>
