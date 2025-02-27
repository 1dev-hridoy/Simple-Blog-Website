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
                        <p class="text-gray-500 text-sm">Hero Section</p>
                        <a href="./edit/hero-section.php" class="text-2xl font-semibold text-gray-700">Home Page</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white">
                        <i class="fas fa-file-pen fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Privacy Settings</p>
                        <a href="privacy-settings.php" class="text-2xl font-semibold text-gray-700">Manage Privacy</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75 text-white">
                        <i class="fas fa-bell fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Notification Settings</p>
                        <a href="notification-settings.php" class="text-2xl font-semibold text-gray-700">Manage Notifications</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500 bg-opacity-75 text-white">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Payment Settings</p>
                        <a href="payment-settings.php" class="text-2xl font-semibold text-gray-700">Manage Payments</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-75 text-white">
                        <i class="fas fa-language fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Language Settings</p>
                        <a href="language-settings.php" class="text-2xl font-semibold text-gray-700">Manage Language</a>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg px-4 py-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-teal-500 bg-opacity-75 text-white">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Other Settings</p>
                        <a href="other-settings.php" class="text-2xl font-semibold text-gray-700">Manage Others</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</div>

<?php include 'includes/__footer__.php'; ?>