<?php 
include '../../server/dbcon.php';

// Get current UTC datetime
$current_datetime = date("Y-m-d H:i:s");
$current_user = "hridoy09bg";

try {
    // Fetch existing footer values
    $stmt = $pdo->query("SELECT * FROM footer_values LIMIT 1");
    $footer_values = $stmt->fetch();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $twitter_url = $_POST['twitter_url'];
        $github_url = $_POST['github_url'];
        $linkedin_url = $_POST['linkedin_url'];
        $copyright_text = $_POST['copyright_text'];

        if ($footer_values) {
            // Update existing data
            $stmt = $pdo->prepare("UPDATE footer_values SET 
                      twitter_url = :twitter_url,
                      github_url = :github_url,
                      linkedIn_url = :linkedin_url,
                      copyright_txt = :copyright_text,
                      updated_at = :updated_at 
                      WHERE id = :id");
            $stmt->bindParam(':id', $footer_values['id']);
        } else {
            // Insert new data
            $stmt = $pdo->prepare("INSERT INTO footer_values 
                      (twitter_url, github_url, linkedIn_url, copyright_txt, created_at, updated_at) 
                      VALUES 
                      (:twitter_url, :github_url, :linkedin_url, :copyright_text, :created_at, :updated_at)");
            $stmt->bindParam(':created_at', $current_datetime);
        }

        $stmt->bindParam(':twitter_url', $twitter_url);
        $stmt->bindParam(':github_url', $github_url);
        $stmt->bindParam(':linkedin_url', $linkedin_url);
        $stmt->bindParam(':copyright_text', $copyright_text);
        $stmt->bindParam(':updated_at', $current_datetime);

        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => 'Footer settings saved successfully!');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to save footer settings!');
        }

        // If it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode($response);
            exit;
        }
    }
} catch(PDOException $e) {
    $response = array('status' => 'error', 'message' => "Error: " . $e->getMessage());
    echo json_encode($response);
    exit;
}
?>

<?php include '../includes/__header__.php'; ?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Footer Settings</h3>
            <div class="text-sm text-gray-500">
                Last updated: <?php echo isset($footer_values['updated_at']) ? $footer_values['updated_at'] : 'Never'; ?>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form id="footerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-4">
                    <label for="twitter_url" class="block text-gray-700 text-sm font-bold mb-2">Twitter URL:</label>
                    <input type="url" 
                           id="twitter_url" 
                           name="twitter_url" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           value="<?php echo isset($footer_values['twitter_url']) ? htmlspecialchars($footer_values['twitter_url']) : ''; ?>"
                           required>
                </div>
                <div class="mb-4">
                    <label for="github_url" class="block text-gray-700 text-sm font-bold mb-2">GitHub URL:</label>
                    <input type="url" 
                           id="github_url" 
                           name="github_url" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           value="<?php echo isset($footer_values['github_url']) ? htmlspecialchars($footer_values['github_url']) : ''; ?>"
                           required>
                </div>
                <div class="mb-4">
                    <label for="linkedin_url" class="block text-gray-700 text-sm font-bold mb-2">LinkedIn URL:</label>
                    <input type="url" 
                           id="linkedin_url" 
                           name="linkedin_url" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           value="<?php echo isset($footer_values['linkedIn_url']) ? htmlspecialchars($footer_values['linkedIn_url']) : ''; ?>"
                           required>
                </div>
                <div class="mb-4">
                    <label for="copyright_text" class="block text-gray-700 text-sm font-bold mb-2">Copyright Text:</label>
                    <input type="text" 
                           id="copyright_text" 
                           name="copyright_text" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           value="<?php echo isset($footer_values['copyright_txt']) ? htmlspecialchars($footer_values['copyright_txt']) : ''; ?>"
                           required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Changes
                    </button>
                    <div class="text-sm text-gray-500">
                        Last edited by: <?php echo $current_user; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.getElementById('footerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // Reload the page to show updated data
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An unexpected error occurred',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
});
</script>

<?php include '../includes/__footer__.php'; ?>