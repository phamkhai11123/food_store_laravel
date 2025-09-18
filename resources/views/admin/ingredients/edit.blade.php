<x-layouts.admin title="Cập nhật nguyên liệu">
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">🧪 Cập nhật nguyên liệu</h2>

        <form action="{{ route('admin.ingredients.update', $ingredient->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700">Mã SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $ingredient->sku) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên nguyên liệu</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $ingredient->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <div>
                    <label for="base_unit" class="block text-sm font-medium text-gray-700">Đơn vị cơ bản</label>
                    <select name="base_unit" id="base_unit"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="g" {{ old('base_unit', $ingredient->base_unit) == 'g' ? 'selected' : '' }}>Gram</option>
                        <option value="ml" {{ old('base_unit', $ingredient->base_unit) == 'ml' ? 'selected' : '' }}>Mililit</option>
                        <option value="pc" {{ old('base_unit', $ingredient->base_unit) == 'pc' ? 'selected' : '' }}>Cái</option>
                    </select>
                </div>

                <div>
                    <label for="track_stock" class="block text-sm font-medium text-gray-700">Số lượng còn</label>
                    <input type="number" name="track_stock" id="track_stock" value="{{ old('track_stock', $ingredient->track_stock) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="suggested_unit_cost" class="block text-sm font-medium text-gray-700">Giá vốn đề xuất</label>
                    <input type="number" step="0.01" name="suggested_unit_cost" id="suggested_unit_cost" value="{{ old('suggested_unit_cost', $ingredient->suggested_unit_cost) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="is_active" id="is_active"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1" {{ old('is_active', $ingredient->is_active) == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $ingredient->is_active) == '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-dark font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    💾 Cập nhật nguyên liệu
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>