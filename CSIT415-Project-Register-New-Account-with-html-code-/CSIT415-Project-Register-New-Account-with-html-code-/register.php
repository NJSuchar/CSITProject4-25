<?php
require_once 'Connect.php';
connectDB();

$error = [];
$success = false;

#Registering new account
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #Inputted information
    $username = trim(string: $_POST["username"]);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    #not sure how creation of user vs librarian will be implemented, this is temporary
    $role = trim(string: $_POST["role"]);


    #Input validation
    if (empty($username)) {
        $error[] = "Username is required.";
    } elseif (strlen(string: $username) < 3) {
        $error[] = "Username must be at least 3 characters long.";
    }

    if (empty($password)) {
        $error[] = "Password is required.";
    } elseif (strlen(string: $password) < 6) {
        $error[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirm_password) {
        $error[] = "Passwords do not match.";
    }

    if (empty($role)) {
        $error[] = "Role is required.";
    }

    #Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error[] = "Username already exists.";
    }

    #If no errors, proceed with registration
    if (empty($error)) {
        #Add new user to table
        $stmt = $conn->prepare("INSERT INTO UserAccounts (username, password, role) VALUES (?,?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        
        if ($stmt->execute()) {
            $success = true;
            #Redirect to website after successful registration
            header("Location: university_library.php");
            $conn->close();
            exit();
        } else {
            $error[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - University Library</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="register-container">
        <h2>Create an Account</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-container">
                <?php foreach ($error as $err): ?>
                    <div class="error"><?php echo htmlspecialchars($err); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">Registration successful! Redirecting...</div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small>Must be at least 6 characters long</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role (user/librarian)</label>
                <input type="text" id="role" name="role" required>
            </div>

            <div class="form-group">
                <button type="submit">Register</button>
            </div>
            
            <p>Already have an account? <a href="university_library.php">Login here</a></p>
        </form>
    </div>
</body>
</html>