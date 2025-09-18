<x-layouts.admin title="Quản lý nguyên liệu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">📦 Quản lý nguyên liệu</h1>

        <div class="mb-4">
            <a href="{{ route('admin.ingredients.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                ➕ Thêm nguyên liệu
            </a>
        </div>
            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow flex flex-wrap items-end gap-4">
    <div class="flex flex-col min-w-[180px]">
        <label for="name" class="text-sm text-gray-600 mb-1">Tên nguyên liệu</label>
        <input type="text" name="name" id="name" value="{{ request('name') }}"
               class="border rounded px-3 py-2 w-full" placeholder="Ví dụ: Muối">
    </div>

    <div class="flex flex-col min-w-[150px]">
        <label for="is_active" class="text-sm text-gray-600 mb-1">Trạng thái</label>
        <select name="is_active" id="is_active" class="border rounded px-3 py-2 w-full">
            <option value="">Tất cả</option>
            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Ngừng dùng</option>
        </select>
    </div>

    <div class="flex flex-col min-w-[130px]">
        <label for="base_unit" class="text-sm text-gray-600 mb-1">Đơn vị</label>
        <select name="base_unit" id="base_unit" class="border rounded px-3 py-2 w-full">
            <option value="">Tất cả</option>
            <option value="pc" {{ request('base_unit') == 'pc' ? 'selected' : '' }}>pc</option>
            <option value="ml" {{ request('base_unit') == 'ml' ? 'selected' : '' }}>ml</option>
            <option value="g" {{ request('base_unit') == 'g' ? 'selected' : '' }}>g</option>
        </select>
    </div>
        <div class="flex flex-col min-w-[150px]">
        <label for="sort_stock" class="text-sm text-gray-600 mb-1">Sắp xếp tồn kho</label>
        <select name="sort_stock" id="sort_stock" class="border rounded px-3 py-2 w-full">
            <option value="">Mặc định</option>
            <option value="asc" {{ request('sort_stock') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
            <option value="desc" {{ request('sort_stock') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
        </select>
    </div>
            <div class="flex flex-col min-w-[150px]">
                <label for="stock_status" class="text-sm text-gray-600 mb-1">Tình trạng kho</label>
                <select name="stock_status" id="stock_status" class="border rounded px-3 py-2 w-full">
                    <option value="">Tất cả</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Sắp hết</option>
                    <option value="enough" {{ request('stock_status') == 'enough' ? 'selected' : '' }}>Còn đủ</option>
                </select>
            </div>
    <div class="flex gap-2 mt-6">
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
            🔍 <span>Lọc</span>
        </button>

        <a href="{{ route('admin.ingredients.index') }}"
           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-2">
            🧹 <span>Xóa</span>
        </a>
    </div>
</form>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">SKU</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tên</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Giá tiền</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tồn kho</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Đơn vị</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Trạng thái</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ngày nhập</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ingredients as $ingredient)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->sku }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->name }}</td>
                            {{-- <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->base_unit }}</td>
                             --}}
                             
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ number_format($ingredient->suggested_unit_cost, 0) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @php
                                    $unit = $ingredient->base_unit ?? '';
                                    $stock = $ingredient->track_stock ?? 0;
                                    $low = (in_array($unit, ['ml', 'g']) && $stock < 2000) || ($unit === 'pc' && $stock < 10);
                                @endphp

                                <span class="px-2 py-1 rounded text-xs {{ $low ? 'text-red-600 font-semibold' : 'text-gray-800' }}">
                                    {{ number_format($stock, 0) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @switch($ingredient->base_unit)
                                    @case('g')
                                        Gram
                                        @break
                                    @case('ml')
                                        Mililit
                                        @break
                                    @case('pc')
                                        Cái
                                        @break
                                    @default
                                        Không xác định
                                @endswitch
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <span class="px-2 py-1 rounded text-xs {{ $ingredient->is_active ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $ingredient->is_active ? 'Hoạt động' : 'Ngừng' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 space-x-2">
                                <a href="{{ route('admin.ingredients.edit', $ingredient->id) }}" class="text-indigo-600 hover:underline text-sm">Sửa</a>
                                <form action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-gray-500">Không có nguyên liệu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>