<?php 
ob_start(); // Start output buffering
session_start();
include '../../server/dbcon.php';
include '../includes/__header__.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$alert = []; // Store alert data

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $name = $_POST['name'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $skills = $_POST['skills'] ?? '';
        $experience = $_POST['experience'] ?? '';
        $interests = $_POST['interests'] ?? '';
        
        // Handle image upload
        $image_url = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $image_url = 'uploads/' . $filename;
            } else {
                throw new Exception("Failed to upload image");
            }
        }
        
        // Check if record exists
        $stmt = $pdo->query("SELECT COUNT(*) FROM bio");
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // Update existing record
            $sql = "UPDATE bio SET 
                    name = :name, 
                    bio = :bio, 
                    skills = :skills, 
                    experience = :experience, 
                    interests = :interests, 
                    updated_at = NOW()";
            
            // Only update image if a new one was uploaded
            if ($image_url) {
                $sql .= ", image_url = :image_url";
            }
            
            $stmt = $pdo->prepare($sql);
            $params = [
                ':name' => $name,
                ':bio' => $bio,
                ':skills' => $skills,
                ':experience' => $experience,
                ':interests' => $interests
            ];
            
            if ($image_url) {
                $params[':image_url'] = $image_url;
            }
            
            if ($stmt->execute($params)) {
                $alert = [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Profile updated successfully!'
                ];
            } else {
                throw new Exception("Failed to update profile");
            }
        } else {
            // Insert new record
            $sql = "INSERT INTO bio (name, bio, skills, experience, interests, image_url, created_at, updated_at) 
                    VALUES (:name, :bio, :skills, :experience, :interests, :image_url, NOW(), NOW())";
            
            $stmt = $pdo->prepare($sql);
            $params = [
                ':name' => $name,
                ':bio' => $bio,
                ':skills' => $skills,
                ':experience' => $experience,
                ':interests' => $interests,
                ':image_url' => $image_url
            ];
            
            if ($stmt->execute($params)) {
                $alert = [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Profile created successfully!'
                ];
            } else {
                throw new Exception("Failed to create profile");
            }
        }
        
    } catch (Exception $e) {
        $alert = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => $e->getMessage()
        ];
    }
}

// Get existing profile data if available
try {
    $stmt = $pdo->query("SELECT * FROM bio LIMIT 1");
    $profile_data = $stmt->fetch();
} catch(PDOException $e) {
    $alert = [
        'type' => 'error',
        'title' => 'Error!',
        'message' => "Database error: " . $e->getMessage()
    ];
}
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Edit Profile</h3>
            <?php if (isset($profile_data['updated_at'])): ?>
            <div class="text-sm text-gray-500">
                Last updated: <?php echo $profile_data['updated_at']; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form method="POST" enctype="multipart/form-data">
                <div class="flex flex-col items-center text-center mb-6">
                    <?php 
                    $profile_image = isset($profile_data['image_url']) && !empty($profile_data['image_url']) 
                        ? '../' . $profile_data['image_url'] 
                        : '../assets/img/default-profile.jpg';
                    ?>
                    <img id="profile_image_preview" src="<?php echo htmlspecialchars($profile_image); ?>" class="w-32 h-32 rounded-full shadow mb-4 object-cover" alt="Profile Picture">
                    <input type="file" id="profile_image" name="profile_image" class="hidden" onchange="previewImage(event, 'profile_image_preview')">
                    <label for="profile_image" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Change Profile Picture
                    </label>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo isset($profile_data['name']) ? htmlspecialchars($profile_data['name']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Bio:</label>
                    <textarea id="bio" name="bio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo isset($profile_data['bio']) ? htmlspecialchars($profile_data['bio']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="skills" class="block text-gray-700 text-sm font-bold mb-2">Skills & Expertise:</label>
                    <textarea id="skills" name="skills" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required><?php echo isset($profile_data['skills']) ? htmlspecialchars($profile_data['skills']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="experience" class="block text-gray-700 text-sm font-bold mb-2">Experience:</label>
                    <textarea id="experience" name="experience" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo isset($profile_data['experience']) ? htmlspecialchars($profile_data['experience']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="interests" class="block text-gray-700 text-sm font-bold mb-2">Interests:</label>
                    <textarea id="interests" name="interests" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required><?php echo isset($profile_data['interests']) ? htmlspecialchars($profile_data['interests']) : ''; ?></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
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
    reader.onload = function() {
        if (reader.readyState === 2) {
            preview.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}

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
include '../includes/__footer__.php';
ob_end_flush(); // End output buffering and send output
?>