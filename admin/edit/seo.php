<?php 
ob_start(); // Start output buffering
session_start();
include '../../server/dbcon.php';
include '../includes/__header__.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if database connection is established
if (!isset($pdo)) {
    die("Database connection failed");
}

// Check if there's an alert in the session
$alert = [];
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']); // Clear the alert from session
}

// Get existing SEO data if available
try {
    $stmt = $pdo->query("SELECT * FROM seo LIMIT 1");
    $seo_data = $stmt->fetch();
} catch(PDOException $e) {
    echo "<div style='color:red; padding:10px; background:#ffeeee; border:1px solid red; margin:10px;'>";
    echo "Database error: " . $e->getMessage();
    echo "</div>";
    error_log("Database fetch error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data with basic sanitization
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $og_title = trim($_POST['og_title'] ?? '');
        $og_description = trim($_POST['og_description'] ?? '');
        $og_url = trim($_POST['og_url'] ?? '');
        $twitter_title = trim($_POST['twitter_title'] ?? '');
        $twitter_description = trim($_POST['twitter_description'] ?? '');
        $canonical_url = trim($_POST['canonical_url'] ?? '');
        $schema_data = trim($_POST['schema_data'] ?? '');
        
        // Handle Open Graph image upload
        $og_image_url = null;
        if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['og_image']['name'], PATHINFO_EXTENSION);
            $filename = 'og_image_' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['og_image']['tmp_name'], $target_file)) {
                $og_image_url = 'uploads/' . $filename;
            } else {
                error_log("Failed to upload Open Graph image to: " . $target_file);
            }
        }
        
        // Use existing image if no new one was uploaded
        if ($og_image_url === null && isset($seo_data['og_image'])) {
            $og_image_url = $seo_data['og_image'];
        }
        
        // Handle Twitter image upload
        $twitter_image_url = null;
        if (isset($_FILES['twitter_image']) && $_FILES['twitter_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['twitter_image']['name'], PATHINFO_EXTENSION);
            $filename = 'twitter_image_' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['twitter_image']['tmp_name'], $target_file)) {
                $twitter_image_url = 'uploads/' . $filename;
            } else {
                error_log("Failed to upload Twitter image to: " . $target_file);
            }
        }
        
        // Use existing image if no new one was uploaded
        if ($twitter_image_url === null && isset($seo_data['twitter_image'])) {
            $twitter_image_url = $seo_data['twitter_image'];
        }
        
        // Check if schema_data is empty and provide default if needed
        if (empty($schema_data)) {
            $schema_data = '{}'; // Default empty JSON object
        } else {
            // Validate JSON format
            json_decode($schema_data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON format in Schema Data: " . json_last_error_msg());
            }
        }
        
        // Current timestamp for created_at/updated_at
        $current_timestamp = date('Y-m-d H:i:s');
        
        // Check if record exists
        $stmt = $pdo->query("SELECT COUNT(*) FROM seo");
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // Update existing record using direct SQL
            $sql = "UPDATE seo SET 
                    title = ?, 
                    description = ?, 
                    keywords = ?, 
                    author = ?, 
                    og_title = ?, 
                    og_description = ?, 
                    og_image = ?, 
                    og_url = ?, 
                    twitter_title = ?, 
                    twitter_description = ?, 
                    twitter_image = ?, 
                    canonical_url = ?, 
                    schema_data = ?,
                    updated_at = ?";
            
            $stmt = $pdo->prepare($sql);
            $params = [
                $title,
                $description, 
                $keywords,
                $author,
                $og_title,
                $og_description,
                $og_image_url,
                $og_url,
                $twitter_title,
                $twitter_description,
                $twitter_image_url,
                $canonical_url,
                $schema_data,
                $current_timestamp
            ];
            
            if (!$stmt->execute($params)) {
                throw new Exception("Failed to update SEO settings: " . implode(", ", $stmt->errorInfo()));
            }
            
            echo "<div style='color:green; padding:10px; background:#eeffee; border:1px solid green; margin:10px;'>";
            echo "SEO settings updated successfully!";
            echo "</div>";
            
        } else {
            // Insert new record using direct SQL
            $sql = "INSERT INTO seo (
                    title, description, keywords, author, 
                    og_title, og_description, og_image, og_url, 
                    twitter_title, twitter_description, twitter_image, 
                    canonical_url, schema_data, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $params = [
                $title,
                $description, 
                $keywords,
                $author,
                $og_title,
                $og_description,
                $og_image_url,
                $og_url,
                $twitter_title,
                $twitter_description,
                $twitter_image_url,
                $canonical_url,
                $schema_data,
                $current_timestamp,
                $current_timestamp
            ];
            
            if (!$stmt->execute($params)) {
                throw new Exception("Failed to create SEO settings: " . implode(", ", $stmt->errorInfo()));
            }
            
            echo "<div style='color:green; padding:10px; background:#eeffee; border:1px solid green; margin:10px;'>";
            echo "SEO settings created successfully!";
            echo "</div>";
        }
        
    } catch (Exception $e) {
        // Display error directly on page
        echo "<div style='color:red; padding:10px; background:#ffeeee; border:1px solid red; margin:10px;'>";
        echo "Error: " . $e->getMessage();
        echo "</div>";
        
        error_log("SEO settings error: " . $e->getMessage());
    }
}

