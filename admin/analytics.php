<?php include 'includes/__header__.php'; ?>
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-gray-700 text-3xl font-medium mb-6">Post and Visitor Analysis</h3>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form id="filter-form">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="mb-4">
                        <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="filter_type" class="block text-gray-700 text-sm font-bold mb-2">Filter By:</label>
                        <select id="filter_type" name="filter_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="most_visited">Most Visited</option>
                            <option value="most_posts">Most Posts</option>
                        </select>
                    </div>
                    <div class="flex items-end mb-4">
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="applyFilters()">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h4 class="text-gray-700 text-lg font-medium mb-4">Visitor Analysis</h4>
                <canvas id="visitorChart"></canvas>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h4 class="text-gray-700 text-lg font-medium mb-4">Post Analysis</h4>
                <canvas id="postChart"></canvas>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function applyFilters() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const filterType = document.getElementById('filter_type').value;

    // Example data fetching function
    fetchAnalysisData(startDate, endDate, filterType);
}

function fetchAnalysisData(startDate, endDate, filterType) {
    // Replace this with actual data fetching logic
    const visitorData = [12, 19, 3, 5, 2, 3, 10];
    const postData = [7, 11, 5, 8, 3, 7, 12];

    updateVisitorChart(visitorData);
    updatePostChart(postData);
}

function updateVisitorChart(data) {
    const ctx = document.getElementById('visitorChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: 'Visitors',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function updatePostChart(data) {
    const ctx = document.getElementById('postChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: 'Posts',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Initialize with default data
fetchAnalysisData();
</script>

<?php include 'includes/__footer__.php'; ?>