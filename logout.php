<?php
session_start();
session_destroy();
header("Location: a.php"); // Redirect to home page after logout
exit();
?>
