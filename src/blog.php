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
        <div id="comments-container" hx-get="data/comments.php?blog_id=<?php echo intval($_GET['blog_id']); ?>" hx-trigger="load">
            <p>Loading comments...</p>
        </div>
    </div>
    <section id="comment-form-section">
        <form hx-post="data/submit_comment.php" hx-target="#comments-container" hx-swap="afterbegin" hx-on:afterRequest="this.reset()" class="comment-form">
            <input type="hidden" name="blog_id" value="<?php echo intval($_GET['blog_id']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Vieras'; ?>" required>
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" cols="30" rows="10" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </section>
    <?php
    require 'templates/footer.php';
    ?>