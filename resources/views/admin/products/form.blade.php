<x-layouts.admin title="{{ isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới' }}">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới' }}</h1>
        </div>

        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md overflow-hidden">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column: Main Info -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label for="name" class="block mb-2 font-medium">Tên sản phẩm <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block mb-2 font-medium">Slug</label>
                        <div class="flex">
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="generateSlug" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-r-md text-gray-700">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Để trống để tự động tạo từ tên sản phẩm.</p>
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2 font-medium">Mô tả</label>
                        <textarea id="description" name="description" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column: Meta Info -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-md p-4">
                        <h2 class="font-medium mb-4">Trạng thái & Danh mục</h2>

                        <div class="mb-4">
                            <label for="is_active" class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ (old('is_active', $product->is_active ?? true) ? 'checked' : '') }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2">Đang bán</span>
                            </label>
                        </div>

                        <div>
                            <label for="category_id" class="block mb-2 font-medium">Danh mục <span class="text-red-600">*</span></label>
                            <select id="category_id" name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-md p-4">
                        <h2 class="font-medium mb-4">Giá sản phẩm</h2>

                        <div>
                            <label for="price" class="block mb-2 font-medium">Giá bán <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? 0) }}" min="0" step="1000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">đ</span>
                                </div>
                            </div>
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-md p-4">
                        <h2 class="font-medium mb-4">Hình ảnh sản phẩm</h2>

                        <div>
                            <label for="image" class="block mb-2 font-medium">Ảnh đại diện</label>
                            <div class="flex">
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @if(isset($product) && $product->image_url)
                                <div class="mt-2">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded-md">
                                </div>
                            @endif
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md mr-2 hover:bg-gray-100" onclick="window.history.back()">
                    Hủy
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ isset($product) ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const generateSlugButton = document.getElementById('generateSlug');

        function createSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        nameInput.addEventListener('blur', function() {
            if (slugInput.value === '') {
                slugInput.value = createSlug(nameInput.value);
            }
        });

        generateSlugButton.addEventListener('click', function() {
            slugInput.value = createSlug(nameInput.value);
        });
    </script>
    @endpush
</x-layouts.admin>
