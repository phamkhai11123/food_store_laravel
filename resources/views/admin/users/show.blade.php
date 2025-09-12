<x-layouts.admin title="Chi tiết người dùng - {{ $user->name }}">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách người dùng
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết người dùng</h1>

            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                </a>
                @if (\Illuminate\Support\Facades\Auth::id() != $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> Xóa
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Thông tin người dùng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-600">ID: {{ $user->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Họ tên: {{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email: {{ $user->email }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Số điện thoại: {{ $user->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Địa chỉ: {{ $user->address ?? 'Chưa cập nhật' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Vai trò:
                                @if ($user->role == 'admin')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Admin
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Người dùng
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Tài khoản tạo lúc: {{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Statistics -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Thống kê đơn hàng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-blue-600 text-sm font-medium">Tổng đơn hàng</div>
                                <div class="text-2xl font-bold mt-1">{{ $orderStats['total'] }}</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-green-600 text-sm font-medium">Đã giao hàng</div>
                                <div class="text-2xl font-bold mt-1">{{ $orderStats['completed'] }}</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="text-yellow-600 text-sm font-medium">Đang xử lý</div>
                                <div class="text-2xl font-bold mt-1">{{ $orderStats['pending'] }}</div>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <div class="text-red-600 text-sm font-medium">Đã hủy</div>
                                <div class="text-2xl font-bold mt-1">{{ $orderStats['cancelled'] }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-600 text-sm font-medium mb-2">Tổng chi tiêu</div>
                            <div class="text-2xl font-bold text-green-600">
                                {{ number_format($orderStats['total_spent']) }}đ</div>
                        </div>

                        <div>
                            <div class="text-gray-600 text-sm font-medium mb-2">Đơn hàng gần đây</div>
                            @if (count($recentOrders) > 0)
                                <div class="space-y-2">
                                    @foreach ($recentOrders as $order)
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="block bg-gray-50 p-3 rounded-md hover:bg-gray-100">
                                            <div class="flex justify-between">
                                                <div class="font-medium">#{{ $order->order_number }}</div>
                                                <div class="text-gray-500 text-sm">
                                                    {{ $order->created_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <div class="text-gray-500 text-sm">
                                                    @if ($order->status == 'pending')
                                                        <span class="text-yellow-600">Đang xử lý</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="text-blue-600">Đang giao hàng</span>
                                                    @elseif($order->status == 'completed')
                                                        <span class="text-green-600">Đã giao hàng</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="text-red-600">Đã hủy</span>
                                                    @endif
                                                </div>
                                                <div class="font-medium">{{ number_format($order->total) }}đ</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Xem tất cả đơn hàng <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            @else
                                <div class="text-gray-500 text-sm">Người dùng chưa có đơn hàng nào</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Đánh giá gần đây</h2>
                </div>
                <div class="p-6">
                    @if (count($reviews) > 0)
                        <div class="space-y-4">
                            @foreach ($reviews as $review)
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="flex justify-between">
                                        <div class="font-medium">{{ $review->product->name }}</div>
                                        <div class="text-gray-500 text-sm">{{ $review->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-600 text-sm">{{ $review->rating }}/5</span>
                                    </div>
                                    <div class="mt-2 text-gray-700 text-sm">
                                        {{ $review->comment }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">Người dùng chưa có đánh giá nào</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-lg">Lịch sử đơn hàng</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày đặt
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thanh toán
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tổng tiền
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if ($order->payment_method == 'cod')
                                            <span>COD</span>
                                        @elseif($order->payment_method == 'bank')
                                            <span>Chuyển khoản</span>
                                        @elseif($order->payment_method == 'momo')
                                            <span>MoMo</span>
                                        @endif

                                        @if ($order->payment_status)
                                            <span class="ml-1 text-green-600">(Đã thanh toán)</span>
                                        @else
                                            <span class="ml-1 text-yellow-600">(Chờ xác nhận)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($order->total) }}đ
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-900">Chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Người dùng chưa có đơn hàng nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($orders) > 0)
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
