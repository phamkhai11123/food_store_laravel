<x-layouts.admin title="Thêm nhiều nguyên liệu">
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">🧪 Thêm nguyên liệu</h2>

        <form action="{{ route('admin.ingredients.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg space-y-6">
            @csrf

            <div class="overflow-x-auto">
                <div id="ingredient-rows" class="space-y-6">
                    <div class="flex gap-2 ingredient-row">
                        <input type="text" name="ingredients[0][sku]" placeholder="SKU" class="w-1/6 form-input text-sm h-12" required>
                        <input type="text" name="ingredients[0][name]" placeholder="Tên" class="w-1/6 form-input text-sm h-12" required>
                        <select name="ingredients[0][base_unit]" class="w-1/6 form-select text-sm h-12">
                            <option value="g">Gram</option>
                            <option value="ml">Mililit</option>
                            <option value="pc">Cái</option>
                        </select>
                        <input type="number" name="ingredients[0][track_stock]" placeholder="Tồn kho" class="w-1/6 form-input h-12 text-sm">
                        <input type="number" step="0.01" name="ingredients[0][suggested_unit_cost]" placeholder="Giá vốn" class="w-1/6 form-input text-sm">
                        <select name="ingredients[0][is_active]" class="w-1/6 form-select text-sm">
                            <option value="1">Hoạt động</option>
                            <option value="0">Ngừng</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="button" id="add-row"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    ➕ Thêm nguyên liệu
                </button>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 btn btn-primary font-semibold rounded-md shadow-sm">
                    💾 Lưu tất cả
                </button>
            </div>
        </form>
    </div>

    <script>
        let index = 1;
        document.getElementById('add-row').addEventListener('click', function () {
            const container = document.getElementById('ingredient-rows');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-2 ingredient-row';
            newRow.innerHTML = `
                <input type="text" name="ingredients[${index}][sku]" placeholder="SKU" class="w-1/6 h-12 form-input text-sm" required>
                <input type="text" name="ingredients[${index}][name]" placeholder="Tên" class="w-1/6 h-12 form-input text-sm" required>
                <select name="ingredients[${index}][base_unit]" class="w-1/6 h-12 form-select text-sm">
                    <option value="g">Gram</option>
                    <option value="ml">Mililit</option>
                    <option value="pc">Cái</option>
                </select>
                <input type="number" name="ingredients[${index}][track_stock]" placeholder="Tồn kho" class="w-1/6 form-input text-sm">
                <input type="number" step="0.01" name="ingredients[${index}][suggested_unit_cost]" placeholder="Giá vốn" class="w-1/6 form-input text-sm">
                <select name="ingredients[${index}][is_active]" class="w-1/6 form-select text-sm">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngừng</option>
                </select>
                  <hr class="my-2 border-dark">
            `;
            container.appendChild(newRow);
            index++;
        });
    </script>
</x-layouts.admin>