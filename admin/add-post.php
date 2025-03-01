<?php 
ob_start(); // Start output buffering
session_start();
include '../server/dbcon.php';
include './includes/__header__.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$alert = []; // Store alert data

// Function to check if URL exists and generate unique URL
function generateUniqueUrl($pdo, $baseUrl) {
    $url = $baseUrl;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE url = ?");
    $stmt->execute([$url]);
    $exists = $stmt->fetchColumn();
    
    if ($exists) {
        do {
            // Generate 3 random digits
            $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $url = $baseUrl . '-' . $random;
            
            $stmt->execute([$url]);
            $exists = $stmt->fetchColumn();
        } while ($exists);
    }
    
    return $url;
}

// Get categories for dropdown
try {
    $stmt = $pdo->query("SELECT * FROM category ORDER BY name ASC");
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    $alert = [
        'type' => 'error',
        'title' => 'Error!',
        'message' => "Failed to fetch categories: " . $e->getMessage()
    ];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $title = $_POST['title'] ?? '';
        $baseUrl = $_POST['url'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';
        $category = $_POST['category'] ?? '';
        $tags = $_POST['tags'] ?? '';
        
        // Generate unique URL
        $url = generateUniqueUrl($pdo, $baseUrl);
        
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
                    <select id="category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['name']); ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="mt-2">
                        <button type="button" onclick="openNewCategoryModal()" class="text-sm text-blue-500 hover:text-blue-700">
                            + Add New Category
                        </button>
                    </div>
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

<!-- New Category Modal -->
<div id="newCategoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add New Category</h3>
                <input type="text" id="newCategoryName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Category Name">
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveNewCategory()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save
                </button>
                <button type="button" onclick="closeNewCategoryModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

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

function openNewCategoryModal() {
    document.getElementById('newCategoryModal').classList.remove('hidden');
}

function closeNewCategoryModal() {
    document.getElementById('newCategoryModal').classList.add('hidden');
    document.getElementById('newCategoryName').value = '';
}

function saveNewCategory() {
    const categoryName = document.getElementById('newCategoryName').value;
    if (!categoryName) {
        alert('Please enter a category name');
        return;
    }

    // Send AJAX request to save new category
    fetch('./includes/__save_category__.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'name=' + encodeURIComponent(categoryName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Add new option to select
            const select = document.getElementById('category');
            const option = new Option(categoryName, categoryName);
            select.add(option);
            select.value = categoryName;
            
            closeNewCategoryModal();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Category added successfully!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
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
ob_end_flush();
?>