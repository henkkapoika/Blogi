<?php
require "data/function.php";
require 'templates/header.php';

?>
<main>
    <section>
        <div class="">
            <?php
            generateBlogPost();
            ?>
        </div>
    </section>
    <div id="comments-section">
        <h3>Comments</h3>
        <div id="comments-container" hx-get="data/comments.php?blog_id=<?php echo intval($_GET['blog_id']); ?>"
            hx-trigger="load" hx-target="#comments-container" hx-swap="innerHTML">
            <p>Loading comments...</p>
        </div>
    </div>
    <div id="user-details-modal" class="modal2" style="display: none;">
        <div class="modal-content2">
            <span id="close-user-details" class="close2">&times;</span>
            <div id="modal-body">
                <p>Loading user details...</p>
            </div>
        </div>
    </div>
    <section id="comment-form-section">
        <form id="comment-form" hx-post="data/submit_comment.php" hx-target="#comments-container" hx-swap="afterbegin" hx-on:htmx:afterRequest="resetForm()" class="comment-form">
            <input type="hidden" name="blog_id" value="<?php echo intval($_GET['blog_id']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Vieras'; ?>" required>
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" cols="30" rows="10" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </section>
    <script>
        
        // Modal
        document.addEventListener('htmx:beforeRequest', function(evt) {
            if (evt.detail.elt.matches('a[data-username]')) {
                document.getElementById('user-details-modal').style.display = 'block';
            }
        });

        window.onclick = function(event) {
            var modal = document.getElementById('user-details-modal');
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        };

        document.addEventListener('click', function(event) {
            if (event.target.matches('.close2')) {
                document.getElementById('user-details-modal').style.display = 'none';
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                var modal = document.getElementById('user-details-modal');
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
    <?php
    require 'templates/footer.php';
    ?>