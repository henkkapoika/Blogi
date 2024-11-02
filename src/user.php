<?php


require "templates/header.php";
require "data/dbconn.php";

$stmt = $mysqli->prepare("SELECT * FROM users WHERE user_Id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
</header>
<main>
    <section>
        <div class="user-info">
            <h2>User Information:</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
        </div>
    </section>
    <section>
        <div class="user-blogs">
            <h2>Your Blogs</h2>
            <?php
            require "data/function.php";
            generateUserBlogs();
            ?>
</main>
<?php
require "templates/footer.php";
?>