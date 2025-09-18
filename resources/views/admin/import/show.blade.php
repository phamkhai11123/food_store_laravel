<x-layouts.admin title="Chi tiết đơn nhập">
    <div class="max-w-7xl mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">Chi tiết đơn nhập: {{ $import->code }}</h1>

        <div class="mb-6 space-y-2">
            <p><strong>Ngày nhập:</strong> {{ \Carbon\Carbon::parse($import->import_date)->format('d/m/Y') }}</p>
            <p><strong>Nhà cung cấp:</strong> {{ $import->supplier }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($import->total_cost, 0, ',', '.') }} đ</p>
            
        </div>

        <h2 class="text-xl font-semibold mb-3">Danh sách nguyên liệu nhập</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr class="text-left text-sm font-semibold text-gray-700">
                        <th class="px-4 py-3 border">Tên nguyên liệu</th>
                        <th class="px-4 py-3 border">Số lượng nhập</th>
                        <th class="px-4 py-3 border">Giá nhập</th>
                        <th class="px-4 py-3 border">Thành tiền</th>
                        <th class="px-4 py-3 border">Tồn kho hiện tại</th>
                        <th class="px-4 py-3 border">Đơn vị</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($import->details as $detail)
                    @php
                        $unit = $detail->ingredient->base_unit;
                        $rawQty = $detail->quantity;
                        $convertedQty = in_array($unit, ['ml', 'g']) ? $rawQty / 1000 : $rawQty;
                        $lineTotal = $convertedQty * $detail->unit_price;
                    @endphp

                    <tr class="text-sm text-gray-800">
                        <td class="px-4 py-2 border">{{ $detail->ingredient->name }}</td>
                        <td class="px-4 py-2 border">{{ $convertedQty }}</td>
                        <td class="px-4 py-2 border">{{ number_format($detail->unit_price, 0, ',', '.') }} đ</td>
                        <td class="px-4 py-2 border">{{ number_format($lineTotal, 0, ',', '.') }} đ</td>
                       <td class="px-4 py-2 border">{{ number_format($detail->ingredient->track_stock, 0) }}</td>
                       <td class="px-4 py-2 border">{{ $detail->ingredient->base_unit }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('admin.import.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách</a>
            <form action="{{ route('admin.import.destroy', $import->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đơn nhập này không?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Xóa đơn nhập
            </button>
        </form>
        </div>
    </div>
</x-layouts.admin>