<?php include '../includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Footer Settings</h3>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="save-footer-settings.php" method="POST">
                <div class="mb-4">
                    <label for="twitter_url" class="block text-gray-700 text-sm font-bold mb-2">Twitter URL:</label>
                    <input type="url" id="twitter_url" name="twitter_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="github_url" class="block text-gray-700 text-sm font-bold mb-2">GitHub URL:</label>
                    <input type="url" id="github_url" name="github_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="linkedin_url" class="block text-gray-700 text-sm font-bold mb-2">LinkedIn URL:</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="copyright_text" class="block text-gray-700 text-sm font-bold mb-2">Copyright Text:</label>
                    <input type="text" id="copyright_text" name="copyright_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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

<?php include '../includes/__footer__.php'; ?>