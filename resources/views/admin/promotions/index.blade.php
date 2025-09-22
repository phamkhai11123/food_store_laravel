<x-layouts.admin title="Danh sách khuyến mãi">
    <div class="max-w-6xl mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6">📋 Danh sách khuyến mãi</h2>
        <a href="{{ route('admin.promotions.create') }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
            ➕ Tạo khuyến mãi mới
        </a>

        <table class="w-full table-auto border border-gray-300 rounded-md overflow-hidden">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Tên</th>
                    <th class="px-4 py-2">Mã</th>
                    <th class="px-4 py-2">Loại</th>
                    <th class="px-4 py-2">Giá trị</th>
                    <th class="px-4 py-2">Thời gian</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promotion)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $promotion->name }}</td>
                        <td class="px-4 py-2">{{ $promotion->code ?? '—' }}</td>
                        <td class="px-4 py-2">
                            {{ $promotion->type === 'percentage' ? 'Giảm %' : 'Giảm tiền' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $promotion->type === 'percentage' ? $promotion->value . '%' : number_format($promotion->value) . 'đ' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ $promotion->start_date ? $promotion->start_date->format('d/m/Y H:i') : '—' }}
                            —
                            {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td class="px-4 py-2">
                            @if($promotion->is_active)
                                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Đang hoạt động</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Tạm ngưng</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.promotions.edit', $promotion) }}"
                               class="text-blue-600 hover:underline text-sm">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">Chưa có khuyến mãi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $promotions->links() }}
        </div>
    </div>
</x-layouts.admin>