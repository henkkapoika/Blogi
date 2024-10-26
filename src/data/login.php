<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    require "dbconn.php";

    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $password_hash)) {
            session_start();
            $_SESSION["username"] = $username;
            echo "<div class='success'>Login successful!</div>";
        } else {
            echo "<div class='error'>Invalid username or password.</div>";
        }
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
        exit();
    }
}


?>