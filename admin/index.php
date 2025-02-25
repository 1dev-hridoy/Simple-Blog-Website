<?php include 'includes/__header__.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-4 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">Dashboard</h3>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-white shadow-md rounded-lg px-4 py-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75 text-white">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm">Total Posts</p>
                                    <p class="text-2xl font-semibold text-gray-700">12,345</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg px-4 py-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white">
                                    <i class="fas fa-eye fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm">Total Views</p>
                                    <p class="text-2xl font-semibold text-gray-700">$24,680</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg px-4 py-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75 text-white">
                                    <i class="fas fa-message fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm">Total Comments</p>
                                    <p class="text-2xl font-semibold text-gray-700">1,234</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg px-4 py-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-500 bg-opacity-75 text-white">
                                    <i class="fas fa-retweet fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-500 text-sm">Shares</p>
                                    <p class="text-2xl font-semibold text-gray-700">56</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h4 class="text-gray-700 text-lg font-medium mb-4">Sales Overview</h4>
                            <div class="chart-container">
                  
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    Sales Chart Placeholder
                                </div>
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h4 class="text-gray-700 text-lg font-medium mb-4">User Growth</h4>
                            <div class="chart-container">
                    
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    User Growth Chart Placeholder
                                </div>
                            </div>
                        </div>
                    </div>

                
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h4 class="text-gray-700 text-lg font-medium">Recent Orders</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#12345</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$120.00</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#12346</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$75.50</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#12347</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bob Johnson</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$250.00</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<?php include 'includes/__footer__.php'; ?>