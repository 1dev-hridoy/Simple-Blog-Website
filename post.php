<?php 
$current_datetime = "2025-03-02 16:52:54";
$current_user = "hridoy09bg";
include 'server/dbcon.php'; 
include 'includes/__header__.php';

// Get URL from either format
$url = '';
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    if ($path_info) {
        $url = trim($path_info, '/');
    }
}

try {
    // Get post details
    $stmt = $pdo->prepare("SELECT posts.*, 
        (SELECT COUNT(DISTINCT user_ip) FROM post_views WHERE post_id = posts.id) as view_count 
        FROM posts 
        WHERE url = ? OR id = ?");
    $stmt->execute([$url, $url]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header("Location: /blog/404.php");
        exit();
    }
    
    // Get user's real IP address
    function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    $user_ip = getRealIpAddr();
    
    // Record view if not already viewed today
    $stmt = $pdo->prepare("INSERT INTO post_views (post_id, user_ip, viewed_at) 
                          SELECT ?, ?, ? 
                          WHERE NOT EXISTS (
                              SELECT 1 
                              FROM post_views 
                              WHERE post_id = ? 
                              AND user_ip = ? 
                              AND DATE(viewed_at) = CURRENT_DATE
                          )");
    $stmt->execute([
        $post['id'], 
        $user_ip, 
        $current_datetime, 
        $post['id'], 
        $user_ip
    ]);
    
    // Convert tags to array
    $tags = !empty($post['tags']) ? explode(',', $post['tags']) : [];
    
} catch(PDOException $e) {
    error_log("Error in post.php: " . $e->getMessage());
    header("Location: /blog/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($post['description']); ?>">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($post['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($post['description']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars('/blog/' . ltrim($post['featured_image'], '/')); ?>">
    
    <!-- Add your CSS here -->
    <link rel="stylesheet" href="/blog/assets/css/style.css">
</head>
<body class="bg-gray-900 text-white">
    <main class="container mx-auto px-4 py-8 mt-20">
        <article class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            <!-- Featured Image -->
            <div class="relative">
                <img src="<?php echo htmlspecialchars('/blog/' . ltrim($post['featured_image'], '/')); ?>" 
                     alt="<?php echo htmlspecialchars($post['title']); ?>" 
                     class="w-full h-64 object-cover cursor-pointer transition-opacity hover:opacity-90"
                     onclick="openImageModal(this.src)">
                
                <!-- View Count Badge -->
                <div class="absolute top-4 right-4 bg-black/70 px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span><?php echo number_format($post['view_count']); ?></span>
                </div>
            </div>
            
            <div class="p-6 md:p-8">
                <!-- Post Header -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
                        <!-- Date -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                            </svg>
                            <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                        </div>
                        
                        <!-- Category -->
                        <span class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full">
                            <?php echo htmlspecialchars($post['category']); ?>
                        </span>
                        
                        <!-- Last Updated -->
                        <div class="text-gray-500">
                            Updated: <?php echo date('M j, Y', strtotime($post['updated_at'])); ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="text-gray-300 mb-8 text-lg leading-relaxed">
                    <?php echo htmlspecialchars($post['description']); ?>
                </div>

                <!-- Content -->
                <div class="prose prose-invert max-w-none mb-8">
                    <?php echo $post['content']; ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="border-t border-gray-700 pt-6">
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($tags as $tag): ?>
                            <?php if(trim($tag) !== ''): ?>
                            <span class="px-3 py-1 bg-gray-700 rounded-full text-sm text-gray-300">
                                #<?php echo htmlspecialchars(trim($tag)); ?>
                            </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </article>
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white p-2 hover:bg-white/10 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-h-[90vh] max-w-full object-contain">
        </div>
    </div>

    <script>
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeImageModal();
    });

    // Close modal when clicking outside the image
    document.getElementById('imageModal').addEventListener('click', (e) => {
        if (e.target.id === 'imageModal') closeImageModal();
    });
    </script>

<?php include 'includes/__footer__.php'; ?>
</body>
</html>