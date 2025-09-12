<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('shop.orders.show', $orderItem->order_id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại chi tiết đơn hàng
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa đánh giá</h1>
            </div>

            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 h-20 w-20">
                        @if($orderItem->product)
                            <img class="h-20 w-20 rounded-md object-cover" src="{{ $orderItem->product->image_url }}" alt="{{ $orderItem->product_name }}">
                        @else
                            <div class="h-20 w-20 rounded-md bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $orderItem->product_name }}</h2>
                        <p class="text-sm text-gray-600">Đơn hàng #{{ $orderItem->order->order_number }}</p>
                    </div>
                </div>

                <form action="{{ route('shop.reviews.update', $orderItem->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Đánh giá</label>
                        <div class="rating-container">
                            <div class="flex items-center space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden" {{ (old('rating', $orderItem->review->rating) == $i) ? 'checked' : '' }} required>
                                        <span class="star-icon text-3xl {{ (old('rating', $orderItem->review->rating) >= $i) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Nội dung đánh giá</label>
                        <textarea name="comment" id="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ old('comment', $orderItem->review->comment) }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('shop.orders.show', $orderItem->order_id) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-2 hover:bg-gray-300 transition duration-200">
                            Hủy
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Cập nhật đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-icon');
            const inputs = document.querySelectorAll('input[name="rating"]');
            
            // Hiển thị sao ban đầu dựa trên giá trị đã chọn
            function updateStarsDisplay() {
                const selectedValue = document.querySelector('input[name="rating"]:checked').value;
                stars.forEach((star, i) => {
                    if (i < selectedValue) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
            
            // Cài đặt sự kiện cho các ngôi sao
            stars.forEach((star, index) => {
                // Khi di chuột qua sao
                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
                
                // Khi click vào sao
                star.addEventListener('click', () => {
                    inputs[index].checked = true;
                    updateStarsDisplay();
                });
            });
            
            // Xử lý khi di chuột ra khỏi container
            document.querySelector('.rating-container').addEventListener('mouseleave', updateStarsDisplay);
        });
    </script>
</x-layouts.app>
