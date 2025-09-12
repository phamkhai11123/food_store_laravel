<x-layouts.app title="Giỏ hàng">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Giỏ hàng của bạn</h1>

        @if($cartItems->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <i class="fas fa-shopping-cart text-5xl text-gray-400 mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Giỏ hàng trống</h2>
                <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row lg:space-x-6">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Sản phẩm</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-4 flex flex-col sm:flex-row items-center">
                                    <div class="sm:w-20 mb-4 sm:mb-0">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-auto rounded">
                                    </div>

                                    <div class="sm:ml-6 sm:flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between mb-4">
                                            <div>
                                                <h3 class="text-lg font-medium">
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-blue-600 hover:text-blue-800">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-gray-500">{{ $item->product->category->name }}</p>
                                            </div>
                                            <div class="mt-2 sm:mt-0 text-red-600 font-bold">
                                                {{ number_format($item->product->price) }}đ
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                            <div class="flex items-center mb-4 sm:mb-0">
                                                <!-- Form giảm số lượng -->
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </form>

                                                <!-- Hiển thị số lượng -->
                                                <span class="w-10 text-center">{{ $item->quantity }}</span>

                                                <!-- Form tăng số lượng -->
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="text-gray-700">
                                                Tổng: <span class="font-bold">{{ number_format($item->product->price * $item->quantity) }}đ</span>
                                            </div>

                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 bg-gray-50 flex justify-between">
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Tiếp tục mua sắm
                            </a>

                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 inline-flex items-center">
                                    <i class="fas fa-trash-alt mr-2"></i> Xóa tất cả
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-4">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Tóm tắt đơn hàng</h2>
                        </div>

                        <div class="p-4 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)</span>
                                <span class="font-medium">{{ number_format($subtotal) }}đ</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển</span>
                                <span class="font-medium">{{ number_format($shippingFee) }}đ</span>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Tổng cộng</span>
                                    <span class="text-red-600">{{ number_format($total) }}đ</span>
                                </div>
                            </div>

                            <a href="{{ route('shop.orders.checkout') }}" class="block bg-red-600 hover:bg-red-700 text-white text-center px-4 py-3 rounded-md font-bold">
                                Tiến hành thanh toán
                            </a>

                            <div class="text-center text-sm text-gray-500 mt-4">
                                <p>Chúng tôi cam kết bảo mật thông tin thanh toán</p>
                                <div class="flex justify-center mt-2">
                                    <i class="fab fa-cc-visa text-2xl mx-1"></i>
                                    <i class="fab fa-cc-mastercard text-2xl mx-1"></i>
                                    <i class="fab fa-cc-paypal text-2xl mx-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>

@push('scripts')
@endpush
