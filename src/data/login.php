<?php
session_start();
require "dbconn.php";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $mysqli->prepare("SELECT user_id, password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $password_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $password_hash)) {
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $user_id;
            http_response_code(200);
            //echo "<div class='success'>Login successful!</div>";
            echo '
            <div id="header-login-status" hx-swap-oob="outerHTML">
            <li>
                <a class="header-options" href="user.php">User Page</a>
                <a class="header-options" href="http://">About Us</a>
                <span class="header-username">Logged in as: ' . htmlspecialchars($_SESSION["username"]) . '</span>
                <button class="header-logout-btn" type="button"
                hx-post="data/logout.php"
                hx-target="#header-login-status"
                hx-swap="outerHTML">Log Out</button>
            </div>
            ';
            echo '<div id="login-feedback"></div>';
        } else {
            http_response_code(401);
            echo "<div class='error'>Invalid username or password.</div>";
        }
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
    }
}
