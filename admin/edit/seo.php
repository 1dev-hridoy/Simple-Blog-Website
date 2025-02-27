<?php include '../includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">SEO Settings</h3>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="save-seo-settings.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="keywords" class="block text-gray-700 text-sm font-bold mb-2">Keywords:</label>
                    <textarea id="keywords" name="keywords" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author:</label>
                    <input type="text" id="author" name="author" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="og_title" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Title:</label>
                    <input type="text" id="og_title" name="og_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="og_description" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Description:</label>
                    <textarea id="og_description" name="og_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="og_image" class="block text-gray-700 text-sm font-bold mb-2">Open Graph Image:</label>
                    <input type="file" id="og_image" name="og_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required onchange="previewImage(event, 'og_image_preview')">
                    <img id="og_image_preview" class="mt-4 w-full max-w-xs rounded-lg shadow" style="display: none; border-radius: 2px;">
                </div>
                <div class="mb-4">
                    <label for="og_url" class="block text-gray-700 text-sm font-bold mb-2">Open Graph URL:</label>
                    <input type="text" id="og_url" name="og_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="twitter_title" class="block text-gray-700 text-sm font-bold mb-2">Twitter Title:</label>
                    <input type="text" id="twitter_title" name="twitter_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="twitter_description" class="block text-gray-700 text-sm font-bold mb-2">Twitter Description:</label>
                    <textarea id="twitter_description" name="twitter_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="twitter_image" class="block text-gray-700 text-sm font-bold mb-2">Twitter Image:</label>
                    <input type="file" id="twitter_image" name="twitter_image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required onchange="previewImage(event, 'twitter_image_preview')">
                    <img id="twitter_image_preview" class="mt-4 w-full max-w-xs rounded-lg shadow" style="display: none; border-radius: 2px;">
                </div>
                <div class="mb-4">
                    <label for="canonical_url" class="block text-gray-700 text-sm font-bold mb-2">Canonical URL:</label>
                    <input type="text" id="canonical_url" name="canonical_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="schema_data" class="block text-gray-700 text-sm font-bold mb-2">Schema Data (JSON):</label>
                    <textarea id="schema_data" name="schema_data" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6" required></textarea>
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
</div>
</div>

<script>
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
</script>

<?php include '../includes/__footer__.php'; ?>