<?php
require "dbconn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"]) && isset($_POST["newPassword"])) {
    $password = htmlspecialchars($_POST["password"]);
    $newPassword = htmlspecialchars($_POST["newPassword"]);

    if (!empty($password) && !empty($newPassword)) {
        $stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $password_hash)) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $newPasswordHash, $_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();

            echo "<div class='success'>Password changed successfully!</div>";
        } else {
            echo "<div class='error'>Invalid password.</div>";
        }
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
    }
}


?>