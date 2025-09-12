<x-layouts.admin title="Cập nhật đơn hàng #{{ $order->order_number }}">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.orders.show', $order) }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại chi tiết đơn hàng
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold">Cập nhật đơn hàng #{{ $order->order_number }}</h1>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Information -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng
                                        thái đơn hàng</label>
                                    <select id="status" name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang
                                            xử lý</option>
                                        <option value="processing"
                                            {{ $order->status == 'processing' ? 'selected' : '' }}>Đang giao hàng
                                        </option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Đã giao hàng</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Đã hủy</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="payment_status"
                                        class="block text-sm font-medium text-gray-700 mb-1">Trạng thái thanh
                                        toán</label>
                                    <select id="payment_status" name="payment_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>Chờ
                                            xác nhận</option>
                                        <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>Đã
                                            thanh toán</option>
                                    </select>
                                    @error('payment_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Thông tin giao hàng</h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ tên
                                        người nhận</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $order->name) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện
                                        thoại</label>
                                    <input type="text" id="phone" name="phone"
                                        value="{{ old('phone', $order->phone) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa
                                        chỉ</label>
                                    <input type="text" id="address" name="address"
                                        value="{{ old('address', $order->address) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
                                    <input type="text" id="city" name="city"
                                        value="{{ old('city', $order->city) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold mb-4">Sản phẩm trong đơn hàng</h2>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sản phẩm
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Giá
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Số lượng
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thành tiền
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->orderItems as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md object-cover"
                                                            src="{{ $item->product->image_url }}"
                                                            alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name }}
                                                            <input type="hidden" name="items[{{ $index }}][id]"
                                                                value="{{ $item->id }}">
                                                            <input type="hidden"
                                                                name="items[{{ $index }}][product_id]"
                                                                value="{{ $item->product_id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" name="items[{{ $index }}][price]"
                                                    value="{{ $item->price }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" name="items[{{ $index }}][quantity]"
                                                    value="{{ $item->quantity }}" min="1"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700">
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ number_format($item->price * $item->quantity) }}đ</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold mb-4">Thông tin thanh toán</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-1">Tạm
                                            tính</label>
                                        <input type="number" id="subtotal" name="subtotal"
                                            value="{{ old('subtotal', $order->subtotal) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('subtotal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="shipping_fee"
                                            class="block text-sm font-medium text-gray-700 mb-1">Phí vận chuyển</label>
                                        <input type="number" id="shipping_fee" name="shipping_fee"
                                            value="{{ old('shipping_fee', $order->shipping_fee) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('shipping_fee')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="total"
                                            class="block text-sm font-medium text-gray-700 mb-1">Tổng cộng</label>
                                        <input type="number" id="total" name="total"
                                            value="{{ old('total', $order->total) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-bold text-red-600">
                                        @error('total')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Hủy
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cập nhật đơn hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto calculate total
            const calculateTotal = function() {
                const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
                const shippingFee = parseFloat(document.getElementById('shipping_fee').value) || 0;

                const total = subtotal + shippingFee;
                document.getElementById('total').value = Math.max(0, total);
            };

            // Add event listeners to inputs that affect the total
            document.getElementById('subtotal').addEventListener('change', calculateTotal);
            document.getElementById('shipping_fee').addEventListener('change', calculateTotal);

            // Recalculate when item prices or quantities change
            const itemPrices = document.querySelectorAll('input[name^="items"][name$="[price]"]');
            const itemQuantities = document.querySelectorAll('input[name^="items"][name$="[quantity]"]');

            const recalculateSubtotal = function() {
                let subtotal = 0;

                for (let i = 0; i < itemPrices.length; i++) {
                    const price = parseFloat(itemPrices[i].value) || 0;
                    const quantity = parseFloat(itemQuantities[i].value) || 0;
                    subtotal += price * quantity;
                }

                document.getElementById('subtotal').value = subtotal;
                calculateTotal();
            };

            itemPrices.forEach(input => input.addEventListener('change', recalculateSubtotal));
            itemQuantities.forEach(input => input.addEventListener('change', recalculateSubtotal));
        });
    </script>
</x-layouts.admin>
