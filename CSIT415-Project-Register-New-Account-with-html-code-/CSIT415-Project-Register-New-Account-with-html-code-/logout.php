<?php
// Start the session
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to university_library.php
header("Location: university_library.php");
exit();
?>