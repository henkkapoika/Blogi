<?php
require 'data/dbconn.php';
require 'templates/header.php';

$username = isset($_GET['username']) ? trim($_GET['username']) : '';
if (empty($username)) {
    echo "<p class='error'>No user specified!</p>";
    require 'templates/footer.php';
    exit();
}

$stmtUser = $mysqli->prepare("SELECT user_id, username, profile_picture, created_at FROM users WHERE username = ?");
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();
$stmtUser->close();

if (!$user) {
    echo "<p class='error'>User not found!</p>";
    require 'templates/footer.php';
    exit();
}

$userId = $user['user_id'];
$profilePicture = $user['profile_picture'];
$username = $user['username'];

?>
<style>

main {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}
</style>
<main>
    <section>
        <div class="user-profile">
            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile picture" class="profile-picture">
            <h2><?php echo htmlspecialchars($username); ?>'s Blog</h2>
        </div>
    </section>

    <section id="user-blogs-section">
        <h3>All Blog Entries</h3>
        <div id="blogs-container" hx-get="data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=1" hx-trigger="load" hx-target="#blogs-container" hx-swap="innerHTML">
            <p>Loading blog entries...</p>
        </div>
        <button id="load-more-blogs" 
        data-page="2" 
        hx-get="data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=2" 
        hx-trigger="click" hx-target="#blogs-container" hx-swap="afterend">Load More</button>
    </section>
</main>

<script>
    document.getElementById('load-more-blogs').addEventListener('click', function() {
        let currentPage = parseInt(this.getAttribute('data-page'));

        currentPage++;
        this.setAttribute('hx-get', `data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=${currentPage}`);
        this.setAttribute('data-page', currentPage);
    });
</script>

<?php
require 'templates/footer.php';
?>