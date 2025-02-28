<?php 
include '../../server/dbcon.php';

// Get current UTC datetime
$current_datetime = date("Y-m-d H:i:s");
$current_user = "hridoy09bg";

try {
    // Fetch existing hero section data
    $stmt = $pdo->query("SELECT * FROM hero_section LIMIT 1");
    $hero_section = $stmt->fetch();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $description = $_POST['description'];

        if ($hero_section) {
            // Update existing data
            $stmt = $pdo->prepare("UPDATE hero_section SET 
                      title = :title, 
                      description = :description, 
                      updated_at = :updated_at 
                      WHERE id = :id");
            $stmt->bindParam(':id', $hero_section['id']);
        } else {
            // Insert new data
            $stmt = $pdo->prepare("INSERT INTO hero_section (title, description, created_at, updated_at) 
                      VALUES (:title, :description, :created_at, :updated_at)");
            $stmt->bindParam(':created_at', $current_datetime);
        }

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':updated_at', $current_datetime);

        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => 'Record saved successfully!');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to save record!');
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
            <h3 class="text-gray-700 text-3xl font-medium">Edit Hero Section</h3>
            <div class="text-sm text-gray-500">
                Last updated: <?php echo isset($hero_section['updated_at']) ? $hero_section['updated_at'] : 'Never'; ?>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form id="heroForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           value="<?php echo isset($hero_section['title']) ? htmlspecialchars($hero_section['title']) : ''; ?>" 
                           required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" 
                              name="description" 
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                              rows="4" 
                              required><?php echo isset($hero_section['description']) ? htmlspecialchars($hero_section['description']) : ''; ?></textarea>
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
document.getElementById('heroForm').addEventListener('submit', function(e) {
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
