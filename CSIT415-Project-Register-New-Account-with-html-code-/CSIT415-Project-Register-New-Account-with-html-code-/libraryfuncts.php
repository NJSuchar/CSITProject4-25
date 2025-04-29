<?php
function checkLogin($conn) {
    session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location: university_library.php");
        exit();
    }
}
?>