<x-layouts.admin title="Chi tiết đơn hàng #{{ $order->order_number }}">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách đơn hàng
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng #{{ $order->order_number }}</h1>

            <div class="flex space-x-2">
                <a href="{{ route('admin.orders.print', $order) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center" target="_blank">
                    <i class="fas fa-print mr-2"></i> In đơn hàng
                </a>
                <a href="{{ route('admin.orders.edit', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-edit mr-2"></i> Cập nhật trạng thái
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Thông tin đơn hàng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã đơn hàng:</span>
                            <span class="font-medium">#{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày đặt hàng:</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trạng thái đơn hàng:</span>
                            <span>
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
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phương thức thanh toán:</span>
                            <span>
                                @if($order->payment_method == 'cod')
                                    <span class="flex items-center">
                                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i> Thanh toán khi nhận hàng
                                    </span>
                                @elseif($order->payment_method == 'bank')
                                    <span class="flex items-center">
                                        <i class="fas fa-university text-blue-600 mr-1"></i> Chuyển khoản ngân hàng
                                    </span>
                                @elseif($order->payment_method == 'momo')
                                    <span class="flex items-center">
                                        <i class="fas fa-wallet text-pink-600 mr-1"></i> Ví MoMo
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trạng thái thanh toán:</span>
                            <span>
                                @if($order->payment_status)
                                    <span class="text-green-600">Đã thanh toán</span>
                                @else
                                    <span class="text-yellow-600">Chờ xác nhận</span>
                                @endif
                            </span>
                        </div>

                        @if($order->note)
                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="font-medium mb-2">Ghi chú của khách hàng:</h3>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $order->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Thông tin khách hàng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-600">Họ tên:</span>
                            <div class="font-medium">{{ $order->name }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <div>{{ $order->email }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Số điện thoại:</span>
                            <div>{{ $order->phone }}</div>
                        </div>

                        @if($order->user_id)
                            <div class="pt-4 border-t border-gray-200">
                                <span class="text-gray-600">Tài khoản:</span>
                                <div>
                                    <a href="{{ route('admin.users.show', $order->user_id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $order->user->name ?? 'N/A' }} (ID: {{ $order->user_id }})
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="pt-4 border-t border-gray-200">
                                <span class="text-gray-600">Tài khoản:</span>
                                <div class="text-gray-500">Khách vãng lai</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg">Thông tin giao hàng</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-600">Người nhận:</span>
                            <div class="font-medium">{{ $order->name }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Địa chỉ:</span>
                            <div>{{ $order->address }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Tỉnh/Thành phố:</span>
                            <div>{{ $order->city }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Số điện thoại:</span>
                            <div>{{ $order->phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-lg">Sản phẩm đã đặt</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giá
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số lượng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thành tiền
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.products.edit', $item->product) }}" class="hover:text-blue-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                SKU: {{ $item->product->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($item->price) }}đ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item->price * $item->quantity) }}đ</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium">
                                Tạm tính:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{ number_format($order->subtotal) }}đ
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium">
                                Phí vận chuyển:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{ number_format($order->shipping_fee) }}đ
                            </td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold">
                                Tổng cộng:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ number_format($order->total) }}đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-lg">Lịch sử đơn hàng</h2>
            </div>

            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($orderHistory as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($history->status == 'created')
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-shopping-cart text-white"></i>
                                                </span>
                                            @elseif($history->status == 'pending')
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-clock text-white"></i>
                                                </span>
                                            @elseif($history->status == 'processing')
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-truck text-white"></i>
                                                </span>
                                            @elseif($history->status == 'completed')
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white"></i>
                                                </span>
                                            @elseif($history->status == 'cancelled')
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-times text-white"></i>
                                                </span>
                                            @elseif($history->status == 'payment_status_changed')
                                                <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-money-bill-wave text-white"></i>
                                                </span>
                                            @else
                                                <span class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-info text-white"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900">
                                                    @if($history->status == 'created')
                                                        Đơn hàng đã được tạo
                                                    @elseif($history->status == 'pending')
                                                        Đơn hàng đang xử lý
                                                    @elseif($history->status == 'processing')
                                                        Đơn hàng đang được giao
                                                    @elseif($history->status == 'completed')
                                                        Đơn hàng đã được giao
                                                    @elseif($history->status == 'cancelled')
                                                        Đơn hàng đã bị hủy
                                                    @elseif($history->status == 'payment_status_changed')
                                                        @if($history->data['payment_status'])
                                                            Đơn hàng đã được thanh toán
                                                        @else
                                                            Đơn hàng chờ xác nhận
                                                        @endif
                                                    @else
                                                        {{ $history->status }}
                                                    @endif
                                                </p>
                                                @if($history->comment)
                                                    <p class="text-sm text-gray-500">{{ $history->comment }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <div>{{ $history->created_at->format('d/m/Y H:i') }}</div>
                                                @if($history->user_id)
                                                    <div class="text-xs">
                                                        {{ $history->user->name ?? 'N/A' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
            </a>

            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex-1 flex space-x-2">
                @csrf
                @method('PATCH')

                @if($order->status == 'pending')
                    <button type="submit" name="status" value="processing" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-truck mr-2"></i> Đánh dấu đang giao hàng
                    </button>
                @elseif($order->status == 'processing')
                    <button type="submit" name="status" value="completed" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i> Đánh dấu đã giao hàng
                    </button>
                @endif

                @if($order->status != 'completed' && $order->status != 'cancelled')
                    <button type="submit" name="status" value="cancelled" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center justify-center" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        <i class="fas fa-times mr-2"></i> Hủy đơn hàng
                    </button>
                @endif
            </form>

            @if(!$order->payment_status)
                <form action="{{ route('admin.orders.mark-paid', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Đánh dấu đã thanh toán
                    </button>
                </form>
            @else
                <form action="{{ route('admin.orders.mark-unpaid', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-times-circle mr-2"></i> Đánh dấu chờ xác nhận
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-layouts.admin>
