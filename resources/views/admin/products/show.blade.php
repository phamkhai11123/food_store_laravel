<x-layouts.admin title="Chi tiết sản phẩm">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết sản phẩm</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md flex items-center">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Hình ảnh sản phẩm -->
                <div class="space-y-4">
                    <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-96 w-auto object-cover">
                        @else
                            <div class="text-gray-400 text-center">
                                <i class="fas fa-image text-6xl mb-2"></i>
                                <p>Không có hình ảnh</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-gray-500 mt-1">Mã sản phẩm: #{{ $product->id }}</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-700">Mô tả sản phẩm</h3>
                            <div class="mt-2 text-gray-600">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-medium text-gray-700">Danh mục</h3>
                                <p class="mt-1 text-gray-600">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700">Giá bán</h3>
                                <p class="mt-1 text-lg font-semibold text-blue-600">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700">Số lượng tồn kho</h3>
                                <p class="mt-1 text-gray-600">{{ $product->stock_quantity }} sản phẩm</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-700">Đã bán</h3>
                                <p class="mt-1 text-gray-600">{{ $product->sold_quantity ?? 0 }} sản phẩm</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Đang bán' : 'Ngừng bán' }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500">
                                    {{ $product->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bổ sung -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin bổ sung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700">Mô tả chi tiết</h4>
                        <div class="mt-2 text-gray-600 prose max-w-none">
                            {!! $product->content ?? '<p class="text-gray-400">Chưa có mô tả chi tiết</p>' !!}
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Thông tin khác</h4>
                        <div class="mt-2 space-y-2 text-gray-600">
                            <p><span class="font-medium">SKU:</span> {{ $product->sku ?? 'Chưa cập nhật' }}</p>
                            <p><span class="font-medium">Trọng lượng:</span> {{ $product->weight ? $product->weight . 'g' : 'Chưa cập nhật' }}</p>
                            <p><span class="font-medium">Kích thước:</span>
                                @if($product->length && $product->width && $product->height)
                                    {{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm
                                @else
                                    Chưa cập nhật
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
