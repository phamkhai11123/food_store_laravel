<x-layouts.admin title="Thống kê">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Thống kê</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Đơn hàng</h2>
                        <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if ($orderGrowth > 0)
                        <span class="text-green-500 flex items-center font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> +{{ $orderGrowth }}%
                        </span>
                    @elseif($orderGrowth < 0)
                        <span class="text-red-500 flex items-center font-medium">
                            <i class="fas fa-arrow-down mr-1"></i> {{ abs($orderGrowth) }}%
                        </span>
                    @else
                        <span class="text-gray-500 flex items-center font-medium">
                            <i class="fas fa-minus mr-1"></i> {{ $orderGrowth }}%
                        </span>
                    @endif
                    <span class="text-gray-500 ml-2">so với tuần trước</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Doanh thu</h2>
                        <p class="text-2xl font-bold">{{ number_format($totalRevenue) }}đ</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if ($revenueGrowth > 0)
                        <span class="text-green-500 flex items-center font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> +{{ $revenueGrowth }}%
                        </span>
                    @elseif($revenueGrowth < 0)
                        <span class="text-red-500 flex items-center font-medium">
                            <i class="fas fa-arrow-down mr-1"></i> {{ abs($revenueGrowth) }}%
                        </span>
                    @else
                        <span class="text-gray-500 flex items-center font-medium">
                            <i class="fas fa-minus mr-1"></i> {{ $revenueGrowth }}%
                        </span>
                    @endif
                    <span class="text-gray-500 ml-2">so với tuần trước</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Khách hàng</h2>
                        <p class="text-2xl font-bold">{{ $totalCustomers }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if ($customerGrowth > 0)
                        <span class="text-green-500 flex items-center font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> +{{ $customerGrowth }}%
                        </span>
                    @elseif($customerGrowth < 0)
                        <span class="text-red-500 flex items-center font-medium">
                            <i class="fas fa-arrow-down mr-1"></i> {{ abs($customerGrowth) }}%
                        </span>
                    @else
                        <span class="text-gray-500 flex items-center font-medium">
                            <i class="fas fa-minus mr-1"></i> {{ $customerGrowth }}%
                        </span>
                    @endif
                    <span class="text-gray-500 ml-2">so với tuần trước</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-burger text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm">Sản phẩm</h2>
                        <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 flex items-center">
                        <i class="fas fa-plus mr-1"></i> {{ $newProducts }}
                    </span>
                    <span class="text-gray-500 ml-2">sản phẩm mới trong tháng</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-bold text-lg">Đơn hàng gần đây</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Xem
                        tất cả</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đơn hàng
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Khách hàng
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tổng tiền
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày đặt
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($order->total) }}đ</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($order->status == 'pending')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Đang xử lý
                                            </span>
                                        @elseif($order->status == 'processing')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Đang giao hàng
                                            </span>
                                        @elseif($order->status == 'completed')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã giao hàng
                                            </span>
                                        @elseif($order->status == 'cancelled')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Đã hủy
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        Chưa có đơn hàng nào.
                    </div>
                @endif
            </div>

            <!-- Popular Products -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-bold text-lg">Sản phẩm bán chạy</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Xem
                        tất cả</a>
                </div>
                @if ($topProducts->isNotEmpty())
                    <div class="divide-y divide-gray-200">
                        @foreach ($topProducts as $product)
                            <div class="p-4 flex items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-md overflow-hidden">
                                    <img class="h-16 w-16 rounded-md object-cover"
                                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}"
                                        alt="{{ $product->name }}">
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-medium">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600">Đã bán: {{ $product->total_quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">{{ number_format($product->total_revenue) }}đ</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        Chưa có sản phẩm nào.
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">Doanh thu 7 ngày qua</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Order Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">Trạng thái đơn hàng</h2>
                <div class="h-64">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Revenue Chart
                const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                const revenueChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($revenueChartData['labels']) !!},
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: {!! json_encode($revenueChartData['data']) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                                    }
                                }
                            }
                        }
                    }
                });

                // Order Status Chart
                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
                const orderStatusChart = new Chart(orderStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đang xử lý', 'Đang giao hàng', 'Đã giao hàng', 'Đã hủy'],
                        datasets: [{
                            data: [
                                {{ $orderStatusData['pending'] }},
                                {{ $orderStatusData['processing'] }},
                                {{ $orderStatusData['completed'] }},
                                {{ $orderStatusData['cancelled'] }}
                            ],
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layouts.admin>
