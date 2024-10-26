<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    require "dbconn.php";

    $username = htmlspecialchars($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (!empty($username) && !empty($password)) {
        $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->close();

        echo "<div class='success'>User registered successfully!</div>";
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
        exit();
    }

}



?>