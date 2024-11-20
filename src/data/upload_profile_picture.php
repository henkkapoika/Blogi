<?php
require "dbconn.php";
session_start();

if(!isset($_SESSION['username'])){
    echo "<p class='error'>You must log in to upload a profile picture!</p>";
}

$username = $_SESSION['username'];

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['profile_picture'])){
    $uploadDir = "../uploads/";
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = basename($_FILES['profile_picture']['name']);
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = mime_content_type($fileTmpPath);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    if(in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize){
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $username . '_' . uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $destPath)){
            $stmt = $mysqli->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
            $profilePicturePath = "uploads/" . $newFileName;
            $stmt->bind_param("ss", $profilePicturePath, $username);
            $stmt->execute();
            $stmt->close();

            echo "<div id='profile-picture'>";
            echo "<img src='" . htmlspecialchars($profilePicturePath) . "' alt='Profile Picture' width='150' height='150'>";
            echo "</div>";
        } else {
            echo "<p class='error'>There was an error uploading the file. Please try again!</p>";
        }
    } else {
        echo "<p class='error'>Invalid file. Please upload a valid image file (JPEG, PNG, GIF) less than 5MB.</p>";
    }
} else {
    echo "<p class='error'>No file was uploaded!</p>";
}


?>