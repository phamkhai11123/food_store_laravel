<x-layouts.admin title="Quản lý đơn hàng">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Quản lý đơn hàng</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <!-- Search and Filters -->
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <!-- Search -->
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã đơn, tên, email..."
                            class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" id="statusFilter" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Tất cả tt đơn hàng</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <!-- Payment Status Filter -->
                    <div class="relative">
                        <select name="payment_status" id="paymentStatusFilter" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Tất cả tt thanh toán</option>
                            <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>Chờ xác nhận</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Date Range -->
                    <div class="flex items-center space-x-2">
                        <div class="relative">
                            <input type="date" name="from_date" id="fromDate" value="{{ request('from_date') }}"
                                class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm w-full">
                        </div>
                        <span>đến</span>
                        <div class="relative">
                            <input type="date" name="to_date" id="toDate" value="{{ request('to_date') }}"
                                class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm w-full">
                        </div>
                        <button type="button" id="applyDateFilter" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm">
                            <i class="fas fa-filter"></i>
                        </button>
                        <button type="button" id="clearFilters" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-md text-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request('status') || request('payment_status') || request('from_date') || request('to_date') || request('search'))
            <div class="p-3 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Bộ lọc đang áp dụng:</span>

                    @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Tìm kiếm: {{ request('search') }}
                    </span>
                    @endif

                    @if(request('status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Trạng thái:
                        @switch(request('status'))
                            @case('pending')
                                Đang xử lý
                                @break
                            @case('processing')
                                Đang giao hàng
                                @break
                            @case('completed')
                                Đã giao hàng
                                @break
                            @case('cancelled')
                                Đã hủy
                                @break
                            @default
                                {{ request('status') }}
                        @endswitch
                    </span>
                    @endif

                    @if(request('payment_status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Trạng thái thanh toán: {{ request('payment_status') == '1' ? 'Đã thanh toán' : 'Chờ xác nhận' }}
                    </span>
                    @endif

                    @if(request('from_date') || request('to_date'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Thời gian:
                        {{ request('from_date') ? date('d/m/Y', strtotime(request('from_date'))) : 'Bất kỳ' }}
                        đến
                        {{ request('to_date') ? date('d/m/Y', strtotime(request('to_date'))) : 'Bất kỳ' }}
                    </span>
                    @endif
                </div>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tổng tiền
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phương thức TT
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái TT
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày đặt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="order-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $order->id }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($order->total) }}đ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($order->payment_method == 'cod')
                                            <span class="flex items-center">
                                                <i class="fas fa-money-bill-wave text-green-600 mr-1"></i> COD
                                            </span>
                                        @elseif($order->payment_method == 'bank')
                                            <span class="flex items-center">
                                                <i class="fas fa-university text-blue-600 mr-1"></i> Bank
                                            </span>
                                        @elseif($order->payment_method == 'momo')
                                            <span class="flex items-center">
                                                <i class="fas fa-wallet text-pink-600 mr-1"></i> MoMo
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->payment_status)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã thanh toán
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Chờ xác nhận
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Đang xử lý
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Đang giao hàng
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã giao hàng
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Đã hủy
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="text-green-600 hover:text-green-900 mr-3" title="Cập nhật trạng thái">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.print', $order) }}" class="text-purple-600 hover:text-purple-900" title="In đơn hàng" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Không tìm thấy đơn hàng nào.
                </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <span class="mr-2">Với đơn hàng đã chọn:</span>
                            <div class="relative">
                                <select id="bulkAction" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Chọn hành động</option>
                                    <option value="pending">Đánh dấu: Đang xử lý</option>
                                    <option value="processing">Đánh dấu: Đang giao hàng</option>
                                    <option value="completed">Đánh dấu: Đã giao hàng</option>
                                    <option value="cancelled">Đánh dấu: Đã hủy</option>
                                    <option value="mark_paid">Đánh dấu: Đã thanh toán</option>
                                    <option value="mark_unpaid">Đánh dấu: Chờ xác nhận</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <button id="applyBulkAction" class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm">
                                Áp dụng
                            </button>
                        </div>
                    </div>

                    <div>
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filters
            const statusFilter = document.getElementById('statusFilter');
            const paymentStatusFilter = document.getElementById('paymentStatusFilter');
            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const applyDateFilter = document.getElementById('applyDateFilter');

            function applyFilters() {
                const searchParams = new URLSearchParams(window.location.search);

                // Apply status filter
                if (statusFilter.value) {
                    searchParams.set('status', statusFilter.value);
                } else {
                    searchParams.delete('status');
                }

                // Apply payment status filter
                if (paymentStatusFilter.value) {
                    searchParams.set('payment_status', paymentStatusFilter.value);
                } else {
                    searchParams.delete('payment_status');
                }

                // Apply date filters
                if (fromDate.value) {
                    searchParams.set('from_date', fromDate.value);
                } else {
                    searchParams.delete('from_date');
                }

                if (toDate.value) {
                    searchParams.set('to_date', toDate.value);
                } else {
                    searchParams.delete('to_date');
                }

                window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
            }

            statusFilter.addEventListener('change', applyFilters);
            paymentStatusFilter.addEventListener('change', applyFilters);
            applyDateFilter.addEventListener('click', applyFilters);

            // Clear filters
            const clearFilters = document.getElementById('clearFilters');
            clearFilters.addEventListener('click', function() {
                statusFilter.value = '';
                paymentStatusFilter.value = '';
                fromDate.value = '';
                toDate.value = '';

                // Clear the URL parameters and reload the page
                window.location.href = window.location.pathname;
            });

            // Select all checkboxes
            const selectAll = document.getElementById('selectAll');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');

            selectAll.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Bulk actions
            const bulkAction = document.getElementById('bulkAction');
            const applyBulkAction = document.getElementById('applyBulkAction');

            applyBulkAction.addEventListener('click', function() {
                const selectedOrderIds = Array.from(orderCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedOrderIds.length === 0) {
                    alert('Vui lòng chọn ít nhất một đơn hàng!');
                    return;
                }

                if (!bulkAction.value) {
                    alert('Vui lòng chọn một hành động!');
                    return;
                }

                // Submit the form with selected orders and action
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.orders.bulk-action") }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = bulkAction.value;
                form.appendChild(actionInput);

                selectedOrderIds.forEach(id => {
                    const orderInput = document.createElement('input');
                    orderInput.type = 'hidden';
                    orderInput.name = 'order_ids[]';
                    orderInput.value = id;
                    form.appendChild(orderInput);
                });

                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
    @endpush
</x-layouts.admin>
