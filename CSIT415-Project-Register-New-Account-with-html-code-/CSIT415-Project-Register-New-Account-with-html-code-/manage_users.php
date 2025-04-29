<?php
session_start();
require_once("connect.php");

// Ensure the user is an admin and has a valid session
if (!isset($_SESSION['userID']) || $_SESSION['isAdmin'] != 1) {
    header("Location: home.php");
    exit();
}

// Connect to the database
$db = new Dbh();
$conn = $db->connectDB();

// Handle hold actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $userID = intval($_POST['userID']);
    $action = $_POST['action'];

    if ($action === 'applyHold') {
        // Apply a hold
        $updateSql = "UPDATE users SET holdActive = 1 WHERE userID = ?";
    } elseif ($action === 'removeHold') {
        // Remove a hold
        $updateSql = "UPDATE users SET holdActive = 0 WHERE userID = ?";
    }

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users from the users table
$sql = "SELECT userID, username, finesDue, holdActive FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .apply-hold {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }
        .apply-hold:hover {
            background-color: darkred;
        }
        .remove-hold {
            background-color: green;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }
        .remove-hold:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <h1>Manage Users</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Fines Due</th>
                    <th>Holds Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['userID']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['finesDue']); ?></td>
                        <td><?php echo $row['holdActive'] == 1 ? 'Yes' : 'No'; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="userID" value="<?php echo htmlspecialchars($row['userID']); ?>">
                                <?php if ($row['holdActive'] == 0): ?>
                                    <button type="submit" name="action" value="applyHold" class="apply-hold">Apply Hold</button>
                                <?php else: ?>
                                    <button type="submit" name="action" value="removeHold" class="remove-hold">Remove Hold</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <div>
        <a href="home.php"><button>Home</button></a>
    </div>
</body>
</html>

<?php
$conn->close();
?>