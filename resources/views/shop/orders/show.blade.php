<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('shop.orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách đơn hàng
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng #{{ $order->order_number }}</h1>

                @if($order->status === 'pending')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Đang xử lý
                    </span>
                @elseif($order->status === 'processing')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Đang giao hàng
                    </span>
                @elseif($order->status === 'completed')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                        Đã giao hàng
                    </span>
                @elseif($order->status === 'cancelled')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Đã hủy
                    </span>
                @endif
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Thông tin đơn hàng</h2>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Mã đơn hàng:</span> {{ $order->order_number }}</p>
                            <p><span class="font-medium">Ngày đặt hàng:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            {{-- trạng thái đơn hàng --}}
                            <p><span class="font-medium">Trạng thái đơn hàng:</span>
                                @if($order->status === 'pending') Đang xử lý
                                @elseif($order->status === 'processing') Đang giao hàng
                                @elseif($order->status === 'completed') Đã giao hàng
                                @elseif($order->status === 'cancelled') Đã hủy
                                @else {{ $order->status }}
                                @endif
                            <p><span class="font-medium">Phương thức thanh toán:</span>
                                @if($order->payment_method === 'cod') Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method === 'bank_transfer') Chuyển khoản ngân hàng
                                @elseif($order->payment_method === 'momo') Ví MoMo
                                @else {{ $order->payment_method }}
                                @endif
                            </p>
                            <p><span class="font-medium">Trạng thái thanh toán:</span>
                                @if($order->payment_status == 1)
                                    <span class="text-green-600">Đã thanh toán</span>
                                @else
                                    <span class="text-yellow-600">Chờ xác nhận</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Thông tin giao hàng</h2>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->name }}</p>
                            <p><span class="font-medium">Số điện thoại:</span> {{ $order->phone }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->address }}</p>
                            <p><span class="font-medium">Ghi chú:</span> {{ $order->note ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <h2 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Chi tiết sản phẩm</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                                @if($order->status === 'completed')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đánh giá</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if($item->product)
                                                    <img class="h-12 w-12 rounded-md object-cover" src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}">
                                                @else
                                                    <div class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name ?? 'Sản phẩm không có sẵn' }}</div>
                                                @if($item->options)
                                                    <div class="text-xs text-gray-500">
                                                        @foreach(json_decode($item->options, true) ?? [] as $key => $value)
                                                            <span>{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                    @if($order->status === 'completed')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($item->review)
                                            <a href="{{ route('shop.reviews.edit', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition duration-200">
                                                <i class="fas fa-edit mr-1"></i> Chỉnh sửa đánh giá
                                            </a>
                                        @else
                                            <a href="{{ route('shop.reviews.create', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition duration-200">
                                                <i class="fas fa-star mr-1"></i> Đánh giá
                                            </a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2 text-sm">
                                <span class="font-medium">Tạm tính:</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="flex justify-between py-2 text-sm">
                                <span class="font-medium">Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee+30000, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span>{{ number_format($order->total+30000, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($order->status === 'pending')
                    <div class="mt-8 text-right">
                        <form action="{{ route('shop.orders.cancel', $order) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition duration-200 ease-in-out" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                Hủy đơn hàng
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
