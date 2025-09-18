<x-layouts.admin title="Quản lý công thức món ăn">
    <div class="max-w-7xl mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-6">Danh sách món ăn & công thức</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white border rounded shadow p-4 flex flex-col">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-40 w-full object-cover rounded mb-4">

                    <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600 mb-2">Giá bán: <strong>{{ number_format($product->price, 0, ',', '.') }} đ</strong></p>
                    <p class="text-gray-600 mb-2">
                        Chi phí nguyên liệu: <strong>{{ number_format($product->ingredient_cost, 0, ',', '.') }} đ</strong>
                    </p>
                    <p class="text-sm text-gray-700 mb-2">Nguyên liệu:</p>
                    <ul class="flex overflow-hidden whitespace-nowrap text-sm text-gray-800 mb-4">
                        @forelse($product->recipeItems as $item)
                            <li class="mr-1 max-w-[150px]">
                                {{ $item->ingredient->name }}:
                                {{ rtrim(rtrim(number_format($item->quantity_per_portion_base, 3, '.', ''), '0'), '.')}}
                                {{ $item->ingredient->base_unit }},
                            </li>
                        @empty
                            <li class="text-gray-500 italic">Chưa có công thức</li>
                        @endforelse
                    </ul>


                    <a href="{{ route('admin.recipes.edit', $product->id) }}" class="mt-auto bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center">
                        Chỉnh sửa công thức
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.admin>