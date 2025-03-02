<?php
include 'server/dbcon.php';
include 'includes/__header__.php';

// Search query
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC");
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    }
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}
?>

<main class="container mx-auto px-4 py-8 mt-24">
    <h1 class="text-4xl font-bold mb-8 text-center text-white">Latest Blog Posts</h1>

    <!-- Search Bar -->
    <div class="mb-8 flex justify-center">
        <input type="text" id="searchInput" placeholder="Search posts..." 
            class="px-4 py-2 w-full max-w-lg border border-gray-600 bg-gray-800 text-white rounded-lg focus:border-blue-500 outline-none">
    </div>

    <!-- Blog Post Grid -->
    <div id="postsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:border-blue-500 transition-colors">
                    <a href="post/<?php echo htmlspecialchars($post['url']); ?>">
                        <img src="/blog/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>" 
                             class="w-full h-48 object-cover lazyload" 
                             loading="lazy">
                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-2 text-gray-100">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h2>
                            <p class="text-gray-400 mb-4">
                                <?php echo strlen($post['description']) > 150 
                                    ? substr(htmlspecialchars($post['description']), 0, 150) . '...' 
                                    : htmlspecialchars($post['description']); ?>
                            </p>
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                <span>Category: <?php echo htmlspecialchars($post['category']); ?></span>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-400 col-span-full">No posts found.</p>
        <?php endif; ?>
    </div>
</main>

<script>
document.getElementById("searchInput").addEventListener("input", function() {
    let query = this.value.trim();
    window.location.href = "?q=" + encodeURIComponent(query);
});
</script>

<?php include 'includes/__footer__.php'; ?>
