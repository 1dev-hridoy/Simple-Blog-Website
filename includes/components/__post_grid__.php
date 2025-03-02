<?php
// Database connection (ensure $pdo is properly initialized before this)
$current_datetime = "2025-03-01 21:25:07";
$current_user = "hridoy09bg";

try {
    $stmt = $pdo->query("SELECT id, title, url, description, featured_image FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch(PDOException $e) {
    $posts = [];
}
?>

<!-- Blog Posts Grid -->
<section class="py-12 px-4">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                <article class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:scale-105 transition-transform duration-300">
                    <!-- Lazy-loaded image with fixed size -->
                    <img src="<?php echo htmlspecialchars('./' . $post['featured_image']); ?>" 
                         alt="Featured Image" 
                         loading="lazy" 
                         class="w-full h-64 object-cover">

                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-white">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h2>
                        <p class="text-gray-400 mb-4">
                            <?php echo strlen($post['description']) > 150 ? 
                                substr(htmlspecialchars($post['description']), 0, 150) . '...' : 
                                htmlspecialchars($post['description']); ?>
                        </p>
                        <a href="post.php?url=<?php echo htmlspecialchars($post['url']); ?>" 
                           class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Read More
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <h3 class="text-xl text-gray-400">No posts found</h3>
                    <p class="text-gray-500 mt-2">Check back later for new content!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- JavaScript for Advanced Lazy Loading (Optional) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let lazyImages = document.querySelectorAll("img[loading='lazy']");
    let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                let img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute("loading");
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => {
        img.dataset.src = img.src;
        img.src = "";
        observer.observe(img);
    });
});
</script>
