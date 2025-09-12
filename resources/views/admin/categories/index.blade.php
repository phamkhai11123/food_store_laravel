<x-layouts.admin title="Quản lý danh mục">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Quản lý danh mục</h1>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center" data-modal-target="categoryModal" data-modal-action="create">
                <i class="fas fa-plus mr-2"></i> Thêm danh mục mới
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <!-- Search -->
                <form action="{{ route('admin.categories.index') }}" method="GET" class="flex space-x-2">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm danh mục..."
                            class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" id="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hiển thị</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đang ẩn</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    
                    @if(request('search') || request('status') || request('sort'))
                        <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center">
                            <i class="fas fa-times mr-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                    
                    <!-- Hidden input to preserve sort parameter -->
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </form>

                <!-- Sort -->
                <div class="relative">
                    <select name="sort" id="sortOrder" class="appearance-none bg-gray-100 border border-gray-300 rounded-md pl-3 pr-10 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                        <option value="products_count" {{ request('sort') == 'products_count' ? 'selected' : '' }}>Số lượng sản phẩm</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-chevron-down text-xs"></i>
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
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hình ảnh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên danh mục
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số sản phẩm
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
                        @foreach($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="category-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $category->id }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ $category->image_url }}" alt="{{ $category->name }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $category->products_count }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Hiển thị
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Ẩn
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" class="text-blue-600 hover:text-blue-900 mr-3"
                                        data-modal-target="categoryModal"
                                        data-modal-action="edit"
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}"
                                        data-category-description="{{ $category->description }}"
                                        data-category-slug="{{ $category->slug }}"
                                        data-category-is-active="{{ $category->is_active }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($categories->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Không tìm thấy danh mục nào.
                </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="mb-4 sm:mb-0">
                        <div class="flex items-center">
                            <span class="mr-2">Với danh mục đã chọn:</span>
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
                        {{ $categories->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold modal-title">Thêm danh mục mới</h2>
                <button type="button" class="text-gray-500 hover:text-gray-700" data-modal-close>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div id="method-field"></div>
                <input type="hidden" id="category_id" name="id">

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block mb-2 font-medium">Tên danh mục <span class="text-red-600">*</span></label>
                        <input type="text" id="category_name" name="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div>
                        <label for="slug" class="block mb-2 font-medium">Slug</label>
                        <div class="flex">
                            <input type="text" id="category_slug" name="slug"
                                class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="generateCategorySlug" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-r-md text-gray-700">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Để trống để tự động tạo từ tên danh mục.</p>
                    </div>

                    <div>
                        <label for="description" class="block mb-2 font-medium">Mô tả</label>
                        <textarea id="category_description" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="image" class="block mb-2 font-medium">Hình ảnh</label>
                        <input type="file" id="category_image" name="image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1" id="image-note">Chọn hình ảnh cho danh mục.</p>
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" id="category_is_active" name="is_active" value="1" checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2">Hiển thị danh mục</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100" data-modal-close>
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <span class="modal-action-text">Thêm danh mục</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status filter
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    // Get the form element
                    const form = statusFilter.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            }

            // Sort filter
            const sortOrder = document.getElementById('sortOrder');

            sortOrder.addEventListener('change', function() {
                const searchParams = new URLSearchParams(window.location.search);

                if (sortOrder.value) {
                    searchParams.set('sort', sortOrder.value);
                } else {
                    searchParams.delete('sort');
                }

                window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
            });

            // Select all checkboxes
            const selectAll = document.getElementById('selectAll');
            const categoryCheckboxes = document.querySelectorAll('.category-checkbox');

            selectAll.addEventListener('change', function() {
                categoryCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Bulk actions
            const bulkAction = document.getElementById('bulkAction');
            const applyBulkAction = document.getElementById('applyBulkAction');

            applyBulkAction.addEventListener('click', function() {
                const selectedCategoryIds = Array.from(categoryCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedCategoryIds.length === 0) {
                    alert('Vui lòng chọn ít nhất một danh mục!');
                    return;
                }

                if (!bulkAction.value) {
                    alert('Vui lòng chọn một hành động!');
                    return;
                }

                if (bulkAction.value === 'delete') {
                    if (!confirm('Bạn có chắc chắn muốn xóa các danh mục đã chọn?')) {
                        return;
                    }
                }

                // Submit the form with selected categories and action
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.categories.bulk-action") }}';

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

                selectedCategoryIds.forEach(id => {
                    const categoryInput = document.createElement('input');
                    categoryInput.type = 'hidden';
                    categoryInput.name = 'category_ids[]';
                    categoryInput.value = id;
                    form.appendChild(categoryInput);
                });

                document.body.appendChild(form);
                form.submit();
            });

            // Category Modal
            const modal = document.getElementById('categoryModal');
            const modalTitle = modal.querySelector('.modal-title');
            const modalActionText = modal.querySelector('.modal-action-text');
            const categoryForm = document.getElementById('categoryForm');
            const methodField = document.getElementById('method-field');
            const categoryId = document.getElementById('category_id');
            const categoryName = document.getElementById('category_name');
            const categorySlug = document.getElementById('category_slug');
            const categoryDescription = document.getElementById('category_description');
            const categoryIsActive = document.getElementById('category_is_active');
            const imageNote = document.getElementById('image-note');

            // Open modal
            document.querySelectorAll('[data-modal-target="categoryModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('data-modal-action');

                    if (action === 'create') {
                        modalTitle.textContent = 'Thêm danh mục mới';
                        modalActionText.textContent = 'Thêm danh mục';
                        categoryForm.action = '{{ route("admin.categories.store") }}';
                        methodField.innerHTML = '';
                        categoryForm.reset();
                        categoryId.value = '';
                        imageNote.textContent = 'Chọn hình ảnh cho danh mục.';
                    } else if (action === 'edit') {
                        modalTitle.textContent = 'Chỉnh sửa danh mục';
                        modalActionText.textContent = 'Cập nhật danh mục';

                        const id = this.getAttribute('data-category-id');
                        categoryForm.action = `{{ route("admin.categories.index") }}/${id}`;
                        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                        categoryId.value = id;
                        categoryName.value = this.getAttribute('data-category-name');
                        categorySlug.value = this.getAttribute('data-category-slug');
                        categoryDescription.value = this.getAttribute('data-category-description');
                        categoryIsActive.checked = this.getAttribute('data-category-is-active') === '1';

                        imageNote.textContent = 'Để trống nếu không muốn thay đổi hình ảnh.';
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Close modal
            document.querySelectorAll('[data-modal-close]').forEach(button => {
                button.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });

            // Generate slug from name
            const generateCategorySlugButton = document.getElementById('generateCategorySlug');

            function createSlug(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text
            }

            categoryName.addEventListener('blur', function() {
                if (categorySlug.value === '') {
                    categorySlug.value = createSlug(categoryName.value);
                }
            });

            generateCategorySlugButton.addEventListener('click', function() {
                categorySlug.value = createSlug(categoryName.value);
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>
