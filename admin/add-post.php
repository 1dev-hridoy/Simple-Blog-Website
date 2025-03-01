<?php 
ob_start(); // Start output buffering
session_start();
include '../server/dbcon.php';
include './includes/__header__.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$alert = []; // Store alert data

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $title = $_POST['title'] ?? '';
        $url = $_POST['url'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';
        $category = $_POST['category'] ?? '';
        $tags = $_POST['tags'] ?? '';
        
        // Handle featured image upload
        $featured_image = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/blog/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
            $filename = 'post_' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
                $featured_image = 'uploads/blog/' . $filename;
            } else {
                throw new Exception("Failed to upload featured image");
            }
        } else {
            throw new Exception("Featured image is required");
        }
        
        // Insert new post
        $sql = "INSERT INTO posts (title, url, description, content, featured_image, category, tags, created_at, updated_at) 
                VALUES (:title, :url, :description, :content, :featured_image, :category, :tags, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $params = [
            ':title' => $title,
            ':url' => $url,
            ':description' => $description,
            ':content' => $content,
            ':featured_image' => $featured_image,
            ':category' => $category,
            ':tags' => $tags
        ];
        
        if ($stmt->execute($params)) {
            $post_id = $pdo->lastInsertId();
            
            // Handle content images if any (from Quill editor)
            // You might want to process the content to find and save any embedded images
            // This is a basic example - you might need to enhance it based on your needs
            
            $alert = [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Post created successfully!'
            ];
            
            // Redirect to posts page after successful creation
            header("Location: ./posts");
            exit();
        } else {
            throw new Exception("Failed to create post");
        }
        
    } catch (Exception $e) {
        $alert = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => $e->getMessage()
        ];
    }
}
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Create Blog Post</h3>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required oninput="generateSlug()">
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-gray-700 text-sm font-bold mb-2">URL:</label>
                    <input type="text" id="url" name="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
                    <div id="editor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="min-height: 300px;"></div>
                    <input type="hidden" id="content" name="content">
                </div>
                <div class="mb-4">
                    <label for="featured_image" class="block text-gray-700 text-sm font-bold mb-2">Featured Image:</label>
                    <input type="file" id="featured_image" name="featured_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required accept="image/*" onchange="previewImage(event, 'featured_image_preview')">
                    <img id="featured_image_preview" class="mt-4 w-full max-w-xs rounded-lg shadow" style="display: none;">
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <input type="text" id="category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags:</label>
                    <input type="text" id="tags" name="tags" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Separate tags with commas">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Publish
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
function generateSlug() {
    const title = document.getElementById('title').value;
    const url = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    document.getElementById('url').value = url;
}

function previewImage(event, previewId) {
    const reader = new FileReader();
    const preview = document.getElementById(previewId);
    reader.onload = function() {
        if (reader.readyState === 2) {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}

document.addEventListener('DOMContentLoaded', function() {
    const toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['link', 'image', 'video'],
        ['clean']
    ];

    const quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    // Set content to hidden input before form submission
    document.querySelector('form').onsubmit = function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    };
});

// Show SweetAlert2 notification if there's an alert
<?php if (!empty($alert)): ?>
Swal.fire({
    icon: '<?php echo $alert['type']; ?>',
    title: '<?php echo $alert['title']; ?>',
    text: '<?php echo $alert['message']; ?>',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
<?php endif; ?>
</script>

<?php 
include './includes/__footer__.php';
ob_end_flush(); // End output buffering and send output
?>