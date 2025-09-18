<x-layouts.admin title="Nhập thêm hàng">
    <div class="max-w-7xl mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-6">Nhập thêm hàng</h1>

        <form action="{{ route('admin.import.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Nhà cung cấp</label>
                <select name="supplier" id="supplier" class="w-full px-3 py-2 border rounded">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier }}">{{ $supplier }}</option>
                    @endforeach
                </select>
            </div>

            <div id="import-lines" class="space-y-4">
                <div class="import-line flex flex-wrap items-center gap-4">
                    <select name="ingredients[0][id]" class="flex-1 min-w-[200px] px-3 py-2 border rounded">
                        <option value="">-- Chọn nguyên liệu --</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="ingredients[0][quantity]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Số lượng">
                    <input type="number" step="0.01" name="ingredients[0][unit_price]" class="flex-1 min-w-[150px] px-3 py-2 border rounded" placeholder="Giá nhập">

                    <button type="button" class="remove-line text-red-600 font-bold text-xl">&times;</button>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="button" id="add-line" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Thêm nguyên liệu</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Nhập hàng</button>
            </div>
        </form>
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