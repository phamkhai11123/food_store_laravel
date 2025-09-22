<x-layouts.app title="Danh sách sản phẩm">
    <div class="container mx-auto px-4 py-8">
        <img src="{{ asset('images/banner.png') }}" alt="Banner" class="w-full object-cover mb-8 rounded-lg shadow-md" style="height: 450px; object-fit: cover;">
        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Sidebar -->
            <div class="md:w-1/4 mb-6 md:mb-0">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Danh mục</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('products.index') }}" class="block py-2 px-3 rounded-md {{ !request()->has('category') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Tất cả sản phẩm
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="block py-2 px-3 rounded-md {{ request('category') == $category->id ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                        

                    </ul>

                    <h2 class="text-xl font-bold mt-8 mb-4">Sắp xếp</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="block py-2 px-3 rounded-md {{ request('sort', 'newest') == 'newest' ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Mới nhất
                            </a>
                        </li>
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="block py-2 px-3 rounded-md {{ request('sort') == 'price_asc' ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Giá: Thấp đến cao
                            </a>
                        </li>   
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="block py-2 px-3 rounded-md {{ request('sort') == 'price_desc' ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Giá: Cao đến thấp
                            </a>
                        </li>
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" class="block py-2 px-3 rounded-md {{ request('sort') == 'name_asc' ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Tên: A-Z
                            </a>
                        </li>
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" class="block py-2 px-3 rounded-md {{ request('sort') == 'name_desc' ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                Tên: Z-A
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', ['sale' => 1]) }}"
                            class="block py-2 px-3 rounded-md {{ request()->has('sale') && request('sale') == 1 && !request()->has('category') && !request()->has('sort') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                                🔥 Món đang sale
                            </a>
                        </li>   
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:w-3/4">
                <!-- Search Results -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold mb-2">
                        @if(request()->has('search'))
                            Kết quả tìm kiếm: "{{ request('search') }}"
                        @elseif(request()->has('category'))
                            {{ $categories->find(request('category'))->name }}
                        @else
                            Tất cả sản phẩm
                        @endif
                    </h1>
                    <p class="text-gray-600">Hiển thị {{ $products->count() }} trong tổng số {{ $products->total() }} sản phẩm</p>
                </div>

                @if($products->isEmpty())
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <i class="fas fa-search text-5xl text-gray-400 mb-4"></i>
                        <h2 class="text-2xl font-bold mb-2">Không tìm thấy sản phẩm</h2>
                        <p class="text-gray-600 mb-6">Không tìm thấy sản phẩm nào phù hợp với tìm kiếm của bạn.</p>
                        <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-ui.product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
