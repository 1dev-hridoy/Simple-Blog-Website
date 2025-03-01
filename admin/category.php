<?php 
include '../server/dbcon.php';

// Get current UTC datetime
$current_datetime = date("Y-m-d H:i:s");
$current_user = "hridoy09bg";

try {
    // Handle AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if it's an update or create operation
            if(isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['categoryId'])) {
                $categoryId = $_POST['categoryId'];
                $categoryName = $_POST['categoryName'];
                
                // Update existing category
                $stmt = $pdo->prepare("UPDATE category SET name = :name, updated_at = :updated_at WHERE id = :id");
                $stmt->bindParam(':name', $categoryName);
                $stmt->bindParam(':updated_at', $current_datetime);
                $stmt->bindParam(':id', $categoryId);

                if ($stmt->execute()) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Category updated successfully!',
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'updated_at' => $current_datetime
                    );
                } else {
                    $response = array('status' => 'error', 'message' => 'Failed to update category!');
                }
            } 
            elseif(isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['categoryId'])) {
                $categoryId = $_POST['categoryId'];
                
                // Delete category
                $stmt = $pdo->prepare("DELETE FROM category WHERE id = :id");
                $stmt->bindParam(':id', $categoryId);

                if ($stmt->execute()) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Category deleted successfully!',
                        'id' => $categoryId
                    );
                } else {
                    $response = array('status' => 'error', 'message' => 'Failed to delete category!');
                }
            }
            else {
                // Create new category
                $categoryName = $_POST['categoryName'];
                
                // Insert new category
                $stmt = $pdo->prepare("INSERT INTO category (name, created_at) VALUES (:name, :created_at)");
                $stmt->bindParam(':name', $categoryName);
                $stmt->bindParam(':created_at', $current_datetime);

                if ($stmt->execute()) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Category added successfully!',
                        'id' => $pdo->lastInsertId(),
                        'name' => $categoryName,
                        'created_at' => $current_datetime
                    );
                } else {
                    $response = array('status' => 'error', 'message' => 'Failed to add category!');
                }
            }
            
            echo json_encode($response);
            exit;
        } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
            // Handle search request
            $search = "%" . $_GET['search'] . "%";
            $stmt = $pdo->prepare("SELECT * FROM category WHERE name LIKE :search ORDER BY created_at DESC");
            $stmt->bindParam(':search', $search);
            $stmt->execute();
            $categories = $stmt->fetchAll();
            
            echo json_encode($categories);
            exit;
        }
    }

    // Fetch initial categories for page load
    $stmt = $pdo->query("SELECT * FROM category ORDER BY created_at DESC");
    $categories = $stmt->fetchAll();

} catch(PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>

<?php include './includes/__header__.php'; ?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Category List</h3>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                <input type="text" 
                       id="search" 
                       name="search" 
                       placeholder="Search categories" 
                       class="shadow appearance-none border rounded w-full sm:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4 sm:mb-0">
                <button class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        onclick="openModal()">
                    Add New
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Category Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Created At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="categoryTable" class="bg-white divide-y divide-gray-200">
                        <?php foreach ($categories as $category): ?>
                            <tr id="category-<?php echo $category['id']; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $category['created_at']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button onclick="openEditModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name']); ?>')" 
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="openDeleteModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name']); ?>')" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add New Category Modal -->
<div id="addModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start w-full">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Category</h3>
                        <div class="mt-2 w-full">
                            <input type="text" id="newCategoryName" name="newCategoryName" placeholder="Category Name"
                                class="w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:shadow-outline">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:text-sm"
                    onclick="saveCategory()">
                    Save
                </button>
                <button type="button"
                    class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:text-sm"
                    onclick="closeModal('addModal')">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start w-full">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Category</h3>
                        <div class="mt-2 w-full">
                            <input type="hidden" id="editCategoryId">
                            <input type="text" id="editCategoryName" name="editCategoryName" placeholder="Category Name"
                                class="w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:shadow-outline">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:text-sm"
                    onclick="updateCategory()">
                    Update
                </button>
                <button type="button"
                    class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:text-sm"
                    onclick="closeModal('editModal')">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Category</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this category? <span id="deleteCategoryName" class="font-semibold"></span>
                                This action cannot be undone.
                            </p>
                            <input type="hidden" id="deleteCategoryId">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:text-sm"
                    onclick="deleteCategory()">
                    Delete
                </button>
                <button type="button"
                    class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:text-sm"
                    onclick="closeModal('deleteModal')">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>

function openModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('newCategoryName').value = '';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(id, name) {
    document.getElementById('editCategoryId').value = id;
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editModal').classList.remove('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteCategoryId').value = id;
    document.getElementById('deleteCategoryName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function saveCategory() {
    const categoryName = document.getElementById('newCategoryName').value;
    if (categoryName.trim() === '') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Category name cannot be empty',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }

    const formData = new FormData();
    formData.append('categoryName', categoryName);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Add new row to table
            const tbody = document.getElementById('categoryTable');
            const newRow = `
                <tr id="category-${data.id}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${data.name}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${data.created_at}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="openEditModal(${data.id}, '${data.name}')" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openDeleteModal(${data.id}, '${data.name}')" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            tbody.insertAdjacentHTML('afterbegin', newRow);

            closeModal('addModal');
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
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
}

function updateCategory() {
    const categoryId = document.getElementById('editCategoryId').value;
    const categoryName = document.getElementById('editCategoryName').value;
    
    if (categoryName.trim() === '') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Category name cannot be empty',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('categoryId', categoryId);
    formData.append('categoryName', categoryName);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Update the row in the table
            const row = document.getElementById(`category-${categoryId}`);
            row.querySelector('td:first-child').textContent = categoryName;
            
            closeModal('editModal');
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
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
}

function deleteCategory() {
    const categoryId = document.getElementById('deleteCategoryId').value;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('categoryId', categoryId);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Remove the row from the table
            const row = document.getElementById(`category-${categoryId}`);
            row.remove();
            
            closeModal('deleteModal');
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
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
}

let searchTimeout;
document.getElementById('search').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchValue = e.target.value;
        
        fetch(`${window.location.href}?search=${encodeURIComponent(searchValue)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(categories => {
            const tbody = document.getElementById('categoryTable');
            tbody.innerHTML = categories.map(category => `
                <tr id="category-${category.id}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${category.name}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${category.created_at}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="openEditModal(${category.id}, '${category.name}')" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openDeleteModal(${category.id}, '${category.name}')" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
    }, 300);
});
</script>

<?php include './includes/__footer__.php'; ?>