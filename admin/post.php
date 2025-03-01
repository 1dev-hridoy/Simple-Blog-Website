<?php 
$current_datetime = "2025-03-01 20:59:33";
$current_user = "hridoy09bg";
include 'includes/__header__.php';
include '../server/dbcon.php';

// Handle Delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: list.php?msg=deleted");
        exit();
    } catch(PDOException $e) {
        $error = "Delete failed: " . $e->getMessage();
    }
}

// Fetch Posts
try {
    $stmt = $pdo->query("SELECT id, title, url, featured_image, category, created_at FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching posts: " . $e->getMessage();
    $posts = [];
}
?>

<main class="bg-gray-50 min-h-screen overflow-y-auto">
    <div class="container px-6 py-8 mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="fas fa-blog mr-2"></i>
                Blog Posts
            </h2>
            <a href="add-post.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-plus mr-2"></i>
                Add New Post
            </a>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">Post successfully deleted.</span>
        </div>
        <?php endif; ?>

        <!-- Blog Posts Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($posts as $post): ?>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                <img src="<?php echo htmlspecialchars('../' . $post['featured_image']); ?>" style="width: 80px; height: 70px; object-fit: cover;" alt="Featured Image">


                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-link mr-1"></i>
                                            <?php echo htmlspecialchars($post['url']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($post['category']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-150">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $post['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this post?')"
                                       class="text-red-600 hover:text-red-900 transition duration-150">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="view-post.php?id=<?php echo $post['id']; ?>" 
                                       class="text-green-600 hover:text-green-900 transition duration-150">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php 
include 'includes/__footer__.php';
?>