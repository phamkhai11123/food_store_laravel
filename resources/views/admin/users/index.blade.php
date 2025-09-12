<x-layouts.admin title="Quản lý người dùng">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Quản lý người dùng</h1>

            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm người dùng mới
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold">Bộ lọc</h2>
            </div>

            <div class="p-6">
                <form id="filterForm" action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Tên, email, số điện thoại..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @if(request('search'))
                                <button type="button" class="clear-search absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                        <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp</label>
                        <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at-desc" {{ request('sort') == 'created_at-desc' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="created_at-asc" {{ request('sort') == 'created_at-asc' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                            <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                        </select>
                    </div>

                    @if(request('search') || request('role') || request('sort'))
                    <div class="md:col-span-3 flex space-x-2">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-times mr-2"></i> Xóa bộ lọc
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request('search') || request('role') || (request('sort') && request('sort') != 'created_at-desc'))
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-3 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Bộ lọc đang áp dụng:</span>

                    @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <span>Tìm kiếm: {{ request('search') }}</span>
                        <a href="{{ route('admin.users.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('role'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span>
                            Vai trò:
                            @if(request('role') == 'admin')
                                Admin
                            @elseif(request('role') == 'user')
                                Người dùng
                            @else
                                {{ request('role') }}
                            @endif
                        </span>
                        <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['page' => 1])) }}" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('sort') && request('sort') != 'created_at-desc')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <span>
                            Sắp xếp:
                            @if(request('sort') == 'created_at-desc')
                                Mới nhất
                            @elseif(request('sort') == 'created_at-asc')
                                Cũ nhất
                            @elseif(request('sort') == 'name-asc')
                                Tên (A-Z)
                            @elseif(request('sort') == 'name-desc')
                                Tên (Z-A)
                            @else
                                {{ request('sort') }}
                            @endif
                        </span>
                        <a href="{{ route('admin.users.index', array_merge(request()->except('sort'), ['page' => 1])) }}" class="ml-1 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thông tin người dùng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vai trò
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đơn hàng
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            @if($user->phone)
                                                <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role == 'admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Người dùng
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $user->orders_count ?? 0 }} đơn hàng
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(\Illuminate\Support\Facades\Auth::id() != $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->isEmpty())
                <div class="px-6 py-4 text-center text-gray-500">
                    Không tìm thấy người dùng nào
                </div>
            @endif

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');

            // Auto-submit form when select fields change
            document.querySelectorAll('.filter-auto-submit').forEach(function(select) {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            // Clear search button
            const clearSearchBtn = document.querySelector('.clear-search');
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    document.getElementById('search').value = '';
                    filterForm.submit();
                });
            }

            // Search functionality with debounce
            const searchInput = document.getElementById('search');
            if (searchInput) {
                // Search on enter key press
                searchInput.addEventListener('keyup', function(event) {
                    if (event.key === 'Enter') {
                        filterForm.submit();
                    }
                });

                // Debounced search (submit after 500ms of inactivity)
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    if (this.value.trim().length > 2) { // Only search when at least 3 characters
                        searchTimeout = setTimeout(function() {
                            filterForm.submit();
                        }, 500);
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
