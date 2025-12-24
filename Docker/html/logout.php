<?php

session_start();
// Remove the user variables
unset($_SESSION["user"]);
unset($_SESSION["id"]);
unset($_SESSION["name"]);

// end whole session
session_destroy();
// go back to the login page
header("Location: index.php");
exit();
?>
