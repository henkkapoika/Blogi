<?php
session_start();
if (isset($_SESSION["username"])) {
    echo "<span class='header-username'>Logged in as: " . htmlspecialchars($_SESSION["username"]) . "</span>";
    echo "<button class='header-logout-btn' id='logout-button'>Log Out</button>";
} else {
    echo "<button id='open-login' class='header-btn'>Login</button>";
    echo "<button id='open-register' class='header-btn'>Register</button>";
}
?>

