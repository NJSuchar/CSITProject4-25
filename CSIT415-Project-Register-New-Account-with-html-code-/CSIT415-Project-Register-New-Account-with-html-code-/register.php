<?php
session_start();
require_once("connect.php");

// Connect to the database
$db = new Dbh();
$conn = $db->connectDB();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the username already exists
    $checkSql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username is already taken. Please choose another one.";
    } else {
        // Insert the new user into the users table
        $insertSql = "INSERT INTO users (username, password, isAdmin, holdActive, finesDue) VALUES (?, ?, 0, 0, 0)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            // Redirect to the login page after successful registration
            header("Location: university_library.php");
            exit();
        } else {
            $error = "An error occurred while creating your account. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .register-container {
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
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Create an Account</h2>
        <p>Sign up to access library resources.</p>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Sign Up">
            </div>
        </form>

        <p>Already have an account? <a href="university_library.php">Log in here</a></p>
    </div>
</body>
</html>
