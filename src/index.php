<?php
//session_start();
require "templates/header.php" 
?>
    </header>
    <main>
        <section class="main-advert index-main">
            <div class="">
                <a href="publish.php">
                <button>Publish your blog!</button>
            </a>
            </div>
        </section>
        <section>
            <div class="main-posts">
            <?php
                generateBlogPosts();
            ?>
            </div>
        </section>
<?php
require "templates/footer.php";
?>