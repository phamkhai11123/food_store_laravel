@props(['product', 'class' => ''])

<div {{ $attributes->merge(['class' => "card group {$class}"]) }} data-aos="fade-up">
    <div class="relative overflow-hidden">
        <!-- Hình ảnh sản phẩm -->
        <a href="{{ route('products.show', $product->id) }}">
            <img
                src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                alt="{{ $product->name }}"
                class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
            >
        </a>

        <!-- Badge danh mục -->
        <div class="absolute top-2 left-2">
            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                {{ $product->category->name }}
            </span>
        </div>

        <!-- Nút thêm vào giỏ hàng -->
        @auth
            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>
            </div>
        @endauth
    </div>

    <div class="p-4">
        <!-- Tên sản phẩm -->
        <a href="{{ route('products.show', $product->id) }}" class="block text-lg font-semibold text-gray-800 hover:text-blue-600 truncate">
            {{ $product->name }}
        </a>

        <!-- Giá sản phẩm -->
        <div class="mt-2 text-xl font-bold text-blue-600">
            {{ number_format($product->price, 0, ',', '.') }} đ
        </div>

        <!-- Đánh giá sản phẩm -->
        <div class="mt-2 flex items-center">
            <div class="flex text-yellow-400">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $product->averageRating)
                        <i class="fas fa-star"></i>
                    @elseif($i - 0.5 <= $product->averageRating)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
            <span class="ml-1 text-sm font-medium text-gray-700">{{ number_format($product->averageRating, 1) }}</span>
            <span class="mx-1 text-sm text-gray-500">({{ $product->reviewCount }})</span>
        </div>
    </div>
</div>
