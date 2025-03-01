<?php
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
            <?php if(!empty($posts)): ?>
                <?php foreach($posts as $post): ?>
                <article class="bg-gray-800 rounded-lg overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                <img src="<?php echo htmlspecialchars('../uploads/' . $post['featured_image']); ?>" alt="Featured Image">

                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-white"><?php echo htmlspecialchars($post['title']); ?></h2>
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