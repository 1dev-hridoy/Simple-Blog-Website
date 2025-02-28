<?php include 'includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Messages</h3>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Sender
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody id="messageTable" class="bg-white divide-y divide-gray-200">
                        <!-- Messages will be dynamically inserted here -->
                        <tr onclick="openMessageModal('John Doe', 'Hello', 'This is a sample message.', '2025-02-28 05:00:00')">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hello</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2025-02-28 05:00:00</td>
                        </tr>
                        <!-- Add more messages similarly -->
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
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Message Details</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500"><strong>Sender:</strong> <span id="messageSender">John Doe</span></p>
                            <p class="text-sm text-gray-500"><strong>Subject:</strong> <span id="messageSubject">Hello</span></p>
                            <p class="text-sm text-gray-500"><strong>Date:</strong> <span id="messageDate">2025-02-28 05:00:00</span></p>
                            <p class="text-sm text-gray-500 mt-4"><strong>Message:</strong></p>
                            <p class="text-sm text-gray-500" id="messageContent">This is a sample message.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm" onclick="closeMessageModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openMessageModal(sender, subject, content, date) {
    document.getElementById('messageSender').innerText = sender;
    document.getElementById('messageSubject').innerText = subject;
    document.getElementById('messageContent').innerText = content;
    document.getElementById('messageDate').innerText = date;
    document.getElementById('messageModal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
}
</script>

<?php include 'includes/__footer__.php'; ?>