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
        <h2 class="text-center">Popular Posts</h2>
        <div id="popular-posts" hx-get="data/popular_posts.php" hx-trigger="load"></div>
    </section>
    <section>
        <div class="main-posts">
            <?php
            generateBlogPosts();
            ?>
        </div>
    </section>
    <script>
        document.addEventListener('htmx:afterSwap', (event) => {
            if (event.detail.target.id === 'popular-posts') {
                initializeCarousel();
            }
        });

        function initializeCarousel() {
            const carouselInner = document.querySelector('.carousel-inner');
            const items = document.querySelectorAll('.carousel-item');
            let currentIndex = 0;
            const totalItems = items.length;

            if (totalItems === 0) return;

            function showSlide(index) {
                carouselInner.style.transform = `translateX(-${index * 100}%)`;
            }

            showSlide(currentIndex);

            let slideInterval = setInterval(nextSlide, 5000);

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalItems;
                showSlide(currentIndex);
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                showSlide(currentIndex);
            }


            window.nextSlide = nextSlide;
            window.prevSlide = prevSlide;

            // Touch device
            const carousel = document.querySelector('.carousel');
            let startX = 0;
            let isSwiping = false;

            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isSwiping = true;
                clearInterval(slideInterval);
            }, false);

            carousel.addEventListener('touchmove', function(e) {
                if (!isSwiping) return;
                let diffX = startX - e.touches[0].clientX;
                if (Math.abs(diffX) > 50) {
                    if (diffX > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                    isSwiping = false;
                }
            }, false);

            carousel.addEventListener('touchend', function() {
                isSwiping = false;
                slideInterval = setInterval(nextSlide, 5000);
            }, false);

            carousel.addEventListener('mouseenter', function() {
                clearInterval(slideInterval);
            });

            carousel.addEventListener('mouseleave', function() {
                slideInterval = setInterval(nextSlide, 5000);
            });
        }
    </script>
    <?php
    require "templates/footer.php";
    ?>