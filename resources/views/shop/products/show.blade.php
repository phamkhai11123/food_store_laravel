<x-layouts.app title="{{ $product->name }}">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-1/2">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>

                <!-- Product Details -->
                <div class="p-6 md:w-1/2">
                    <div class="mb-8">
                        <div class="flex items-center mb-2">
                            <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                {{ $product->category->name }}
                            </a>
                        </div>

                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>

                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400 mr-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $product->average_rating ? '' : '-o text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="text-gray-600 font-medium ml-1">{{ number_format($product->average_rating, 1) }}</span>
                            <span class="text-gray-600 mx-1">•</span>
                            <span class="text-gray-600">{{ $product->review_count }} đánh giá</span>
                        </div>

                         @php
                            $discounted = $product->getDiscountedPrice();
                        @endphp

                        @if($discounted < $product->price)
                            <div class="text-sm text-gray-500 line-through">
                                {{ number_format($product->price) }}₫
                            </div>
                            <div class="text-lg text-red-600 font-bold">
                                {{ number_format($discounted) }}₫
                            </div>
                        @else
                            <div class="mt-2 text-xl font-bold text-blue-600">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </div>
                        @endif

                        <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                        <form action="{{ route('cart.store') }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="flex items-center border border-gray-300 rounded-md mr-4">
                                <button type="button" class="decrement-btn px-3 py-2 text-gray-600 hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" value="1" min="1" max="100" class="w-12 text-center border-0 focus:outline-none">
                                <button type="button" class="increment-btn px-3 py-2 text-gray-600 hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <x-ui.button type="submit" color="primary">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ hàng
                            </x-ui.button>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-wrap">
                            <div class="w-1/2 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Freeship cho đơn từ 200K</span>
                                </div>
                            </div>
                            <div class="w-1/2 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-undo-alt text-blue-500 mr-2"></i>
                                    <span>Đổi trả trong 24h</span>
                                </div>
                            </div>
                            <div class="w-1/2 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-truck text-purple-500 mr-2"></i>
                                    <span>Giao hàng nhanh</span>
                                </div>
                            </div>
                            <div class="w-1/2 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt text-red-500 mr-2"></i>
                                    <span>Đảm bảo chất lượng</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-t border-gray-200">
                <div class="bg-gray-50 px-6">
                    <div class="flex overflow-x-auto">
                        <button class="tab-btn py-4 px-6 border-b-2 border-blue-600 text-blue-600 font-medium" data-tab="details">
                            Chi tiết sản phẩm
                        </button>
                        <button class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-gray-500" data-tab="reviews">
                            Đánh giá ({{ $product->review_count }})
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div id="tab-details" class="tab-content">
                        <div class="prose max-w-none">
                            {!! $product->content !!}
                        </div>
                    </div>

                    <div id="tab-reviews" class="tab-content hidden">
                        <!-- Review Form -->
                        @auth
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-bold mb-4">Đánh giá sản phẩm</h3>
                                <p class="text-gray-700 mb-4">
                                    Sau khi đơn hàng hoàn thành, bạn có thể đánh giá sản phẩm trong đơn hàng từ trang "Chi tiết đơn hàng".
                                </p>
                                <a href="{{ route('shop.orders.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Xem đơn hàng của tôi
                                </a>
                            </div>
                        @else
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
                                <p class="mb-4">Bạn cần đăng nhập để đánh giá sản phẩm</p>
                                <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Đăng nhập ngay
                                </a>
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        <div class="space-y-6">
                            @forelse($product->reviews as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="font-medium">{{ $review->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-gray-700 font-medium">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500">Chưa có đánh giá nào cho sản phẩm này</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-ui.product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Quantity buttons
        document.addEventListener('DOMContentLoaded', function() {
            const decrementBtn = document.querySelector('.decrement-btn');
            const incrementBtn = document.querySelector('.increment-btn');
            const quantityInput = document.querySelector('input[name="quantity"]');

            decrementBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            incrementBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < 10) {
                    quantityInput.value = currentValue + 1;
                }
            });

            // Tabs
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tab = this.dataset.tab;

                    // Update active tab button
                    tabBtns.forEach(btn => {
                        btn.classList.remove('border-blue-600', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-blue-600', 'text-blue-600');

                    // Show active tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(`tab-${tab}`).classList.remove('hidden');
                });
            });

            // Rating stars
            const stars = document.querySelectorAll('.rating-stars i');
            const ratingInput = document.querySelector('input[name="rating"]');

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.dataset.rating);
                    highlightStars(rating);
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(ratingInput.value);
                    highlightStars(currentRating);
                });

                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingInput.value = rating;
                    highlightStars(rating);
                });
            });

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('far');
                        star.classList.add('fas');
                    } else {
                        star.classList.remove('fas');
                        star.classList.add('far');
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
