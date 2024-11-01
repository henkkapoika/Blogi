<?php
//session_start();
require "templates/header.php" 
?>
    </header>
    <main>
        <section class="main-advert">
            <h1>Placeholder</h1>
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