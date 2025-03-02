<?php 
include 'includes/__header__.php';
include '../server/dbcon.php';

$current_datetime = "2025-03-02 16:36:22";
$current_user = "hridoy09bg";

try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching messages: " . $e->getMessage();
}
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Messages</h3>
            <span class="text-sm text-gray-500">Total: <?php echo count($messages); ?></span>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="messageTable" class="bg-white divide-y divide-gray-200">
                        <?php if(!empty($messages)): ?>
                            <?php foreach($messages as $message): ?>
                            <tr class="hover:bg-gray-50 cursor-pointer transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($message['name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($message['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('Y-m-d H:i', strtotime($message['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button onclick="openMessageModal(
                                        '<?php echo htmlspecialchars(addslashes($message['name'])); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($message['email'])); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($message['message'])); ?>', 
                                        '<?php echo date('Y-m-d H:i', strtotime($message['created_at'])); ?>'
                                    )" class="text-blue-600 hover:text-blue-900">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No messages found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Message Modal -->
<div id="messageModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Message Details</h3>
                        <div class="mt-2 space-y-3">
                            <p class="text-sm text-gray-500">
                                <strong>From:</strong> 
                                <span id="messageSender"></span>
                                (<span id="messageEmail" class="text-blue-600"></span>)
                            </p>
                            <p class="text-sm text-gray-500">
                                <strong>Date:</strong> 
                                <span id="messageDate"></span>
                            </p>
                            <div class="mt-4">
                                <strong class="text-sm text-gray-500">Message:</strong>
                                <p class="text-sm text-gray-500 mt-2 p-4 bg-gray-50 rounded-lg" id="messageContent"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm" 
                        onclick="closeMessageModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openMessageModal(sender, email, content, date) {
    document.getElementById('messageSender').innerText = sender;
    document.getElementById('messageEmail').innerText = email;
    document.getElementById('messageContent').innerText = content;
    document.getElementById('messageDate').innerText = date;
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('messageModal');
    const modalContent = modal.querySelector('.inline-block');
    if (event.target === modal) {
        closeMessageModal();
    }
});
</script>

<?php include 'includes/__footer__.php'; ?>