<?php
//hx-trigger="click"
//hx-swap="outerHTML"
session_start();
if (isset($_SESSION["username"])) {
    echo "<span class='header-username'>Logged in as: " . htmlspecialchars($_SESSION["username"]) . "</span>";
    echo "<button class='header-logout-btn' type='submit' 
            hx-post='data/logout.php' 
            hx-target='#user-state'>Log Out</button>";
} else {
    echo "<button id='open-login' class='header-btn'>Login</button>";
    echo "<button id='open-register' class='header-btn'>Register</button>";
}
?>

