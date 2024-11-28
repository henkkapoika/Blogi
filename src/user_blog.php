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
<main>
    <section>
        <div class="user-profile user-blog-page">
            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile picture" class="profile-picture">
            <h2><?php echo htmlspecialchars($username); ?>'s Blog</h2>
        </div>
    </section>
    <section class="user-blog-page" id="user-blogs-section">
        <h3>All Blog Entries</h3>
        <div id="blogs-container" hx-get="data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=1" hx-trigger="load" hx-target="#blogs-container" hx-swap="innerHTML">
            <div class="loader">Loading blogs...</div>
        </div>
        <button id="load-more-blogs"
            data-page="2"
            hx-get="data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=2"
            hx-trigger="click" hx-target="#blogs-container" hx-swap="afterend">Load More</button>
    </section>
    <div class="flexbox">
        <section class="userpage-comments">
            <h3>All Comments</h3>
            <div id="comments-container"
                hx-get="data/fetch_user_comments.php?user_id=<?php echo intval($userId); ?>&page=1"
                hx-trigger="load"
                hx-target="#comments-container"
                hx-swap="innerHTML">
                <div class="loader">Loading comments...</div>
            </div>
            <button id="load-more-comments"
                data-page="2"
                hx-get="data/fetch_user_comments.php?user_id=<?php echo intval($userId); ?>&page=2"
                hx-trigger="click"
                hx-target="#comments-container"
                hx-swap="beforeend">Load More Comments</button>
        </section>

        <section class="user-likes">
            <h3>All Likes</h3>
            <div id="likes-container"
                hx-get="data/fetch_user_likes.php?user_id=<?php echo intval($userId); ?>&page=1"
                hx-trigger="load"
                hx-target="#likes-container"
                hx-swap="innerHTML">
                <div class="loader">Loading likes...</div>
            </div>
            <button id="load-more-likes"
                data-page="2"
                hx-get="data/fetch_user_likes.php?user_id=<?php echo intval($userId); ?>&page=2"
                hx-trigger="click"
                hx-target="#likes-container"
                hx-swap="afterend">Load More Likes</button>
        </section>
    </div>
</main>

<script>
    document.getElementById('load-more-blogs').addEventListener('click', function() {
        let currentPage = parseInt(this.getAttribute('data-page'));

        currentPage++;
        this.setAttribute('hx-get', `data/fetch_user_blogs.php?user_id=<?php echo intval($userId); ?>&page=${currentPage}`);
        this.setAttribute('data-page', currentPage);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreCommentsBtn = document.getElementById('load-more-comments');
        const commentsLoader = document.getElementById('comments-loader');

        if (loadMoreCommentsBtn) {
            loadMoreCommentsBtn.addEventListener('click', function() {
                this.disabled = true;
                commentsLoader.style.display = 'flex';

                let currentPage = parseInt(this.getAttribute('data-page'));
                currentPage++;
                this.setAttribute('data-page', currentPage);
                this.setAttribute('hx-get', `data/fetch_user_comments.php?user_id=<?php echo intval($userId); ?>&page=${currentPage}`);

                this.addEventListener('htmx:afterSwap', function handler(event) {
                    commentsLoader.style.display = 'none';
                    const commentsContainer = document.getElementById('comments-container');
                    const noMoreComments = commentsContainer.querySelector('#no-more-comments');

                    if (noMoreComments) {
                        loadMoreCommentsBtn.style.display = 'none';
                    } else {
                        loadMoreCommentsBtn.disabled = false;
                    }

                    loadMoreCommentsBtn.removeEventListener('htmx:afterSwap', handler);
                });
            });
        }
    });
    
    const loadMoreLikesBtn = document.getElementById('load-more-likes');
    if (loadMoreLikesBtn) {
        loadMoreLikesBtn.addEventListener('click', function() {
            let currentPage = parseInt(this.getAttribute('data-page'));
            currentPage++;
            this.setAttribute('hx-get', `data/fetch_user_all_likes.php?user_id=<?php echo intval($userId); ?>&page=${currentPage}`);
            this.setAttribute('data-page', currentPage);
        });
    }
</script>

<?php
require 'templates/footer.php';
?>