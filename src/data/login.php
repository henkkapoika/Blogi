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
            
            echo "<div class='success'>Login successful!</div>";
        } else {
            echo "<div class='error'>Invalid username or password.</div>";
        }
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
    }
}

?>