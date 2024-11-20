<?php
require "dbconn.php";
session_start();

if(isset($_GET['username'])){
    $username = $_GET['username'];

    $stmt = $mysqli->prepare("SELECT username, profile_picture, created_at FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $stmt->close();

    if(!$user){
        echo "<p class='error'>User not found!</p>";
        exit;
    }

    $stmt = $mysqli->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $statsResult = $stmt->get_result();
    $stats = $statsResult->fetch_assoc();
    $stmt->close();
} else {
    echo "<p class='error'>No user specified!</p>";
    exit;
}
?>
<div class="user-details">
    <div class="user-profile-picture">
        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'uploads/default.png'); ?>" alt="Profile Picture" width="150" height="150">
    </div>
    <h2><?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Member since:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    <p><strong>Comments posted:</strong> <?php echo intval($stats['comment_count']); ?></p>
</div>