// Get the most current data after any updates
try {
    $stmt = $pdo->query("SELECT * FROM seo LIMIT 1");
    $seo_data = $stmt->fetch();
} catch(PDOException $e) {
    echo "<div style='color:red; padding:10px; background:#ffeeee; border:1px solid red; margin:10px;'>";
    echo "Database error: " . $e->getMessage();
    echo "</div>";
}
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">SEO Settings</h3>
            <?php if (isset($seo_data['updated_at'])): ?>
            <div class="text-sm text-gray-500">
                Last updated: <?php echo date('Y-m-d H:i:s', strtotime($seo_data['updated_at'])); ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" enctype="multipart/form-data" id="seoForm">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['title']) ? htmlspecialchars($seo_data['title']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo isset($seo_data['description']) ? htmlspecialchars($seo_data['description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="keywords" class="block text-gray-700 text-sm font-bold mb-2">Keywords:</label>
                    <textarea id="keywords" name="keywords" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required><?php echo isset($seo_data['keywords']) ? htmlspecialchars($seo_data['keywords']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author:</label>
                    <input type="text" id="author" name="author" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['author']) ? htmlspecialchars($seo_data['author']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="og_title" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Title:</label>
                    <input type="text" id="og_title" name="og_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['og_title']) ? htmlspecialchars($seo_data['og_title']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="og_description" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Description:</label>
                    <textarea id="og_description" name="og_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo isset($seo_data['og_description']) ? htmlspecialchars($seo_data['og_description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="og_image" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Image:</label>
                    <?php if (isset($seo_data['og_image']) && !empty($seo_data['og_image'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo '../' . $seo_data['og_image']; ?>" alt="Open Graph Image" class="max-w-xs mb-2">
                            <p class="text-sm text-gray-600">Current image: <?php echo $seo_data['og_image']; ?></p>
                        </div>
                        <input type="file" id="og_image" name="og_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" onchange="previewImage(event, 'og_image_preview')">
                    <?php else: ?>
                        <input type="file" id="og_image" name="og_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" required onchange="previewImage(event, 'og_image_preview')">
                    <?php endif; ?>
                    <img id="og_image_preview" class="mt-4 w-full max-w-xs rounded-lg shadow" style="display: none;">
                </div>
                <div class="mb-4">
                    <label for="og_url" class="block text-gray-700 text-sm font-bold mb-2">Open Graph URL:</label>
                    <input type="text" id="og_url" name="og_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['og_url']) ? htmlspecialchars($seo_data['og_url']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="twitter_title" class="block text-gray-700 text-sm font-bold mb-2">Twitter Title:</label>
                    <input type="text" id="twitter_title" name="twitter_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['twitter_title']) ? htmlspecialchars($seo_data['twitter_title']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="twitter_description" class="block text-gray-700 text-sm font-bold mb-2">Twitter Description:</label>
                    <textarea id="twitter_description" name="twitter_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo isset($seo_data['twitter_description']) ? htmlspecialchars($seo_data['twitter_description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="twitter_image" class="block text-gray-700 text-sm font-bold mb-2">Twitter Image:</label>
                    <?php if (isset($seo_data['twitter_image']) && !empty($seo_data['twitter_image'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo '../' . $seo_data['twitter_image']; ?>" alt="Twitter Image" class="max-w-xs mb-2">
                            <p class="text-sm text-gray-600">Current image: <?php echo $seo_data['twitter_image']; ?></p>
                        </div>
                        <input type="file" id="twitter_image" name="twitter_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" onchange="previewImage(event, 'twitter_image_preview')">
                    <?php else: ?>
                        <input type="file" id="twitter_image" name="twitter_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" required onchange="previewImage(event, 'twitter_image_preview')">
                    <?php endif; ?>
                    <img id="twitter_image_preview" class="mt-4 w-full max-w-xs rounded-lg shadow" style="display: none;">
                </div>
                <div class="mb-4">
                    <label for="canonical_url" class="block text-gray-700 text-sm font-bold mb-2">Canonical URL:</label>
                    <input type="text" id="canonical_url" name="canonical_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($seo_data['canonical_url']) ? htmlspecialchars($seo_data['canonical_url']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="schema_data" class="block text-gray-700 text-sm font-bold mb-2">Schema Data (JSON):</label>
                    <textarea id="schema_data" name="schema_data" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6"><?php echo isset($seo_data['schema_data']) ? htmlspecialchars($seo_data['schema_data']) : '{}'; ?></textarea>
                    <p class="text-sm text-gray-600 mt-1">Enter valid JSON. Example: {"@context":"https://schema.org","@type":"Organization"}</p>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" id="saveButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Image preview functionality
function previewImage(event, previewId) {
    const reader = new FileReader();
    const preview = document.getElementById(previewId);
    
    if (event.target.files && event.target.files[0]) {
        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
}

// Validate JSON before form submission
document.getElementById('seoForm').addEventListener('submit', function(e) {
    const schemaData = document.getElementById('schema_data').value.trim();
    
    if (schemaData && schemaData !== '{}') {
        try {
            JSON.parse(schemaData);
        } catch (error) {
            e.preventDefault();
            alert("Invalid JSON format in Schema Data. Please correct it before submitting.\n\nError: " + error.message);
            document.getElementById('schema_data').focus();
        }
    }
});
</script>

<?php 
include '../includes/__footer__.php';
ob_end_flush(); // End output buffering and send output
?>