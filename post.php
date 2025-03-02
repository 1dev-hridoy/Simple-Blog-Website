<?php 
$current_datetime = "2025-03-02 09:01:20";
$current_user = "hridoy09bg";
include 'server/dbcon.php'; 
include 'includes/__header__.php';

// Get URL from either format
$url = '';
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    // Get URL from PATH_INFO if using post.php/slug format
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    if ($path_info) {
        $url = trim($path_info, '/');
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE url = ? OR id = ?");
    $stmt->execute([$url, $url]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header("Location: /blog/404.php");
        exit();
    }
    
    // Convert comma-separated tags to array
    $tags = explode(',', $post['tags']);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<main class="container mx-auto px-4 py-8 mt-20">
    <article class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
        <!-- Blog Post Header -->
        <img src="<?php echo htmlspecialchars('/blog/' . ltrim($post['featured_image'], '/')); ?>" 
     alt="<?php echo htmlspecialchars($post['title']); ?>" 
     class="w-full h-64 object-cover lazyload" 
     loading="lazy">

        
        <div class="p-6 md:p-8">
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-100">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                <div class="flex items-center text-gray-400 text-sm">
                    <span class="mr-4">
                        <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path>
                        </svg>
                        <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    </span>
                    <span class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full">
                        <?php echo htmlspecialchars($post['category']); ?>
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="text-gray-300 mb-6">
                <?php echo htmlspecialchars($post['description']); ?>
            </div>

            <!-- Blog Content - Quill JS HTML Content -->
            <div class="prose prose-invert max-w-none">
                <?php 
                // Display Quill JS HTML content safely
                echo $post['content']; 
                ?>
            </div>

            <!-- Tags -->
            <div class="mt-8 pt-8 border-t border-gray-700">
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
        </div>
    </article>
</main>
<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-80 hidden">
    <div class="relative max-w-4xl w-full">
        <button id="closeModal" class="absolute top-2 right-2 bg-gray-800 hover:bg-gray-700 text-white rounded-full w-10 h-10 flex items-center justify-center z-10 transition">
            <!-- Font Awesome icon with fallback text -->
            <i class="fas fa-times" aria-hidden="true"></i>
            <span class="sr-only">Close</span>
            <!-- Fallback X if icon doesn't load -->
            <span class="absolute inset-0 flex items-center justify-center" aria-hidden="true">×</span>
        </button>
        <img id="modalImage" src="/placeholder.svg" alt="Enlarged image" class="max-h-[90vh] max-w-full object-contain mx-auto rounded-lg shadow-2xl">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.getElementById('closeModal');
    
    // Get all images in the blog content
    const contentImages = document.querySelector('.prose').querySelectorAll('img');
    
    // Add click event to each image
    contentImages.forEach(img => {
        img.classList.add('cursor-pointer', 'transition', 'hover:opacity-90');
        img.addEventListener('click', function() {
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling when modal is open
        });
    });
    
    // Also handle the featured image at the top
    const featuredImage = document.querySelector('article > img');
    if (featuredImage) {
        featuredImage.classList.add('cursor-pointer', 'transition', 'hover:opacity-90');
        featuredImage.addEventListener('click', function() {
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    }
    
    // Close modal when clicking the close button
    closeBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside the image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});
</script>
<?php include 'includes/__footer__.php'; ?>