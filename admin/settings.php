<?php include 'includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Settings</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75 text-white">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Author</p>
                        <a href="./edit/profile.php" class="text-2xl font-semibold text-gray-700">About Page</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white">
                        <i class="fas fa-gear fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Seo</p>
                        <a href="./edit/seo.php" class="text-2xl font-semibold text-gray-700">Edit Site Info</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75 text-white">
                        <i class="fas fa-house fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Home Hero</p>
                        <a href="./edit/hero-section.php" class="text-2xl font-semibold text-gray-700">Hero Section</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500 bg-opacity-75 text-white">
                        <i class="fas fa-hand-point-down fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Footer Section</p>
                        <a href="./edit/footer.php" class="text-2xl font-semibold text-gray-700">Site Footer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</div>

<?php include 'includes/__footer__.php'; ?>