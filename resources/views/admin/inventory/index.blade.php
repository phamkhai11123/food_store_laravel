<x-layouts.admin title="Quản lý kho">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-yellow-600 text-2xl">📜</span>
    <span>Lịch sử giao dịch kho</span>
    </h2>
        <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow flex flex-row flex-wrap items-end gap-4 overflow-x-auto">
    <div class="flex flex-col min-w-[150px]">
        <label for="name" class="text-sm text-gray-600 mb-1">Tên nguyên liệu</label>
        <input type="text" name="name" id="name" value="{{ request('name') }}"
               class="border rounded px-3 py-2 w-full" placeholder="Ví dụ: Đường">
    </div>

    <div class="flex flex-col min-w-[130px]">
        <label for="from" class="text-sm text-gray-600 mb-1">Từ ngày</label>
        <input type="date" name="from" id="from" value="{{ request('from') }}"
               class="border rounded px-3 py-2 w-full">
    </div>

    <div class="flex flex-col min-w-[130px]">
        <label for="to" class="text-sm text-gray-600 mb-1">Đến ngày</label>
        <input type="date" name="to" id="to" value="{{ request('to') }}"
               class="border rounded px-3 py-2 w-full">
    </div>

    <div class="flex flex-col min-w-[140px]">
        <label for="type" class="text-sm text-gray-600 mb-1">Loại giao dịch</label>
        <select name="type" id="type" class="border rounded px-3 py-2 w-full">
            <option value="">Tất cả</option>
            <option value="import" {{ request('type') == 'import' ? 'selected' : '' }}>Nhập</option>
            <option value="export" {{ request('type') == 'export' ? 'selected' : '' }}>Xuất</option>
            <option value="loss" {{ request('type') == 'loss' ? 'selected' : '' }}>Hao hụt</option>
        </select>
    </div>

    <div class="flex flex-col min-w-[100px]">
        <label for="unit" class="text-sm text-gray-600 mb-1">Đơn vị</label>
        <select name="unit" id="unit" class="border rounded px-3 py-2 w-full">
            <option value="">Tất cả</option>
            <option value="ml" {{ request('unit') == 'ml' ? 'selected' : '' }}>ml</option>
            <option value="g" {{ request('unit') == 'g' ? 'selected' : '' }}>g</option>
            <option value="pc" {{ request('unit') == 'pc' ? 'selected' : '' }}>pc</option>
        </select>
    </div>

    <div class="flex flex-col min-w-[130px]">
        <label for="sort" class="text-sm text-gray-600 mb-1">Sắp xếp số lượng</label>
        <select name="sort" id="sort" class="border rounded px-3 py-2 w-full">
            <option value="">Mặc định</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
        </select>
    </div>

    <div class="flex gap-2 mt-6">
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
            🔍 <span>Lọc</span>
        </button>

        <a href="{{ route('admin.inventory.index') }}"
           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-2">
            🧹 <span>Xóa</span>
        </a>
    </div>
</form>

<table class="min-w-full table-auto border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
        <tr>
            <th class="px-4 py-2 border">STT</th>
            <th class="px-4 py-2 border">Thời điểm</th>
            <th class="px-4 py-2 border">Nguyên liệu</th>
            <th class="px-4 py-2 border">Loại</th>
            <th class="px-4 py-2 border">Số lượng</th>
            <th class="px-4 py-2 border">Còn lại</th>
            <th class="px-4 py-2 border">Đơn vị</th>
            <th class="px-4 py-2 border">Ghi chú</th>
        </tr>
    </thead>
    <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
        @foreach ($transactions as $index => $tx)
            @php
                Log::info("Giao dịch #{$tx->id} - Nguyên liệu: " . ($tx->ingredients->name ?? 'null'));
            @endphp
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tx->performed_at)->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-2">{{ $tx->ingredients->name ?? '—' }}</td>
                <td class="px-4 py-2">
                    @switch($tx->type)
                        @case('import') <span class="text-green-600">📥 Nhập kho</span> @break
                        @case('export') <span class="text-blue-600">📤 Xuất kho</span> @break
                        @case('loss') <span class="text-red-600">⚠️ Hao hụt</span> @break
                        @default <span class="text-gray-500">—</span>
                    @endswitch
                </td>
                <td class="px-4 py-2 text-right">{{ number_format($tx->quantity_base, 0) }}</td>
                {{-- <td class="px-4 py-2 text-right">{{ number_format($tx->ingredients->track_stock ?? 0) }}</td>
                 --}}
                 @php
                    $unit = $tx->ingredients->base_unit ?? '';
                    $stock = $tx->ingredients->track_stock ?? 0;

                    $isLow = false;
                    if (in_array($unit, ['ml', 'g']) && $stock < 2000) {
                        $isLow = true;
                    } elseif ($unit === 'pc' && $stock < 10) {
                        $isLow = true;
                    }
                @endphp

                <td class="px-4 py-2 text-right {{ $isLow ? 'text-red-600 font-semibold' : '' }}">
                    {{ number_format($stock, 0) }}
                </td>
                <td class="px-4 py-2 text-center">{{ $tx->ingredients->base_unit }}</td>
                <td class="px-4 py-2 text-gray-600 italic">{{ $tx->note ?? '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $transactions->links() }}

</x-layouts.admin>