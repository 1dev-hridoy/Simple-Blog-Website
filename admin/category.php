<?php include 'includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Category List</h3>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                <input type="text" id="search" name="search" placeholder="Search categories" class="shadow appearance-none border rounded w-full sm:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4 sm:mb-0" oninput="searchCategories()">
                <button class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="openModal()">
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
                        </tr>
                    </thead>
                    <tbody id="categoryTable" class="bg-white divide-y divide-gray-200">
                        <!-- Categories will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add New Category Modal -->
<div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
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
                    onclick="closeModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function saveCategory() {
    const categoryName = document.getElementById('newCategoryName').value;
    if (categoryName.trim() === '') {
        alert('Category name cannot be empty');
        return;
    }

    // Add logic to save the category
    // For now, just close the modal
    closeModal();
}

function searchCategories() {
    const searchValue = document.getElementById('search').value.toLowerCase();

    // Add logic to filter and display categories based on search value
    // For now, just log the search value
    console.log('Search for:', searchValue);
}
</script>

<?php include 'includes/__footer__.php'; ?>