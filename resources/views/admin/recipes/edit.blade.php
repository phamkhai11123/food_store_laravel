<x-layouts.admin title="Chỉnh sửa công thức món ăn">
    <div class="max-w-4xl mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">Chỉnh sửa công thức: {{ $product->name }}</h1>

        <div class="mb-6 flex gap-6 items-center">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded">
            <div>
                <p class="text-lg font-semibold">{{ $product->name }}</p>
                <p class="text-gray-600">Giá bán: {{ number_format($product->price, 0, ',', '.') }} đ</p>
            </div>
        </div>

        <form action="{{ route('admin.recipes.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div id="recipe-lines" class="space-y-4">
                @foreach($product->recipeItems as $index => $item)
                    <div class="recipe-line flex items-center gap-4">
                        <select name="ingredients[{{ $index }}][ingredient_id]" class="w-1/3 px-3 py-2 border rounded">
                            @foreach($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                    {{ $ingredient->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number" step="0.01" name="ingredients[{{ $index }}][quantity_per_portion_base]" value="{{ $item->quantity_per_portion_base }}" class="w-1/4 px-3 py-2 border rounded" placeholder="Số lượng / khẩu phần">

                        <input type="text" name="ingredients[{{ $index }}][note]" value="{{ $item->note }}" class="w-1/3 px-3 py-2 border rounded" placeholder="Ghi chú (nếu có)">

                        <button type="button" class="remove-line text-red-600 font-bold text-xl">&times;</button>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex gap-4">
                <button type="button" id="add-line" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Thêm nguyên liệu</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Lưu công thức</button>
            </div>
        </form>
    </div>

    <script>
        let lineIndex = {{ $product->recipeItems->count() }};

        document.getElementById('add-line').addEventListener('click', function () {
            const container = document.getElementById('recipe-lines');
            const newLine = document.createElement('div');
            newLine.classList.add('recipe-line', 'flex', 'items-center', 'gap-4', 'mt-2');

            newLine.innerHTML = `
                <select name="ingredients[${lineIndex}][ingredient_id]" class="w-1/3 px-3 py-2 border rounded">
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>

                <input type="number" step="0.01" name="ingredients[${lineIndex}][quantity_per_portion_base]" class="w-1/4 px-3 py-2 border rounded" placeholder="Số lượng / khẩu phần">
                <input type="text" name="ingredients[${lineIndex}][note]" class="w-1/3 px-3 py-2 border rounded" placeholder="Ghi chú (nếu có)">
                <button type="button" class="remove-line text-red-600 font-bold text-xl">&times;</button>
            `;
            container.appendChild(newLine);
            lineIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-line')) {
                e.target.closest('.recipe-line').remove();
            }
        });
    </script>
</x-layouts.admin>