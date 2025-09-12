<x-layouts.admin title="Quản lý nguyên liệu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">📦 Quản lý nguyên liệu</h1>

        <div class="mb-4">
            <a href="{{ route('admin.ingredients.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                ➕ Thêm nguyên liệu
            </a>
        </div>

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
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Đơn vị</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Giá vốn</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tồn kho</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Trạng thái</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ngày tạo</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ingredients as $ingredient)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->sku }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->base_unit }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ number_format($ingredient->suggested_unit_cost, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <span class="px-2 py-1 rounded text-xs {{ $ingredient->track_stock ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $ingredient->track_stock}}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <span class="px-2 py-1 rounded text-xs {{ $ingredient->is_active ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $ingredient->is_active ? 'Hoạt động' : 'Ngừng' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $ingredient->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 space-x-2">
                                {{-- <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="text-indigo-600 hover:underline text-sm">Sửa</a> --}}
                                {{-- <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?')"> --}}
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