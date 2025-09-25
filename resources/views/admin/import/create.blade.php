<x-layouts.admin title="Nhập thêm hàng">
    <div class="max-w-7xl mx-auto mt-6 flex flex-col lg:flex-row gap-8">
         <div class="lg:w-2/3 w-full">
        <h2 class="text-2xl font-bold mb-6">Nhập thêm hàng</h2>
        <form action="{{ route('admin.import.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Nhà cung cấp</label>
                <select name="supplier" id="supplier" class="w-full px-3 py-2 border rounded" required>
                    <option value="">-- Chọn nhà cung cấp --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier }}">{{ $supplier }}</option>
                    @endforeach
                </select>
            </div>

            <div id="import-lines" class="space-y-4">
                <div class="import-line flex flex-wrap items-center gap-4">
                    <select name="ingredients[0][id]" class="flex-1 min-w-[200px] px-3 py-2 border rounded" required>
                        <option value="">-- Chọn nguyên liệu --</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>

                    <input type="number" required name="ingredients[0][quantity]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Số lượng">
                    <input type="number" required step="0.01" name="ingredients[0][unit_price]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Giá nhập">

                    <button type="button" class="remove-line text-red-600 font-bold text-xl">&times;</button>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="button" id="add-line" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Thêm nguyên liệu</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Nhập hàng</button>
            </div>
        </form>
    </div>

    <div class="lg:w-1/3 w-full">
         <h2 class="text-xl font-bold mb-4">Nguyên liệu sắp hết</h2>
         @if($lowStockIngredients->isEmpty())
            <div class="text-sm text-gray-500 italic">
                🎉 Tất cả nguyên liệu đều đủ tồn kho. Không có món nào sắp hết!
            </div>
        @else

         <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tên nguyên liệu</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tồn kho</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Đơn vị</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockIngredients as $ingredient)
                    <tr class="">
                        <td class="px-4 py-2 text-sm text-gray-700 font-semibold">
                            {{ $ingredient->name }}
                        </td>
                        <td class="px-4 py-2 text-sm text-red-600 font-bold">
                            {{ number_format($ingredient->track_stock, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 font-semibold">
                            {{ $ingredient->base_unit }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    </div>
    </div>
    
    <script>
        let lineIndex = 1;

        document.getElementById('add-line').addEventListener('click', function () {
            const container = document.getElementById('import-lines');
            const newLine = document.createElement('div');
            newLine.classList.add('import-line', 'flex', 'flex-wrap', 'items-center', 'gap-4', 'mt-2');

            newLine.innerHTML = `
                <select name="ingredients[${lineIndex}][id]" class="flex-1 min-w-[200px] px-3 py-2 border rounded">
                    <option value="">-- Chọn nguyên liệu --</option>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>

                <input type="number" name="ingredients[${lineIndex}][quantity]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Số lượng">
                <input type="number" step="0.01" name="ingredients[${lineIndex}][unit_price]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Giá nhập">

                <button type="button" class="remove-line text-red-600 font-bold text-xl">&times;</button>
            `;
            container.appendChild(newLine);
            lineIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-line')) {
                e.target.closest('.import-line').remove();
            }
        });
    </script>
</x-layouts.admin>