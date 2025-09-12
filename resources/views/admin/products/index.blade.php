<x-layouts.admin title="Quản lý sản phẩm">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Quản lý sản phẩm</h1>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm sản phẩm mới
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                    <!-- Search -->
                    <form action="{{ route('admin.products.index') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm..."
                            class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Category Filter -->
                    <div class="relative">
                        <select name="category" id="categoryFilter" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm w-full">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    @if(request('search') || request('category') || request('sort'))
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center">
                            <i class="fas fa-times mr-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Sort -->
                    <div class="relative">
                        <select name="sort" id="sortOrder" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá (thấp-cao)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá (cao-thấp)</option>
                            <option value="sales_desc" {{ request('sort') == 'sales_desc' ? 'selected' : '' }}>Bán chạy nhất</option>
                            <option value="sales_asc" {{ request('sort') == 'sales_asc' ? 'selected' : '' }}>Bán ít nhất</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Danh mục
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giá
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đã bán
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $product->id }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-md object-cover"
                                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->category?->name ?? 'Không có danh mục' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($product->price) }}đ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->total_quantity > 10)
                                        <div class="text-sm font-medium text-green-600 flex items-center">
                                            <span class="mr-1">{{ number_format($product->total_quantity) }}</span>
                                            <span class="bg-green-100 text-green-800 text-xs px-1.5 py-0.5 rounded-full">Bán chạy</span>
                                        </div>
                                    @elseif($product->total_quantity > 0)
                                        <div class="text-sm font-medium text-green-600">
                                            {{ number_format($product->total_quantity) }} sản phẩm
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500">
                                            0 sản phẩm
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đang bán
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Ngừng bán
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product) }}" class="text-green-600 hover:text-green-900 mr-3" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($products->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Không tìm thấy sản phẩm nào.
                </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <span class="mr-2">Với sản phẩm đã chọn:</span>
                            <div class="relative">
                                <select id="bulkAction" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Chọn hành động</option>
                                    <option value="activate">Hiển thị</option>
                                    <option value="deactivate">Ẩn</option>
                                    <option value="delete">Xóa</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <button id="applyBulkAction" class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm">
                                Áp dụng
                            </button>
                        </div>
                    </div>

                    <div>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category and sort filters
            const categoryFilter = document.getElementById('categoryFilter');
            const sortOrder = document.getElementById('sortOrder');

            categoryFilter.addEventListener('change', function() {
                applyFilters();
            });

            sortOrder.addEventListener('change', function() {
                applyFilters();
            });

            function applyFilters() {
                const searchParams = new URLSearchParams(window.location.search);

                if (categoryFilter.value) {
                    searchParams.set('category', categoryFilter.value);
                } else {
                    searchParams.delete('category');
                }

                if (sortOrder.value) {
                    searchParams.set('sort', sortOrder.value);
                } else {
                    searchParams.delete('sort');
                }

                window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
            }

            // Select all checkboxes
            const selectAll = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');

            selectAll.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Bulk actions
            const bulkAction = document.getElementById('bulkAction');
            const applyBulkAction = document.getElementById('applyBulkAction');

            applyBulkAction.addEventListener('click', function() {
                const selectedProductIds = Array.from(productCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedProductIds.length === 0) {
                    alert('Vui lòng chọn ít nhất một sản phẩm!');
                    return;
                }

                if (!bulkAction.value) {
                    alert('Vui lòng chọn một hành động!');
                    return;
                }

                if (bulkAction.value === 'delete') {
                    if (!confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?')) {
                        return;
                    }
                }

                // Submit the form with selected products and action
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.products.bulk-action") }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = bulkAction.value;
                form.appendChild(actionInput);

                selectedProductIds.forEach(id => {
                    const productInput = document.createElement('input');
                    productInput.type = 'hidden';
                    productInput.name = 'product_ids[]';
                    productInput.value = id;
                    form.appendChild(productInput);
                });

                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
    @endpush
</x-layouts.admin>
