<x-layouts.app title="Thanh toán">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Thanh toán</h1>

        <form action="{{ route('shop.orders.store') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row lg:space-x-6">
                <!-- Customer Information -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Thông tin giao hàng</h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block mb-2 font-medium">Họ và tên</label>
                                    <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? old('name') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block mb-2 font-medium">Email</label>
                                    <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? old('email') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block mb-2 font-medium">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone_number" value="{{ auth()->user()->phone ?? old('phone_number') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('phone_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>                                <div>
                                    <label for="city" class="block mb-2 font-medium">Tỉnh/Thành phố</label>
                                    <select id="city" name="city"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        <option value="Hà Nội" {{ old('city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                        <option value="TP HCM" {{ old('city') == 'TP HCM' ? 'selected' : '' }}>TP HCM</option>
                                        <option value="Đà Nẵng" {{ old('city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                        <option value="Cần Thơ" {{ old('city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                        <option value="Hải Phòng" {{ old('city') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                    </select>
                                    @error('city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        <div>
                            <label for="address" class="block mb-2 font-medium">Địa chỉ giao hàng</label>
                            <input type="text" id="address" name="shipping_address" value="{{ old('shipping_address') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('shipping_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>                            <div>
                                <label for="note" class="block mb-2 font-medium">Ghi chú (tùy chọn)</label>
                                <textarea id="note" name="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Phương thức thanh toán</h2>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center p-4 border border-gray-200 rounded-md">
                                <input type="radio" id="payment_cod" name="payment_method" value="cod" class="h-4 w-4 text-blue-600" checked>
                                <label for="payment_cod" class="ml-3 flex items-center">
                                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                    <div>
                                        <p class="font-medium">Thanh toán khi nhận hàng (COD)</p>
                                        <p class="text-gray-500 text-sm">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-md">
                                <input type="radio" id="payment_bank" name="payment_method" value="bank" class="h-4 w-4 text-blue-600">
                                <label for="payment_bank" class="ml-3 flex items-center">
                                    <i class="fas fa-university text-blue-600 mr-2"></i>
                                    <div>
                                        <p class="font-medium">Chuyển khoản ngân hàng</p>
                                        <p class="text-gray-500 text-sm">Thông tin chuyển khoản sẽ được gửi qua email</p>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-md">
                                <input type="radio" id="payment_momo" name="payment_method" value="momo" class="h-4 w-4 text-blue-600">
                                <label for="payment_momo" class="ml-3 flex items-center">
                                    <i class="fas fa-wallet text-pink-600 mr-2"></i>
                                    <div>
                                        <p class="font-medium">Ví MoMo</p>
                                        <p class="text-gray-500 text-sm">Thanh toán qua ví điện tử MoMo</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-4">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Đơn hàng của bạn</h2>
                        </div>

                        <div class="p-4">
                            <div class="max-h-64 overflow-y-auto mb-4">
                                @foreach($cartItems as $item)
                                    <div class="flex py-4 border-b border-gray-200 last:border-b-0">
                                        <div class="w-16 h-16 flex-shrink-0">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h3 class="text-sm font-medium">{{ $item->product->name }}</h3>
                                            <p class="text-gray-500 text-sm">{{ $item->quantity }} x {{ number_format($item->product->getDiscountedPrice()) }}đ</p>
                                        </div>
                                        @php
                                            $discounted = $item->product->getDiscountedPrice();
                                        @endphp
                                        <div class="text-right">
                                            {{-- <p class="font-medium">{{ number_format($item->product->getDiscountedPrice() * $item->quantity) }}đ</p>
                                             --}}
                                              @if($discounted < $item->product->price)
                                        <div class="text-sm text-gray-400 line-through">
                                            {{ number_format($item->product->price) }}₫
                                        </div>
                                        <div class="text-red-600 font-semibold">
                                            {{ number_format($discounted) }}₫ 
                                        </div>
                                    @else
                                        <div class="text-gray-800 font-semibold">
                                            {{ number_format($item->product->price) }}₫ 
                                        </div>
                                    @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-3 py-4 border-t border-gray-200">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tạm tính</span>
                                    <span>{{ number_format($subtotal) }}đ</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phí vận chuyển</span>
                                    <span>{{ number_format($shippingFee+30000) }}đ</span>
                                </div>

                                <div class="flex justify-between font-bold text-lg pt-3 border-t border-gray-200">
                                    <span>Tổng cộng</span>
                                    <span class="text-red-600">{{ number_format($total+30000) }}đ</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-3 rounded-md font-bold">
                                    Đặt hàng
                                </button>

                                <p class="text-sm text-gray-500 mt-4 text-center">
                                    Bằng cách đặt hàng, bạn đồng ý với
                                    <a href="#" class="text-blue-600 hover:underline">điều khoản dịch vụ</a>
                                    và <a href="#" class="text-blue-600 hover:underline">chính sách bảo mật</a> của chúng tôi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
