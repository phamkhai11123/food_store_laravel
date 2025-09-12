<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Thông tin tài khoản</h1>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6">
                    <div class="flex items-center justify-between pb-4 border-b">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-700">Thông tin cá nhân</h2>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-300">
                            Chỉnh sửa
                        </a>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Họ tên</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Số điện thoại</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Địa chỉ</label>
                                <p class="mt-1 text-lg text-gray-900">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="pb-4 border-b">
                        <h2 class="text-xl font-semibold text-gray-700">Đơn hàng gần đây</h2>
                    </div>

                    <div class="mt-4">
                        @if($user->orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->orders->take(5) as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->order_number }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->total) }}đ</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($order->status == 'completed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Hoàn thành
                                                        </span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Đang xử lý
                                                        </span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Đã hủy
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Chờ xử lý
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('shop.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">
                                                        Chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('shop.orders.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Xem tất cả đơn hàng
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Bạn chưa có đơn hàng nào</p>
                                <a href="{{ route('products.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-300">
                                    Mua sắm ngay
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
